<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterVisionService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->apiKey  = config('ocr.openrouter_api_key', '');
        $this->model   = config('ocr.openrouter_model', 'google/gemini-2.5-flash');
        $this->baseUrl = config('ocr.openrouter_base_url', 'https://openrouter.ai/api/v1/chat/completions');
        $this->timeout = (int) config('ocr.ai_timeout', 60);
    }

    public function isAvailable(): bool
    {
        return $this->apiKey !== '' && $this->model !== '';
    }

    /**
     * Parse attendance photo via OpenRouter Vision API.
     * Sends image as base64 inline data using OpenAI-compatible multimodal format.
     *
     * @return array{success: bool, data?: array, error?: string, source: string}
     */
    public function parseFromImage(string $imagePath, int $bulan, int $tahun): array
    {
        if (!$this->isAvailable()) {
            Log::info('OpenRouter Vision not configured.');
            return [
                'success' => false,
                'error'   => 'OpenRouter belum dikonfigurasi. Set OPENROUTER_API_KEY di .env.',
                'source'  => 'error',
            ];
        }

        try {
            $fullPath = storage_path('app/' . $imagePath);
            if (!file_exists($fullPath)) {
                return ['success' => false, 'error' => 'File gambar tidak ditemukan.', 'source' => 'error'];
            }

            $imageData = file_get_contents($fullPath);
            $mimeType  = mime_content_type($fullPath) ?: 'image/jpeg';
            $base64    = base64_encode($imageData);

            $prompt = $this->buildVisionPrompt($bulan, $tahun);

            Log::info('OpenRouter Vision request started.', [
                'provider' => 'openrouter',
                'model'    => $this->model,
                'mime'     => $mimeType,
            ]);

            $startTime = microtime(true);

            $response = Http::timeout(max($this->timeout, 60))
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ])
                ->post($this->baseUrl, [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role'    => 'user',
                            'content' => [
                                ['type' => 'text', 'text' => $prompt],
                                [
                                    'type'      => 'image_url',
                                    'image_url' => [
                                        'url' => 'data:' . $mimeType . ';base64,' . $base64,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'temperature'     => 0.1,
                    'max_tokens'      => 4096,
                    'response_format' => ['type' => 'json_object'],
                ]);

            $elapsed = round((microtime(true) - $startTime) * 1000);

            if ($response->failed()) {
                $body     = $response->json();
                $errMsg   = $body['error']['message'] ?? ('HTTP ' . $response->status());
                $errorObj = $body['error'] ?? [];

                Log::warning('OpenRouter Vision API failed.', [
                    'provider'   => 'openrouter',
                    'model'      => $this->model,
                    'http_status' => $response->status(),
                    'elapsed_ms' => $elapsed,
                    'error'      => $errMsg,
                    'error_type' => $errorObj['type'] ?? '',
                ]);

                return [
                    'success' => false,
                    'error'   => 'OpenRouter Vision gagal: ' . $errMsg,
                    'source'  => 'error',
                ];
            }

            $body = $response->json();
            $text = $body['choices'][0]['message']['content'] ?? '';

            Log::info('OpenRouter Vision response received.', [
                'provider'   => 'openrouter',
                'model'      => $this->model,
                'http_status' => $response->status(),
                'elapsed_ms' => $elapsed,
                'has_content' => !empty($text),
            ]);

            if (empty($text)) {
                return [
                    'success' => false,
                    'error'   => 'OpenRouter Vision mengembalikan respons kosong.',
                    'source'  => 'error',
                ];
            }

            $parsed = $this->parseAiResponse($text);

            if (!$parsed) {
                Log::warning('OpenRouter Vision: failed to parse response.', ['raw' => mb_substr($text, 0, 500)]);
                return [
                    'success' => false,
                    'error'   => 'Gagal memparse respons OpenRouter Vision.',
                    'source'  => 'error',
                ];
            }

            return [
                'success' => true,
                'data'    => $parsed,
                'source'  => 'ai',
            ];
        } catch (\Exception $e) {
            Log::error('OpenRouter Vision exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error'   => 'Exception OpenRouter Vision: ' . $e->getMessage(),
                'source'  => 'error',
            ];
        }
    }

    /**
     * Test connection to OpenRouter API with a lightweight text-only request.
     * No image sent, no database touched — pure connectivity check.
     *
     * @return array{success: bool, message: string, details?: array}
     */
    public function testConnection(): array
    {
        if (!$this->isAvailable()) {
            return [
                'success' => false,
                'message' => 'OpenRouter belum dikonfigurasi. Pastikan OPENROUTER_API_KEY dan OPENROUTER_MODEL ada di .env.',
            ];
        }

        $startTime = microtime(true);

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ])
                ->post($this->baseUrl, [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'user', 'content' => 'Reply with exactly: {"status":"ok"}'],
                    ],
                    'temperature' => 0,
                    'max_tokens'  => 50,
                ]);

            $elapsed = round((microtime(true) - $startTime) * 1000);

            if ($response->failed()) {
                $body   = $response->json();
                $errMsg = $body['error']['message'] ?? ('HTTP ' . $response->status());

                return [
                    'success' => false,
                    'message' => 'OpenRouter API merespons error: ' . $errMsg,
                    'details' => [
                        'provider'    => 'openrouter',
                        'model'       => $this->model,
                        'http_status' => $response->status(),
                        'elapsed_ms'  => $elapsed,
                    ],
                ];
            }

            $body = $response->json();
            $content = $body['choices'][0]['message']['content'] ?? '';

            return [
                'success'  => true,
                'message'  => 'Koneksi OpenRouter berhasil. Model ' . $this->model . ' merespons.',
                'details'  => [
                    'provider'    => 'openrouter',
                    'model'       => $this->model,
                    'http_status' => $response->status(),
                    'elapsed_ms'  => $elapsed,
                    'response'    => mb_substr($content, 0, 100),
                ],
            ];
        } catch (\Exception $e) {
            $elapsed = round((microtime(true) - $startTime) * 1000);

            return [
                'success' => false,
                'message' => 'Gagal koneksi ke OpenRouter: ' . $e->getMessage(),
                'details' => [
                    'provider'   => 'openrouter',
                    'model'      => $this->model,
                    'elapsed_ms' => $elapsed,
                ],
            ];
        }
    }

    /**
     * Build vision prompt for reading attendance book photos.
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

    /**
     * Parse and normalize AI response JSON into standard format.
     * Supports both data_absensi (new) and siswa (legacy) formats.
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

        // Must have siswa array
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

                $tanggal    = (int) ($entry['tanggal'] ?? 0);
                $status     = strtoupper(trim((string) ($entry['status'] ?? '')));
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
}
