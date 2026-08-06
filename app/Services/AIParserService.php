<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIParserService
{
    protected string $apiKey;
    protected string $apiUrl;
    protected string $model;
    protected int $timeout;
    protected string $provider;
    protected ?OpenRouterVisionService $openRouterVision;
    protected AttendanceImportAdapter $adapter;

    public function __construct(?OpenRouterVisionService $openRouterVision = null, ?AttendanceImportAdapter $adapter = null)
    {
        $this->apiKey  = config('ocr.ai_api_key', '');
        $this->apiUrl  = config('ocr.ai_api_url', '');
        $this->model   = config('ocr.ai_model', 'gemini-2.0-flash');
        $this->timeout = (int) config('ocr.gemini_timeout', config('ocr.ai_timeout', 30));
        $this->provider = config('ocr.ai_provider', 'openrouter');
        $this->openRouterVision = $openRouterVision;
        $this->adapter = $adapter ?? app(AttendanceImportAdapter::class);
    }

    public function isAvailable(): bool
    {
        return $this->apiKey !== '' && $this->apiUrl !== '';
    }

    /**
     * Parse attendance photo via AI Vision (OpenRouter or Gemini, per ai_provider config).
     * Falls back to Gemini if OpenRouter not configured, then to local OCR in caller.
     *
     * @return array{success: bool, data?: array, error?: string, source: string, warning?: string, ai_error?: string}
     */
    public function parseFromImage(string $imagePath, int $bulan, int $tahun, array $context = []): array
    {
        $providers = [];
        $priority = config('ocr.provider_priority', ['openrouter', 'gemini', 'local_ocr']);

        foreach ($priority as $provider) {
            if ($provider === 'openrouter' && !(bool) config('ocr.enable_openrouter_primary', true)) {
                continue;
            }
            if ($provider === 'gemini' && !$this->isAvailable()) {
                continue;
            }
            if (in_array($provider, ['openrouter', 'gemini', 'local_ocr'], true)) {
                $providers[] = $provider;
            }
        }

        Log::info('Attendance provider orchestrator started.', [
            'stage' => 'Provider Orchestrator',
            'providers' => $providers,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        foreach ($providers as $provider) {
            $result = $this->runProviderWithRetry($provider, $imagePath, $bulan, $tahun, $context);
            if ($result['success'] ?? false) {
                return $result;
            }

            Log::warning('Attendance provider failed, switching fallback.', [
                'stage' => 'Fallback',
                'provider' => $provider,
                'error' => $result['error'] ?? 'unknown',
            ]);
        }

        Log::error('Attendance provider orchestrator exhausted all providers.', [
            'stage' => 'Error Report',
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        return [
            'success' => false,
            'error'   => 'Semua provider import absensi gagal diproses.',
            'source'  => 'error',
        ];
    }

    protected function runProviderWithRetry(string $provider, string $imagePath, int $bulan, int $tahun, array $context = []): array
    {
        $maxRetries = match ($provider) {
            'openrouter' => (int) config('ocr.openrouter_max_retries', 2),
            'gemini'     => (int) config('ocr.gemini_max_retries', 1),
            'local_ocr'  => (int) config('ocr.local_ocr_max_retries', 1),
            default      => 0,
        };

        $attempts = max(1, $maxRetries + 1);

        for ($attempt = 1; $attempt <= $attempts; $attempt++) {
            Log::info('Attendance provider attempt.', [
                'stage' => 'Retry',
                'provider' => $provider,
                'attempt' => $attempt,
                'max_retries' => $maxRetries,
                'attempts_total' => $attempts,
            ]);

            $result = match ($provider) {
                'openrouter' => ($this->openRouterVision && $this->openRouterVision->isAvailable())
                    ? $this->openRouterVision->parseFromImage($imagePath, $bulan, $tahun, $context)
                    : ['success' => false, 'error' => 'OpenRouter tidak tersedia.', 'source' => 'error'],
                'gemini'     => $this->parseFromImageGemini($imagePath, $bulan, $tahun, $context),
                'local_ocr'  => $this->parseFromLocalOcr($imagePath, $context),
                default      => ['success' => false, 'error' => 'Provider tidak dikenali.', 'source' => 'error'],
            };

            if ($result['success'] ?? false) {
                return $result;
            }
        }

        return $result ?? ['success' => false, 'error' => 'Provider gagal tanpa hasil.', 'source' => 'error'];
    }

    /**
     * Parse via Gemini Vision (legacy path).
     */
    protected function parseFromImageGemini(string $imagePath, int $bulan, int $tahun, array $context = []): array
    {
        try {
            $fullPath = storage_path('app/' . $imagePath);
            if (!file_exists($fullPath)) {
                return ['success' => false, 'error' => 'File gambar tidak ditemukan.', 'source' => 'error'];
            }

            $imageData = file_get_contents($fullPath);
            $mimeType  = mime_content_type($fullPath) ?: 'image/jpeg';
            $base64    = base64_encode($imageData);

            $prompt = $this->buildVisionPrompt($bulan, $tahun);
            $response = $this->callGeminiVision($this->apiUrl, $prompt, $base64, $mimeType);

            if (!$response['success']) {
                Log::warning('Gemini Vision API failed.', ['error' => $response['error']]);
                return [
                    'success' => false,
                    'error'   => 'Gemini Vision gagal: ' . $response['error'],
                    'source'  => 'error',
                ];
            }

            $parsed = $this->parseAiResponse($response['text']);

            if (!$parsed) {
                Log::warning('AI Parser: failed to parse Gemini Vision response.');
                return [
                    'success' => false,
                    'error'   => 'Gagal memparse respons Gemini Vision.',
                    'source'  => 'error',
                ];
            }

            $universal = $this->adapter->buildUniversalSkeleton([
                'provider'       => 'gemini',
                'provider_model' => $this->model,
                'kelas'          => $context['kelas'] ?? '',
                'bulan'          => $bulan,
                'tahun'          => $tahun,
                'students'       => $this->mapLegacyStudentsToUniversal($parsed['siswa'] ?? []),
                'warnings'       => $parsed['warnings'] ?? [],
                'confidence'     => [
                    'provider' => 0.88,
                    'ocr'      => 0.88,
                    'matching' => 0,
                    'overall'  => 0.88,
                ],
                'pipeline'       => [
                    'classification' => 'pending',
                    'quality'        => 'pending',
                    'preprocess'     => 'pending',
                    'vision'         => 'success',
                    'validation'     => 'pending',
                ],
                'meta' => $parsed['meta'] ?? [],
            ]);

            return [
                'success'   => true,
                'data'      => $parsed,
                'source'    => 'gemini',
                'universal' => $universal,
            ];
        } catch (\Exception $e) {
            Log::error('Gemini Vision exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error'   => 'Exception Gemini Vision: ' . $e->getMessage(),
                'source'  => 'error',
            ];
        }
    }

    /**
     * Parse attendance photo using local Tesseract OCR via Python script.
     * Runs entirely offline — no API key needed.
     *
     * @return array{success: bool, data?: array, error?: string, source: string, warning?: string}
     */
    public function parseFromLocalOcr(string $imagePath, array $context = []): array
    {
        $pythonPath    = config('ocr.python_path', '');
        $tesseractPath = config('ocr.tesseract_path', '');
        $scriptPath    = config('ocr.ocr_script', 'scripts/ocr_attendance.py');

        if (!file_exists($pythonPath)) {
            return [
                'success' => false,
                'error'   => 'Python tidak ditemukan: ' . $pythonPath,
                'source'  => 'error',
            ];
        }

        $fullScript = base_path($scriptPath);
        if (!file_exists($fullScript)) {
            return [
                'success' => false,
                'error'   => 'Script OCR tidak ditemukan: ' . $scriptPath,
                'source'  => 'error',
            ];
        }

        $fullImagePath = storage_path('app/' . $imagePath);
        if (!file_exists($fullImagePath)) {
            return [
                'success' => false,
                'error'   => 'File gambar tidak ditemukan.',
                'source'  => 'error',
            ];
        }

        $cmd = escapeshellarg($pythonPath) . ' ' . escapeshellarg($fullScript) . ' ' . escapeshellarg($fullImagePath);
        if ($tesseractPath) {
            $cmd .= ' ' . escapeshellarg($tesseractPath);
        }

        Log::info('Running local OCR', ['cmd' => $cmd]);

        try {
            $output = [];
            $exitCode = 0;
            set_time_limit((int) config('ocr.local_ocr_timeout', 120));
            exec($cmd . ' 2>&1', $output, $exitCode);

            $jsonStr = implode("\n", $output);
            $result = json_decode($jsonStr, true);

            if (!$result || empty($result['success'])) {
                $errorMsg = $result['error'] ?? 'Local OCR gagal (exit code: ' . $exitCode . ')';
                if (!$result && trim($jsonStr) !== '') {
                    $errorMsg .= ' Output: ' . trim($jsonStr);
                }
                Log::warning('Local OCR failed', ['error' => $errorMsg, 'output' => $jsonStr]);
                return [
                    'success' => false,
                    'error'   => $errorMsg,
                    'source'  => 'error',
                ];
            }

            $siswaList = $result['siswa'] ?? [];

            $parsed = [
                'siswa' => $siswaList,
                'meta' => $result['meta'] ?? [],
                'ocr_text' => collect($siswaList)->map(function ($row) {
                    $statuses = collect($row['ketidakhadiran'] ?? [])->map(function ($entry) {
                        return ($entry['tanggal'] ?? '?') . ':' . ($entry['status'] ?? '?');
                    })->implode(' ');
                    return trim(($row['nisn'] ?? '') . ' ' . ($row['nama_ocr'] ?? '') . ' ' . $statuses);
                })->implode("\n"),
            ];

            $validStatuses = ['H', 'I', 'S', 'A', 'LIBUR', 'UNKNOWN'];
            foreach ($parsed['siswa'] as &$siswa) {
                if (!isset($siswa['ketidakhadiran']) || !is_array($siswa['ketidakhadiran'])) {
                    $siswa['ketidakhadiran'] = [];
                }
                $cleaned = [];
                foreach ($siswa['ketidakhadiran'] as $entry) {
                    if (!is_array($entry)) continue;
                    $t = (int) ($entry['tanggal'] ?? 0);
                    $s = strtoupper(trim((string) ($entry['status'] ?? '')));
                    $c = (float) ($entry['confidence'] ?? 0.5);
                    if ($t >= 1 && $t <= 31 && in_array($s, $validStatuses, true)) {
                        $cleaned[] = [
                            'tanggal'    => $t,
                            'status'     => $s,
                            'confidence' => max(0.0, min(1.0, $c)),
                        ];
                    }
                }
                $siswa['ketidakhadiran'] = $cleaned;
                if (!isset($siswa['warnings']) || !is_array($siswa['warnings'])) {
                    $siswa['warnings'] = [];
                }
            }
            unset($siswa);

            $universal = $this->adapter->buildUniversalSkeleton([
                'provider'       => 'local_ocr',
                'provider_model' => 'tesseract',
                'kelas'          => $context['kelas'] ?? ($result['metadata']['kelas'] ?? ''),
                'bulan'          => $context['bulan'] ?? ($result['metadata']['bulan'] ?? null),
                'tahun'          => $context['tahun'] ?? ($result['metadata']['tahun'] ?? null),
                'students'       => $this->mapLegacyStudentsToUniversal($parsed['siswa'] ?? []),
                'warnings'       => $result['warnings'] ?? [],
                'confidence'     => [
                    'provider' => 0.72,
                    'ocr'      => 0.72,
                    'matching' => 0,
                    'overall'  => 0.72,
                ],
                'pipeline'       => [
                    'classification' => 'pending',
                    'quality'        => 'pending',
                    'preprocess'     => 'pending',
                    'vision'         => 'fallback_local_ocr',
                    'validation'     => 'pending',
                ],
                'meta' => array_merge($parsed['meta'] ?? [], $result['metadata'] ?? []),
            ]);

            return [
                'success'   => true,
                'data'      => $parsed,
                'source'    => 'local_ocr',
                'warning'   => 'Data diproses menggunakan OCR lokal (Tesseract). Wajib diverifikasi oleh operator.',
                'universal' => $universal,
            ];
        } catch (\Exception $e) {
            Log::error('Local OCR exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error'   => 'Exception local OCR: ' . $e->getMessage(),
                'source'  => 'error',
            ];
        }
    }

    /**
     * Call Gemini API with inline image (Vision).
     */
    protected function callGeminiVision(string $url, string $prompt, string $base64Image, string $mimeType): array
    {
        $fullUrl = $this->buildGeminiUrl($url) . '?key=' . $this->apiKey;

        try {
            $response = Http::timeout(max($this->timeout, 60))
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($fullUrl, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                                [
                                    'inline_data' => [
                                        'mime_type' => $mimeType,
                                        'data'      => $base64Image,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature'     => 0.1,
                        'maxOutputTokens' => 4096,
                    ],
                ]);

            if ($response->failed()) {
                $body = $response->json();
                $errMsg = $body['error']['message'] ?? ('HTTP ' . $response->status());
                return ['success' => false, 'error' => $errMsg];
            }

            $body = $response->json();
            $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? '';

            if (empty($text)) {
                return ['success' => false, 'error' => 'Respons Vision kosong.'];
            }

            return ['success' => true, 'text' => $text];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Parse OCR raw text into structured JSON via AI.
     *
     * @return array{success: bool, data?: array, error?: string, source: string, warning?: string, ai_error?: string}
     */
    public function parse(string $ocrText, int $bulan, int $tahun): array
    {
        if (!$this->isAvailable()) {
            Log::info('AI Parser not configured, using fallback.');
            return $this->fallbackParse($ocrText, $bulan, $tahun);
        }

        try {
            $prompt = $this->buildPrompt($ocrText, $bulan, $tahun);
            $response = $this->callApi($prompt);

            if (!$response['success']) {
                Log::warning('AI Parser API failed, using fallback.', ['error' => $response['error']]);
                $result = $this->fallbackParse($ocrText, $bulan, $tahun);
                $result['ai_error'] = $response['error'];
                return $result;
            }

            $parsed = $this->parseAiResponse($response['text']);

            if (!$parsed) {
                Log::warning('AI Parser: failed to parse AI response, using fallback.');
                $result = $this->fallbackParse($ocrText, $bulan, $tahun);
                $result['ai_error'] = 'Gagal memparse respons AI.';
                return $result;
            }

            return [
                'success' => true,
                'data'    => $parsed,
                'source'  => 'ai',
            ];
        } catch (\Exception $e) {
            Log::error('AI Parser exception', ['error' => $e->getMessage()]);
            $result = $this->fallbackParse($ocrText, $bulan, $tahun);
            $result['ai_error'] = $e->getMessage();
            return $result;
        }
    }

    protected function buildPrompt(string $ocrText, int $bulan, int $tahun): string
    {
        $daysInMonth = (int) date('t', mktime(0, 0, 0, $bulan, 1, $tahun));

        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $mapping = [];
        $liburDates = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $ts = mktime(0, 0, 0, $bulan, $d, $tahun);
            $dayOfWeek = (int) date('w', $ts);
            $label = $dayNames[$dayOfWeek];
            if ($dayOfWeek === 5) {
                $label .= '(LIBUR)';
                $liburDates[] = $d;
            }
            $mapping[] = $d . '=' . $label;
        }
        $mappingLines = [];
        for ($i = 0; $i < count($mapping); $i += 8) {
            $mappingLines[] = implode(', ', array_slice($mapping, $i, 8));
        }
        $mappingBlock = implode(",\n", $mappingLines);
        $liburStr = !empty($liburDates) ? implode(', ', $liburDates) : 'tidak ada';

        $prompt = 'Kamu adalah sistem OCR ahli yang bertugas membaca foto/scan dokumen buku absensi siswa secara langsung.

Tugas utamanya adalah memetakan tabel absensi fisik pada gambar dan mengidentifikasi ketidakhadiran siswa (I, S, A) untuk setiap tanggal.

LANGKAH ANALISIS TUGAS:
1. Identifikasi baris header tabel untuk menentukan urutan nomor tanggal (misalnya tanggal 1 sampai ' . $daysInMonth . ').
2. Perhatikan mapping hari di bawah ini — tanggal bertanda (LIBUR) tidak perlu dibaca.
3. Untuk setiap baris data siswa dari atas ke bawah:
   - Ambil NISN jika tertera di gambar (jika tidak ada/tidak jelas, isi null).
   - Ambil Nama Siswa selengkap dan seakurat mungkin sesuai tulisan di gambar.
4. Untuk setiap kolom tanggal pada baris siswa tersebut:
   - Jika tanggal itu LIBUR, SKIP — jangan catat apapun.
   - Evaluasi tanda/karakter kehadiran di dalam sel.
   - HANYA catat jika statusnya KETIDAKHADIRAN:
     * "I" = Izin
     * "S" = Sakit
     * "A" = Alpa / Tanpa Keterangan
   - JANGAN catat jika statusnya Hadir (simbol centang, titik ".", atau sel kosong). Default sistem adalah Hadir.
   - Jika karakter agak buram/meragukan, berikan nilai confidence antara 0.0 sampai 1.0.

MAPPING HARI (Bulan: ' . $bulan . ', Tahun: ' . $tahun . '):
' . $mappingBlock . '

Tanggal LIBUR: ' . $liburStr . ' (Jumat). JANGAN catat apapun untuk tanggal-tanggal ini.

ATURAN OUTPUT (STRICT JSON):
1. Hasil WAJIB berupa JSON murni tanpa narasi, tanpa pembuka/penutup, dan tanpa tag markdown.
2. Gunakan struktur skema JSON berikut secara ketat:

{
  "total_baris_terbaca": number,
  "catatan_gambar": "catatan singkat jika ada kendala kualitas foto (misal: gambar agak miring, bagian kanan agak gelap). Jika tidak ada kendala, tulis null.",
  "data_absensi": [
    {
      "baris_ke": number,
      "nisn": "string angka atau null",
      "nama_ocr": "Nama Siswa Lengkap",
      "ketidakhadiran": [
        {
          "tanggal": number,
          "status": "I atau S atau A",
          "confidence": number_antara_0_dan_1
        }
      ]
    }
  ]
}

PANDUAN PEMBACAAN KARAKTER TULISAN TANGAN:
- Huruf "I" (Izin) kadang menyerupai angka 1, huruf "l", atau garis vertikal "|".
- Huruf "S" (Sakit) kadang menyerupai angka 5.
- Huruf "A" (Alpa) kadang menyerupai angka 4 atau huruf "V" terbalik.

ANTI-HALUSINASI:
- Jika ragu antara karakter mirip (misal "I" vs "l" vs "1"), gunakan konteks baris tabel.
- Jika posisi tanggal tidak jelas karena struktur tabel rusak/tergeser, JANGAN menebak.
- Lebih baik MELEWATI satu status daripada mengarang tanggal yang salah.
- Jika ada I/S/A yang terbaca tetapi posisi tanggal tidak dapat dipastikan, tetap masukkan dengan confidence rendah (0.3-0.5).

Bulan: ' . $bulan . ', Tahun: ' . $tahun . '. Jumlah hari: ' . $daysInMonth . '.

CONTOH INPUT OCR:
KELAS 4A
BULAN : JULI 2026
WALI KELAS : UST. AHMAD FAUZI, S.Pd
1 318245974 AFIQOTUL ULYA . . I . . S . . . .
2 318587180 AVYANUL AMAL . . . S . . . A . . .

CONTOH OUTPUT JSON YANG BENAR:
{
  "total_baris_terbaca": 2,
  "catatan_gambar": null,
  "data_absensi": [
    {
      "baris_ke": 1,
      "nisn": "318245974",
      "nama_ocr": "AFIQOTUL ULYA",
      "ketidakhadiran": [
        {"tanggal": 3, "status": "I", "confidence": 0.95},
        {"tanggal": 6, "status": "S", "confidence": 0.91}
      ]
    },
    {
      "baris_ke": 2,
      "nisn": "318587180",
      "nama_ocr": "AVYANUL AMAL",
      "ketidakhadiran": [
        {"tanggal": 4, "status": "S", "confidence": 0.88},
        {"tanggal": 8, "status": "A", "confidence": 0.92}
      ]
    }
  ]
}

HANYA output JSON, tidak ada teks lain. Langsung buka dengan { dan tutup dengan }.

Teks OCR:
' . $ocrText;

        return $prompt;
    }

    /**
     * Build vision prompt for Gemini — analyzes image directly (no OCR text needed).
     */
    protected function buildVisionPrompt(int $bulan, int $tahun): string
    {
        $daysInMonth = (int) date('t', mktime(0, 0, 0, $bulan, 1, $tahun));

        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $mapping = [];
        $liburDates = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $ts = mktime(0, 0, 0, $bulan, $d, $tahun);
            $dayOfWeek = (int) date('w', $ts);
            $label = $dayNames[$dayOfWeek];
            if ($dayOfWeek === 5) {
                $label .= '(LIBUR)';
                $liburDates[] = $d;
            }
            $mapping[] = $d . '=' . $label;
        }

        $mappingLines = [];
        for ($i = 0; $i < count($mapping); $i += 8) {
            $mappingLines[] = implode(', ', array_slice($mapping, $i, 8));
        }
        $mappingBlock = implode(",\n", $mappingLines);

        $liburStr = !empty($liburDates) ? implode(', ', $liburDates) : 'tidak ada';

        return 'Kamu adalah sistem OCR ahli yang membaca foto/scan dokumen buku absensi siswa secara langsung dari gambar.

TUGAS:
Analisis foto buku absensi ini. Identifikasi tabel absensi, baca setiap baris siswa, dan ekstrak data ketidakhadiran (I, S, A) untuk setiap tanggal.

LANGKAH ANALISIS:
1. Lihat gambar dan identifikasi baris header tabel untuk menentukan urutan nomor tanggal (1 sampai ' . $daysInMonth . ').
2. Perhatikan mapping hari di bawah ini — tanggal bertanda (LIBUR) tidak perlu dibaca.
3. Untuk setiap baris data siswa dari atas ke bawah:
   - Baca NISN jika tertera (jika tidak ada/tidak jelas, isi null).
   - Baca Nama Siswa selengkap dan seakurat mungkin.
4. Untuk setiap kolom tanggal pada baris siswa:
   - Jika tanggal itu LIBUR, SKIP — jangan catat apapun.
   - Lihat tanda/karakter di dalam sel.
   - HANYA catat KETIDAKHADIRAN: "I" = Izin, "S" = Sakit, "A" = Alpa.
   - JANGAN catat Hadir (centang, titik, centang silang, atau sel kosong). Default = Hadir.
   - Jika karakter agak buram/meragukan, berikan confidence 0.0-1.0.

MAPPING HARI (Bulan: ' . $bulan . ', Tahun: ' . $tahun . '):
' . $mappingBlock . '

Tanggal LIBUR: ' . $liburStr . ' (Jumat). JANGAN catat apapun untuk tanggal-tanggal ini.

ATURAN OUTPUT (STRICT JSON):
Kembalikan HANYA JSON murni. Tanpa narasi, tanpa markdown wrapper.

{
  "total_baris_terbaca": number,
  "catatan_gambar": "catatan singkat kendala kualitas foto, atau null",
  "data_absensi": [
    {
      "baris_ke": number,
      "nisn": "string atau null",
      "nama_ocr": "Nama Siswa",
      "ketidakhadiran": [
        {"tanggal": number, "status": "I/S/A", "confidence": number}
      ]
    }
  ]
}

PANDUAN VISUAL:
- "I" (Izin): garis vertikal, mirip angka 1 atau huruf l
- "S" (Sakit): kurva seperti angka 5
- "A" (Alpa): segitiga terbalik, mirip angka 4 atau V
- Centang ✓ atau ✓ = Hadir, JANGAN dicatat
- Titik . = Hadir, JANGAN dicatat
- Sel kosong = Hadir, JANGAN dicatat

ANTI-HALUSINASI:
- Jika ragu antara karakter mirip, gunakan konteks baris dan kolom.
- Jika posisi tanggal tidak pasti karena tabel rusak/tergeser, JANGAN menebak.
- Lebih baik melewati satu status daripada mengarang tanggal yang salah.

HANYA output JSON, langsung mulai dengan { dan tutup dengan }.';
    }

    protected function callApi(string $prompt): array
    {
        $url = $this->apiUrl;
        $isGemini = str_contains($url, 'generativelanguage.googleapis.com');

        if ($isGemini) {
            return $this->callGemini($prompt);
        }

        return $this->callOpenAiCompatible($url, $prompt);
    }

    /**
     * Build full Gemini API URL from base URL + model.
     * Handles: base only, base/models/, or full endpoint URL.
     */
    protected function buildGeminiUrl(string $baseOrFullUrl): string
    {
        $url = rtrim($baseOrFullUrl, '/');

        // Already a full endpoint with /models/...:generateContent
        if (preg_match('#/models/[^/]+:generateContent#', $url)) {
            return $url;
        }

        // Base URL ending with /v1beta or /v1 — append /models/{model}:generateContent
        $model = $this->model;
        return $url . '/models/' . $model . ':generateContent';
    }

    protected function callGemini(string $prompt): array
    {
        $fullUrl = $this->buildGeminiUrl($this->apiUrl) . '?key=' . $this->apiKey;

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($fullUrl, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature'     => 0.1,
                        'maxOutputTokens' => 4096,
                    ],
                ]);

            if ($response->failed()) {
                $body = $response->json();
                $errMsg = $body['error']['message'] ?? ('HTTP ' . $response->status());
                return ['success' => false, 'error' => $errMsg];
            }

            $body = $response->json();
            $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? '';

            if (empty($text)) {
                return ['success' => false, 'error' => 'Respons AI kosong.'];
            }

            return ['success' => true, 'text' => $text];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function callOpenAiCompatible(string $url, string $prompt): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ])
                ->post($url, [
                    'model'       => $this->model,
                    'messages'    => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature'     => 0.1,
                    'max_tokens'      => 4096,
                    'response_format' => ['type' => 'json_object'],
                ]);

            if ($response->failed()) {
                return ['success' => false, 'error' => 'HTTP ' . $response->status()];
            }

            $body = $response->json();
            $text = $body['choices'][0]['message']['content'] ?? '';

            if (empty($text)) {
                return ['success' => false, 'error' => 'Respons AI kosong.'];
            }

            return ['success' => true, 'text' => $text];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Validate and normalize AI response JSON.
     * Supports both new format (data_absensi/baris_ke) and legacy format (siswa/no).
     * Always returns legacy format {siswa: [...]} for downstream compatibility.
     */
    protected function parseAiResponse(string $text): ?array
    {
        $text = trim($text);

        if (str_starts_with($text, '```')) {
            $text = preg_replace('/^```(?:json)?\s*/i', '', $text);
            $text = preg_replace('/\s*```$/', '', $text);
            $text = trim($text);
        }

        $decoded = json_decode($text, true);

        if (!$decoded) {
            return null;
        }

        // New format: data_absensi with baris_ke
        if (isset($decoded['data_absensi']) && is_array($decoded['data_absensi'])) {
            $siswaList = [];
            foreach ($decoded['data_absensi'] as $row) {
                if (!is_array($row)) continue;
                $siswaList[] = [
                    'no'              => (int) ($row['baris_ke'] ?? 0),
                    'nisn'            => $row['nisn'] ?? null,
                    'nama_ocr'        => $row['nama_ocr'] ?? '',
                    'ketidakhadiran'  => $row['ketidakhadiran'] ?? [],
                    'warnings'        => $row['warnings'] ?? [],
                ];
            }
            $decoded['siswa'] = $siswaList;
        }

        // Legacy format: siswa with no (direct use)
        if (!isset($decoded['siswa']) || !is_array($decoded['siswa'])) {
            return null;
        }

        $validStatuses = ['I', 'S', 'A'];

        foreach ($decoded['siswa'] as &$siswa) {
            if (!isset($siswa['ketidakhadiran']) || !is_array($siswa['ketidakhadiran'])) {
                $siswa['ketidakhadiran'] = [];
            }

            $cleaned = [];
            foreach ($siswa['ketidakhadiran'] as $entry) {
                if (!is_array($entry)) continue;

                $tanggal = (int) ($entry['tanggal'] ?? 0);
                $status  = strtoupper(trim((string) ($entry['status'] ?? '')));
                $confidence = (float) ($entry['confidence'] ?? 0.5);

                if ($tanggal >= 1 && $tanggal <= 31 && in_array($status, $validStatuses, true)) {
                    $cleaned[] = [
                        'tanggal'    => $tanggal,
                        'status'     => $status,
                        'confidence' => max(0.0, min(1.0, $confidence)),
                    ];
                }
            }
            $siswa['ketidakhadiran'] = $cleaned;

            if (!isset($siswa['warnings']) || !is_array($siswa['warnings'])) {
                $siswa['warnings'] = [];
            }
        }
        unset($siswa);

        return $decoded;
    }

    public function parseOcrTextToJson(string $ocrText, int $bulan, int $tahun): array
    {
        if (trim($ocrText) === '') {
            return [
                'success' => false,
                'error'   => 'Teks OCR kosong.',
                'source'  => 'error',
            ];
        }

        return $this->fallbackParse($ocrText, $bulan, $tahun);
    }

    /**
     * Fallback PHP parser — no AI needed.
     * Parses OCR text with regex to find I/S/A per student line.
     * Produces same JSON format as AI for downstream compatibility.
     *
     * @return array{success: bool, data: array, source: string, warning?: string}
     */
    protected function mapLegacyStudentsToUniversal(array $students): array
    {
        return array_map(function (array $student, int $index) {
            return [
                'row_number'    => $student['no'] ?? ($index + 1),
                'nisn'          => $student['nisn'] ?? null,
                'nomor_induk'   => $student['nomor_induk'] ?? null,
                'name'          => $student['nama_ocr'] ?? '',
                'attendance'    => $student['ketidakhadiran'] ?? [],
                'warnings'      => $student['warnings'] ?? [],
                'confidence'    => $student['confidence'] ?? 0.6,
            ];
        }, $students, array_keys($students));
    }

    public function fallbackParse(string $ocrText, int $bulan, int $tahun): array
    {
        $daysInMonth = (int) date('t', mktime(0, 0, 0, $bulan, 1, $tahun));
        $lines = preg_split('/\r?\n/', trim($ocrText));
        $siswaList = [];
        $no = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;

            $no++;

            $nisn = '';
            $nama = '';
            $textAfterNama = $line;

            if (preg_match('/^(\d{1,2})\s+(\d{6,20})\s+(.+?)\s+([.\sISAsai\d].*)$/i', $line, $m)) {
                $nisn = $m[2];
                $nama = trim($m[3]);
                $textAfterNama = $m[4];
            } elseif (preg_match('/^(\d{1,2})\s+(\d{6,20})\s+(.+)$/i', $line, $m)) {
                $nisn = $m[2];
                $nama = trim($m[3]);
                $textAfterNama = '';
            } elseif (preg_match('/^(\d{6,20})\s+(.+?)\s+([.\sISAsai\d].*)$/i', $line, $m)) {
                $nisn = $m[1];
                $nama = trim($m[2]);
                $textAfterNama = $m[3];
            } elseif (preg_match('/^(\d{6,20})\s+(.+)$/i', $line, $m)) {
                $nisn = $m[1];
                $nama = trim($m[2]);
                $textAfterNama = '';
            }

            if ($nama === '' && $nisn === '') {
                if (preg_match('/^(.+?)\s+([.\sISAsai\d].*)$/i', $line, $m)) {
                    $nama = trim($m[1]);
                    $textAfterNama = $m[2];
                } else {
                    continue;
                }
            }

            $ketidakhadiran = [];
            $warnings = [];

            if ($textAfterNama !== '') {
                $tokens = preg_split('/\s+/', trim($textAfterNama));
                $dayNum = 1;
                foreach ($tokens as $token) {
                    if ($dayNum > $daysInMonth) break;

                    $char = $token[0] ?? '';
                    if ($char === '.' || $char === '' || ctype_digit($token)) {
                        $dayNum++;
                        continue;
                    }

                    $upper = strtoupper($char);
                    if (in_array($upper, ['I', 'S', 'A'], true)) {
                        $ketidakhadiran[] = [
                            'tanggal'    => $dayNum,
                            'status'     => $upper,
                            'confidence' => 0.6,
                        ];
                    } elseif ($dayNum <= $daysInMonth) {
                        $warnings[] = [
                            'status' => $upper,
                            'reason' => 'Karakter "' . $char . '" di posisi tanggal ' . $dayNum . ' tidak dikenali sebagai I/S/A',
                        ];
                    }
                    $dayNum++;
                }
            }

            $siswaList[] = [
                'no'              => $no,
                'nisn'            => $nisn,
                'nama_ocr'        => $nama,
                'ketidakhadiran'  => $ketidakhadiran,
                'warnings'        => $warnings,
            ];
        }

        $universal = $this->adapter->buildUniversalSkeleton([
            'provider'       => 'fallback_parser',
            'provider_model' => 'regex',
            'bulan'          => $bulan,
            'tahun'          => $tahun,
            'students'       => $this->mapLegacyStudentsToUniversal($siswaList),
            'confidence'     => [
                'provider' => 0.6,
                'ocr'      => 0.6,
                'matching' => 0,
                'overall'  => 0.6,
            ],
            'pipeline' => [
                'classification' => 'skipped',
                'quality'        => 'skipped',
                'preprocess'     => 'skipped',
                'vision'         => 'fallback_parser',
                'validation'     => 'pending',
            ],
            'meta' => ['raw_text_length' => mb_strlen($ocrText)],
        ]);

        return [
            'success'   => true,
            'data'      => ['siswa' => $siswaList],
            'source'    => 'fallback',
            'warning'   => 'AI Parser tidak tersedia. Data diproses menggunakan parser sederhana dan wajib diverifikasi oleh operator.',
            'universal' => $universal,
        ];
    }
}
