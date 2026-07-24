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
use Throwable;

class AbsensiImportController extends Controller
{
    protected $importService;

    public function __construct(AbsensiImportService $importService)
    {
        $this->importService = $importService;
    }

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

            $monthStart = Carbon::createFromDate($request->tahun, $request->bulan, 1);
            $totalDays = $monthStart->daysInMonth;

            if ($tahunAktif->tanggal_mulai && $monthStart->lt(Carbon::parse($tahunAktif->tanggal_mulai)->startOfMonth())) {
                return back()->withInput()->with('error', 'Bulan yang dipilih sebelum periode tahun ajaran aktif.');
            }

            $monthEnd = $monthStart->copy()->endOfMonth();
            if ($tahunAktif->tanggal_selesai && $monthEnd->gt(Carbon::parse($tahunAktif->tanggal_selesai)->endOfMonth())) {
                return back()->withInput()->with('error', 'Bulan yang dipilih setelah periode tahun ajaran aktif.');
            }

            $siswas = Student::whereHas('kelasAktif', function ($q) use ($request, $tahunAktif) {
                $q->where('kelas_id', $request->kelas_id)
                  ->where('tahun_ajaran_id', $tahunAktif->id);
            })->orderBy('nama')->get();

            if ($siswas->isEmpty()) {
                return back()->withInput()->with('error', 'Tidak ada siswa aktif di kelas ini untuk tahun ajaran aktif.');
            }

            $file = $request->file('foto');
            if (!$file || !$file->isValid()) {
                return back()->withInput()->with('error', 'File foto gagal diunggah. Pastikan ukuran file tidak melebihi ' . ini_get('upload_max_filesize') . ' dan coba lagi.');
            }

            $uploadDir = config('ocr.upload_dir', 'absensi-import');
            $disk = config('ocr.upload_disk', 'local');

            $diskPath = Storage::disk($disk)->path('/');
            $fullUploadDir = rtrim($diskPath, '/') . '/' . $uploadDir;
            if (!is_dir($fullUploadDir)) {
                @mkdir($fullUploadDir, 0755, true);
            }

            $ext = $file->getClientOriginalExtension();
            $filename = 'absensi_' . $request->kelas_id . '_' . $request->bulan . '_' . $request->tahun . '_' . time() . '.' . $ext;
            $filePath = $file->storeAs($uploadDir, $filename, $disk);
            $fullPath = Storage::disk($disk)->path($uploadDir . '/' . $filename);

            $ocrResult = $this->importService->runOcr($fullPath);

            if (!$ocrResult['success']) {
                @unlink($fullPath);
                $errorMsg = $ocrResult['error'] ?? 'Gagal membaca foto.';
                if (isset($ocrResult['fallback']) && $ocrResult['fallback']) {
                    $errorMsg .= ' Struktur tabel tidak dapat dikenali dengan baik. Silakan periksa foto atau gunakan input manual.';
                }
                return back()->withInput()->with('error', $errorMsg);
            }

            $matchedData = $this->importService->matchStudentsWithOcr(
                $ocrResult['students'],
                $siswas,
                $totalDays
            );

            $validation = $this->importService->validateImportData($matchedData, $totalDays, $monthStart);

            $existingDates = $this->importService->getExistingDates(
                $request->kelas_id,
                $tahunAktif->id,
                $monthStart,
                $totalDays
            );

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

            $kelasNama = '';
            $kelasModel = Kelas::find($request->kelas_id);
            if ($kelasModel) {
                $kelasNama = $kelasModel->nama_kelas;
            }

            session([
                'import_data' => [
                    'matched_data' => $matchedData,
                    'kelas_id' => $request->kelas_id,
                    'kelas_nama' => $kelasNama,
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

            return view('admin.absensi.import-verify', [
                'tahunAktif' => $tahunAktif,
                'kelas' => $kelasModel,
                'siswas' => $siswas,
                'matchedData' => $matchedData,
                'totalDays' => $totalDays,
                'daysInfo' => $daysInfo,
                'monthStart' => $monthStart,
                'validation' => $validation,
                'existingDates' => $existingDates,
            ]);
        } catch (Throwable $e) {
            Log::error('Import process error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

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

        try {
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
        } catch (Throwable $e) {
            Log::error('Import confirm error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }

        if (isset($importData['foto_path'])) {
            $fotoFullPath = Storage::disk(config('ocr.upload_disk', 'local'))->path($importData['foto_path']);
            if (file_exists($fotoFullPath)) {
                @unlink($fotoFullPath);
            }
        }

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
