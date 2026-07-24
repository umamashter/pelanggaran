<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Student;
use App\Models\TahunAjaran;
use App\Services\AbsensiImportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AbsensiImportController extends Controller
{
    protected AbsensiImportService $importService;

    public function __construct(AbsensiImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * Show the upload form.
     */
    public function showForm()
    {
        $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();

        $kelasList = Kelas::whereHas('siswaAktif', function ($q) use ($tahunAktif) {
            $q->where('tahun_ajaran_id', $tahunAktif->id);
        })->orderBy('nama_kelas')->get();

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $currentMonth = (int) now()->format('m');
        $currentYear = (int) now()->format('Y');

        return view('admin.absensi.import-form', compact(
            'tahunAktif',
            'kelasList',
            'months',
            'currentMonth',
            'currentYear'
        ));
    }

    /**
     * Process the uploaded image via OCR.
     */
    public function processImage(Request $request)
    {
        try {
            $request->validate([
                'foto' => 'required|file|mimes:jpg,jpeg,png,webp|max:' . (config('ocr.max_file_size', 10) * 1024),
                'kelas_id' => 'required|exists:kelas,id',
                'bulan' => 'required|integer|min:1|max:12',
                'tahun' => 'required|integer|min:2020|max:2050',
            ]);

            $tahunAktif = TahunAjaran::where('status', 'Aktif')->firstOrFail();

            // Validate date within tahun ajaran period
            $monthStart = Carbon::createFromDate($request->tahun, $request->bulan, 1);
            $totalDays = $monthStart->daysInMonth;

            if ($tahunAktif->tanggal_mulai && $monthStart->lt(Carbon::parse($tahunAktif->tanggal_mulai)->startOfMonth())) {
                return back()->withInput()->with('error', 'Bulan yang dipilih sebelum periode tahun ajaran aktif.');
            }

            $monthEnd = $monthStart->copy()->endOfMonth();
            if ($tahunAktif->tanggal_selesai && $monthEnd->gt(Carbon::parse($tahunAktif->tanggal_selesai)->endOfMonth())) {
                return back()->withInput()->with('error', 'Bulan yang dipilih setelah periode tahun ajaran aktif.');
            }

            // Get students in this class for active tahun ajaran
            $siswas = Student::whereHas('kelasAktif', function ($q) use ($request, $tahunAktif) {
                $q->where('kelas_id', $request->kelas_id)
                  ->where('tahun_ajaran_id', $tahunAktif->id);
            })->orderBy('nama')->get();

            if ($siswas->isEmpty()) {
                return back()->withInput()->with('error', 'Tidak ada siswa aktif di kelas ini untuk tahun ajaran aktif.');
            }

            // Ensure upload directory exists
            $uploadDir = config('ocr.upload_dir', 'absensi-import');
            $disk = config('ocr.upload_disk', 'local');
            $diskPath = Storage::disk($disk)->path('/');
            $fullUploadDir = rtrim($diskPath, '/') . '/' . $uploadDir;
            if (!is_dir($fullUploadDir)) {
                mkdir($fullUploadDir, 0755, true);
            }

            // Save uploaded file temporarily
            $file = $request->file('foto');
            $filename = 'absensi_' . $request->kelas_id . '_' . $request->bulan . '_' . $request->tahun . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs($uploadDir, $filename, $disk);
            $fullPath = Storage::disk($disk)->path($uploadDir . '/' . $filename);

            // Run OCR
            $ocrResult = $this->importService->runOcr($fullPath);

            if (!$ocrResult['success']) {
                $this->importService->cleanupFile($fullPath);
                $errorMsg = $ocrResult['error'] ?? 'Gagal membaca foto.';
                if (isset($ocrResult['fallback']) && $ocrResult['fallback']) {
                    $errorMsg .= ' Struktur tabel tidak dapat dikenali dengan baik. Silakan periksa foto atau gunakan input manual.';
                }
                return back()->withInput()->with('error', $errorMsg);
            }

            // Match OCR results with DB students
            $matchedData = $this->importService->matchStudentsWithOcr(
                $ocrResult['students'],
                $siswas,
                $totalDays
            );

            // Validate
            $validation = $this->importService->validateImportData($matchedData, $totalDays, $monthStart);

            // Check existing attendance dates
            $existingDates = $this->importService->getExistingDates(
                $request->kelas_id,
                $tahunAktif->id,
                $monthStart,
                $totalDays
            );

            // Build day info for view
            $daysInfo = [];
            for ($day = 1; $day <= $totalDays; $day++) {
                $date = $monthStart->copy()->day($day);
                $isFriday = $date->isFriday();
                $isFuture = $date->gt(Carbon::today());
                $isExisting = in_array($date->toDateString(), $existingDates);

                $daysInfo[$day] = [
                    'date' => $date->format('Y-m-d'),
                    'day_name' => $this->importService->getDayName($date),
                    'is_friday' => $isFriday,
                    'is_future' => $isFuture,
                    'is_existing' => $isExisting,
                    'label' => $isFriday ? 'LIBUR' : ($isFuture ? 'Belum Terjadi' : $date->format('d')),
                ];
            }

            // Store in session for confirm step
            session([
                'import_data' => [
                    'matched_data' => $matchedData,
                    'kelas_id' => $request->kelas_id,
                    'kelas_nama' => Kelas::find($request->kelas_id)->nama_kelas,
                    'tahun_ajaran_id' => $tahunAktif->id,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'total_days' => $totalDays,
                    'days_info' => $daysInfo,
                    'existing_dates' => $existingDates,
                    'foto_path' => $filePath,
                    'stats' => $validation['stats'],
                    'ocr_total_rows' => $ocrResult['total_rows'],
                    'ocr_total_cols' => $ocrResult['total_cols'],
                ],
            ]);

            $kelas = Kelas::find($request->kelas_id);

            return view('admin.absensi.import-verify', compact(
                'tahunAktif',
                'kelas',
                'siswas',
                'matchedData',
                'totalDays',
                'daysInfo',
                'monthStart',
                'validation',
                'existingDates'
            ));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Import process error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memproses import: ' . $e->getMessage());
        }
    }

    /**
     * Confirm and save the import data.
     */
    public function confirmImport(Request $request)
    {
        $importData = session('import_data');

        if (!$importData) {
            return redirect()->route('absensi.import')
                ->with('error', 'Sesi import telah berakhir. Silakan mulai ulang.');
        }

        $request->validate([
            'statuses' => 'required|array',
            'duplicate_mode' => 'required|in:skip,update',
        ]);

        // Update matched data with operator's edits
        $editedData = $importData['matched_data'];

        foreach ($request->statuses as $studentIdx => $dayStatuses) {
            $idx = (int) $studentIdx;
            if (isset($editedData[$idx])) {
                foreach ($dayStatuses as $day => $status) {
                    if (in_array($status, ['H', 'I', 'S', 'A'])) {
                        $editedData[$idx]['statuses'][(string) $day] = $status;
                    }
                }
            }
        }

        // Final validation: no '?' allowed in data to be saved
        $monthStart = Carbon::createFromDate($importData['tahun'], $importData['bulan'], 1);
        $today = Carbon::today();
        $remainingUnknown = 0;

        for ($day = 1; $day <= $importData['total_days']; $day++) {
            $date = $monthStart->copy()->day($day);
            if ($date->month !== $monthStart->month || $date->isFriday() || $date->gt($today)) {
                continue;
            }

            foreach ($editedData as $row) {
                $status = $row['statuses'][(string) $day] ?? '?';
                if ($status === '?') {
                    $remainingUnknown++;
                }
            }
        }

        if ($remainingUnknown > 0) {
            return back()->with('error', 'Masih ada ' . $remainingUnknown . ' data yang belum ditentukan statusnya (?). Semua data harus berstatus H, I, S, atau A sebelum disimpan.');
        }

        // Perform import
        $result = $this->importService->importAttendance(
            $editedData,
            $importData['kelas_id'],
            $importData['tahun_ajaran_id'],
            Auth::id(),
            $monthStart,
            $importData['total_days'],
            $importData['existing_dates'],
            $request->duplicate_mode
        );

        // Cleanup uploaded file
        if (isset($importData['foto_path'])) {
            $this->importService->cleanupFile(
                Storage::disk(config('ocr.upload_disk', 'local'))->path($importData['foto_path'])
            );
        }

        // Clear session
        session()->forget('import_data');

        if ($result['success']) {
            $msg = 'Import absensi berhasil.';
            if ($result['imported'] > 0) {
                $msg .= ' ' . $result['imported'] . ' tanggal baru.';
            }
            if ($result['updated'] > 0) {
                $msg .= ' ' . $result['updated'] . ' tanggal diperbarui.';
            }
            if ($result['skipped'] > 0) {
                $msg .= ' ' . $result['skipped'] . ' tanggal dilewati (sudah ada).';
            }

            return redirect()->route('absensi.index')->with('success', $msg);
        }

        return redirect()->route('absensi.import')
            ->with('error', implode(' ', $result['errors']));
    }
}
