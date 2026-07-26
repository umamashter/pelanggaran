<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Student;
use App\Models\TahunAjaran;
use App\Services\AbsensiImportService;
use App\Services\AIParserService;
use App\Services\OpenRouterVisionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class AbsensiImportController extends Controller
{
    protected $importService;
    protected $aiParser;

    public function __construct(AbsensiImportService $importService, AIParserService $aiParser)
    {
        $this->importService = $importService;
        $this->aiParser      = $aiParser;
    }

    /**
     * Test OpenRouter connection — lightweight, no DB, no image.
     */
    public function testOpenRouter(): JsonResponse
    {
        $service = app(OpenRouterVisionService::class);
        $result  = $service->testConnection();

        Log::info('OpenRouter connection test.', [
            'success' => $result['success'],
            'details' => $result['details'] ?? [],
        ]);

        return response()->json($result, $result['success'] ? 200 : 503);
    }

    public function showForm()
    {
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();

        $kelasList = Kelas::whereHas('siswaAktif', function ($q) use ($tahunAktif) {
            $q->where('tahun_ajaran_id', $tahunAktif->id);
        })->orderBy('nama_kelas')->get();

        $studentCounts = [];
        foreach ($kelasList as $kelas) {
            $studentCounts[$kelas->id] = Student::whereHas('kelasAktif', function ($q) use ($kelas, $tahunAktif) {
                $q->where('kelas_id', $kelas->id)
                  ->where('tahun_ajaran_id', $tahunAktif->id);
            })->count();
        }

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $currentMonth = (int) now()->format('m');
        $currentYear  = (int) now()->format('Y');

        return view('admin.absensi.import-form', compact(
            'tahunAktif', 'kelasList', 'studentCounts',
            'months', 'currentMonth', 'currentYear'
        ));
    }

    /**
     * Receive attendance photo and parse via Gemini Vision API.
     * Also supports legacy text-based OCR (manual mode).
     */
    public function parseOcrText(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'kelas_id'   => 'required|exists:kelas,id',
                'bulan'      => 'required|integer|min:1|max:12',
                'tahun'      => 'required|integer|min:2020|max:2050',
                'foto'       => 'required|file|mimes:jpg,jpeg,png,webp|max:10240',
                'ocr_text'   => 'nullable|string|min:0|max:50000',
                'parse_mode' => 'nullable|in:ai,manual,local',
            ]);

            $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();
            $bulan = (int) $request->bulan;
            $tahun = (int) $request->tahun;

            $monthStart = Carbon::createFromDate($tahun, $bulan, 1);
            $totalDays  = $monthStart->daysInMonth;

            if ($tahunAktif->tanggal_mulai && $monthStart->lt(Carbon::parse($tahunAktif->tanggal_mulai)->startOfMonth())) {
                return response()->json(['error' => 'Bulan yang dipilih sebelum periode tahun ajaran aktif.'], 422);
            }

            $monthEnd = $monthStart->copy()->endOfMonth();
            if ($tahunAktif->tanggal_selesai && $monthEnd->gt(Carbon::parse($tahunAktif->tanggal_selesai)->endOfMonth())) {
                return response()->json(['error' => 'Bulan yang dipilih setelah periode tahun ajaran aktif.'], 422);
            }

            $siswas = Student::whereHas('kelasAktif', function ($q) use ($request, $tahunAktif) {
                $q->where('kelas_id', $request->kelas_id)
                  ->where('tahun_ajaran_id', $tahunAktif->id);
            })->orderBy('nama')->get();

            if ($siswas->isEmpty()) {
                return response()->json(['error' => 'Tidak ada siswa aktif di kelas ini.'], 422);
            }

            $parseMode = $request->input('parse_mode', 'ai');

            // Save uploaded photo temporarily for Vision API
            $file = $request->file('foto');
            if (!$file || !$file->isValid()) {
                return response()->json(['error' => 'File foto tidak valid.'], 422);
            }
            $uploadDir = config('ocr.upload_dir', 'absensi-import');
            $disk      = config('ocr.upload_disk', 'local');
            $ext       = $file->getClientOriginalExtension();
            $tmpName   = 'vision_' . $request->kelas_id . '_' . $bulan . '_' . $tahun . '_' . time() . '.' . $ext;
            $tmpPath   = $file->storeAs($uploadDir, $tmpName, $disk);

            // Parse: Manual / AI with fallback chain
            $parseMode = $request->input('parse_mode', 'ai');

            if ($parseMode === 'manual') {
                $ocrText = $request->input('ocr_text', '');
                if (strlen($ocrText) < 5) {
                    return response()->json(['error' => 'Teks OCR terlalu pendek untuk mode manual.'], 422);
                }
                $aiResult = $this->aiParser->fallbackParse($ocrText, $bulan, $tahun);
            } elseif ($parseMode === 'local') {
                $aiResult = $this->aiParser->parseFromLocalOcr($tmpPath);
            } else {
                // AI mode: OpenRouter Vision → Gemini Vision → Local Tesseract → PHP regex
                $aiResult = $this->aiParser->parseFromImage($tmpPath, $bulan, $tahun);

                if (!$aiResult['success']) {
                    Log::info('Gemini Vision failed, falling back to local OCR.', ['error' => $aiResult['error'] ?? '']);
                    $aiResult = $this->aiParser->parseFromLocalOcr($tmpPath);

                    if (!$aiResult['success']) {
                        Log::info('Local OCR failed, falling back to PHP regex parser.', ['error' => $aiResult['error'] ?? '']);
                        $ocrText = $request->input('ocr_text', '');
                        $aiResult = $this->aiParser->fallbackParse($ocrText ?: '', $bulan, $tahun);
                        $aiResult['warning'] = 'AI Vision dan OCR lokal gagal. Menggunakan parser teks biasa.';
                    }
                }
            }

            if (!$aiResult['success']) {
                // Cleanup temp file on failure
                $this->importService->cleanupFile(storage_path('app/' . $tmpPath));
                return response()->json([
                    'error' => 'Gagal memproses foto: ' . ($aiResult['error'] ?? 'Kesalahan tidak diketahui.'),
                ], 422);
            }

            $aiSiswaList = $aiResult['data']['siswa'] ?? [];
            $parseSource = $aiResult['source'] ?? 'ai';

            $actualDates = range(1, $totalDays);

            $matchedData = $this->importService->matchStudentsWithAi(
                $aiSiswaList, $siswas, $totalDays, $bulan, $tahun, $actualDates
            );

            $validation = $this->importService->validateImportData($matchedData, $totalDays, $monthStart, $actualDates);

            $existingDates = $this->importService->getExistingDates(
                $request->kelas_id, $tahunAktif->id, $monthStart, $totalDays
            );

            $daysInfo = [];
            for ($day = 1; $day <= $totalDays; $day++) {
                $date      = $monthStart->copy()->day($day);
                $isFriday  = $date->isFriday();
                $isFuture  = $date->gt(Carbon::today());
                $isExisting = in_array($date->toDateString(), $existingDates);

                $daysInfo[$day] = [
                    'date'        => $date->format('Y-m-d'),
                    'day_name'    => $this->importService->getDayName($date),
                    'is_friday'   => $isFriday,
                    'is_future'   => $isFuture,
                    'is_existing' => $isExisting,
                    'label'       => $isFriday ? 'LIBUR' : ($isFuture ? 'Belum Terjadi' : $date->format('d')),
                ];
            }

            $kelasModel = Kelas::find($request->kelas_id);

            // Final foto path for verify page
            $fotoPath = $tmpPath;

            $aiJson = $aiResult['data'] ?? [];

            session([
                'import_data' => [
                    'matched_data'    => $matchedData,
                    'kelas_id'        => $request->kelas_id,
                    'kelas_nama'      => $kelasModel ? $kelasModel->nama_kelas : '',
                    'tahun_ajaran_id' => $tahunAktif->id,
                    'bulan'           => $bulan,
                    'tahun'           => $tahun,
                    'total_days'      => $totalDays,
                    'days_info'       => $daysInfo,
                    'existing_dates'  => $existingDates,
                    'foto_path'       => $fotoPath,
                    'stats'           => $validation['stats'],
                    'actual_dates'    => $actualDates,
                    'parse_source'    => $parseSource,
                    'ocr_raw_text'    => '',
                    'ai_json'         => $aiJson,
                    'ai_warning'      => $aiResult['warning'] ?? null,
                    'ai_error'        => $aiResult['ai_error'] ?? null,
                ],
            ]);

            return response()->json([
                'redirect' => route('absensi.import.verify'),
            ]);

        } catch (Throwable $e) {
            Log::error('Parse OCR text error', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show verification page with source-aware attendance data.
     */
    public function showVerify()
    {
        $importData = session('import_data');

        if (!$importData) {
            return redirect()->route('absensi.import')
                ->with('error', 'Sesi import telah berakhir. Silakan mulai ulang.');
        }

        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();
        $kelasModel = Kelas::find($importData['kelas_id']);

        $siswas = Student::whereHas('kelasAktif', function ($q) use ($importData, $tahunAktif) {
            $q->where('kelas_id', $importData['kelas_id'])
              ->where('tahun_ajaran_id', $tahunAktif->id);
        })->orderBy('nama')->get();

        $monthStart   = Carbon::createFromDate($importData['tahun'], $importData['bulan'], 1);
        $actualDates  = $importData['actual_dates'] ?? range(1, $importData['total_days']);

        $validation = $this->importService->validateImportData(
            $importData['matched_data'],
            $importData['total_days'],
            $monthStart,
            $actualDates
        );

        // Compute review items: students with warnings
        $reviewItems = [];
        foreach ($importData['matched_data'] as $row) {
            if (!empty($row['warnings'])) {
                $reviewItems[] = $row;
            }
        }

        // Compute matched/unmatched lists
        $matchedList = [];
        $unmatchedList = [];
        foreach ($importData['matched_data'] as $row) {
            if ($row['student_id']) {
                $matchedList[] = $row;
            } else {
                $unmatchedList[] = $row;
            }
        }

        // Compute I/S/A found by AI
        $aiFoundStatuses = [];
        foreach ($importData['matched_data'] as $row) {
            foreach ($row['sources'] as $day => $source) {
                if ($source === 'AI') {
                    $aiFoundStatuses[] = [
                        'nama'   => $row['nama'],
                        'nisn'   => $row['nisn'],
                        'day'    => $day,
                        'status' => $row['statuses'][$day] ?? '',
                    ];
                }
            }
        }

        return view('admin.absensi.import-verify', [
            'tahunAktif'    => $tahunAktif,
            'kelas'         => $kelasModel,
            'siswas'        => $siswas,
            'matchedData'   => $importData['matched_data'],
            'totalDays'     => $importData['total_days'],
            'daysInfo'      => $importData['days_info'],
            'actualDates'   => $actualDates,
            'monthStart'    => $monthStart,
            'validation'    => $validation,
            'existingDates' => $importData['existing_dates'],
            'fotoPath'      => $importData['foto_path'] ?? null,
            'parseSource'   => $importData['parse_source'] ?? 'unknown',
            'ocrRawText'    => $importData['ocr_raw_text'] ?? '',
            'aiJson'        => $importData['ai_json'] ?? [],
            'aiWarning'     => $importData['ai_warning'] ?? null,
            'aiError'       => $importData['ai_error'] ?? null,
            'reviewItems'   => $reviewItems,
            'matchedList'   => $matchedList,
            'unmatchedList' => $unmatchedList,
            'aiFoundStatuses' => $aiFoundStatuses,
        ]);
    }

    public function confirmImport(Request $request)
    {
        $importData = session('import_data');

        if (!$importData) {
            return redirect()->route('absensi.import')
                ->with('error', 'Sesi import telah berakhir. Silakan mulai ulang.');
        }

        $request->validate([
            'statuses'       => 'required|array',
            'sources'        => 'required|array',
            'duplicate_mode' => 'required|in:skip,update',
        ]);

        $editedData  = $importData['matched_data'];
        $actualDates = $importData['actual_dates'] ?? range(1, $importData['total_days']);

        foreach ($request->statuses as $studentIdx => $dayStatuses) {
            $idx = (int) $studentIdx;
            if (!isset($editedData[$idx])) continue;

            foreach ($dayStatuses as $day => $status) {
                $dayKey = (string) $day;
                if (!in_array($status, ['H', 'I', 'S', 'A'], true)) continue;

                $prevStatus = $editedData[$idx]['statuses'][$dayKey] ?? null;
                $prevSource = $editedData[$idx]['sources'][$dayKey] ?? 'DEFAULT';

                $editedData[$idx]['statuses'][$dayKey] = $status;

                if ($prevStatus !== $status) {
                    $editedData[$idx]['sources'][$dayKey] = 'MANUAL';
                } elseif (isset($request->sources[$studentIdx][$day])) {
                    $editedData[$idx]['sources'][$dayKey] = $request->sources[$studentIdx][$day];
                }
            }
        }

        $monthStart = Carbon::createFromDate($importData['tahun'], $importData['bulan'], 1);

        try {
            $result = $this->importService->importAttendance(
                $editedData,
                $importData['kelas_id'],
                $importData['tahun_ajaran_id'],
                Auth::id(),
                $monthStart,
                $importData['total_days'],
                $importData['existing_dates'],
                $request->duplicate_mode,
                $actualDates
            );
        } catch (Throwable $e) {
            Log::error('Import confirm error', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }

        if (isset($importData['foto_path'])) {
            $disk      = config('ocr.upload_disk', 'local');
            $fotoFullPath = \Illuminate\Support\Facades\Storage::disk($disk)->path($importData['foto_path']);
            if (file_exists($fotoFullPath)) {
                @unlink($fotoFullPath);
            }
        }

        session()->forget('import_data');

        if ($result['success']) {
            $msg = 'Import absensi berhasil.';
            if ($result['imported'] > 0) $msg .= ' ' . $result['imported'] . ' tanggal baru.';
            if ($result['updated'] > 0) $msg .= ' ' . $result['updated'] . ' tanggal diperbarui.';
            if ($result['skipped'] > 0) $msg .= ' ' . $result['skipped'] . ' tanggal dilewati.';

            return redirect()->route('absensi.index')->with('success', $msg);
        }

        return redirect()->route('absensi.import')
            ->with('error', implode(' ', $result['errors']));
    }
}
