<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\AbsensiDetail;
use App\Models\Kelas;
use App\Models\Student;
use App\Models\StudentKelas;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AbsensiImportService
{
    /**
     * Run the Python OCR script and return parsed JSON result.
     *
     * @return array{success: bool, error?: string, students?: array, dates?: array, total_rows?: int, total_cols?: int, fallback?: bool}
     */
    public function runOcr(string $imagePath): array
    {
        if (!function_exists('exec')) {
            Log::error('exec() is disabled on this server');
            return ['success' => false, 'error' => 'Fungsi eksekusi shell (exec) tidak tersedia di server ini. Import foto membutuhkan Python dan Tesseract OCR yang terinstall di server.'];
        }

        $pythonPath = config('ocr.python_path', 'python');
        $scriptPath = base_path(config('ocr.script_path', 'scripts/ocr_attendance.py'));
        $tesseractPath = config('ocr.tesseract_path', '');

        if (!file_exists($scriptPath)) {
            return ['success' => false, 'error' => 'Script OCR tidak ditemukan: ' . $scriptPath];
        }

        if (!file_exists($imagePath)) {
            return ['success' => false, 'error' => 'File gambar tidak ditemukan: ' . $imagePath];
        }

        $escapedImage = escapeshellarg($imagePath);
        $escapedTesseract = $tesseractPath ? ' ' . escapeshellarg($tesseractPath) : '';

        $command = sprintf(
            '%s %s %s%s 2>&1',
            escapeshellarg($pythonPath),
            escapeshellarg($scriptPath),
            $escapedImage,
            $escapedTesseract
        );

        Log::info('OCR command: ' . $command);

        $output = [];
        $exitCode = 0;
        exec($command, $output, $exitCode);

        $outputStr = implode("\n", $output);

        if ($exitCode !== 0) {
            Log::error('OCR script failed', ['exit_code' => $exitCode, 'output' => $outputStr]);
            return ['success' => false, 'error' => 'OCR script gagal dijalankan (exit code: ' . $exitCode . '). Pastikan Python dan Tesseract terinstall.'];
        }

        $decoded = json_decode($outputStr, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('OCR output not valid JSON', ['output' => $outputStr]);
            return ['success' => false, 'error' => 'Output OCR tidak valid.'];
        }

        return $decoded;
    }

    /**
     * Match OCR results with actual students in the class.
     *
     * OCR only returns row_index + statuses. We need to map rows to actual students.
     * For now, rows are returned in order — the caller must provide the student list
     * ordered the same way as the attendance book.
     *
     * @param  array  $ocrStudents  From Python: [{row_index, statuses: {"1":"H",...}}, ...]
     * @param  \Illuminate\Database\Eloquent\Collection  $dbStudents  Students in the class
     * @param  int    $totalDays  Days in the month (28-31)
     * @return array  Merged data: [{student_id, nama, nisn, statuses: {"1":"H",...}}, ...]
     */
    public function matchStudentsWithOcr(array $ocrStudents, $dbStudents, int $totalDays): array
    {
        $result = [];
        $dbList = $dbStudents->values();

        foreach ($ocrStudents as $idx => $ocrRow) {
            $rowIndex = $ocrRow['row_index'] ?? $idx;

            $student = $dbList->get($rowIndex);

            $statuses = [];
            for ($day = 1; $day <= $totalDays; $day++) {
                $dayStr = (string) $day;
                $statuses[$dayStr] = $ocrRow['statuses'][$dayStr] ?? '?';
            }

            $result[] = [
                'student_id' => $student ? $student->id : null,
                'nama' => $student ? $student->nama : 'TIDAK DIKENAL (baris ' . ($rowIndex + 1) . ')',
                'nisn' => $student ? $student->nisn : '-',
                'row_index' => $rowIndex,
                'statuses' => $statuses,
            ];
        }

        // If OCR found fewer rows than students in DB, add missing students with all '?'
        if (count($ocrStudents) < $dbList->count()) {
            for ($i = count($ocrStudents); $i < $dbList->count(); $i++) {
                $student = $dbList->get($i);
                $statuses = [];
                for ($day = 1; $day <= $totalDays; $day++) {
                    $statuses[(string) $day] = '?';
                }
                $result[] = [
                    'student_id' => $student->id,
                    'nama' => $student->nama,
                    'nisn' => $student->nisn,
                    'row_index' => $i,
                    'statuses' => $statuses,
                ];
            }
        }

        return $result;
    }

    /**
     * Validate OCR results before import.
     *
     * @param  array  $matchedData  From matchStudentsWithOcr()
     * @param  int    $totalDays
     * @param  Carbon $monthStart
     * @return array{valid: bool, errors: string[], stats: array}
     */
    public function validateImportData(array $matchedData, int $totalDays, Carbon $monthStart): array
    {
        $errors = [];
        $stats = [
            'H' => 0,
            'I' => 0,
            'S' => 0,
            'A' => 0,
            '?' => 0,
            'libur_jumat' => 0,
            'belum_terjadi' => 0,
        ];

        $today = Carbon::today();

        for ($day = 1; $day <= $totalDays; $day++) {
            $date = $monthStart->copy()->day($day);
            if ($date->month !== $monthStart->month) {
                break;
            }

            if ($date->isFriday()) {
                $stats['libur_jumat']++;
            }

            if ($date->gt($today)) {
                $stats['belum_terjadi']++;
            }
        }

        foreach ($matchedData as $row) {
            if (!$row['student_id']) {
                $errors[] = "Siswa tidak ditemukan untuk baris " . ($row['row_index'] + 1) . ": " . $row['nama'];
            }

            for ($day = 1; $day <= $totalDays; $day++) {
                $dayStr = (string) $day;
                $status = $row['statuses'][$dayStr] ?? '?';
                $date = $monthStart->copy()->day($day);

                if ($date->month !== $monthStart->month) {
                    continue;
                }

                // Skip Jumat and future dates in stats (but don't count their status)
                if ($date->isFriday() || $date->gt($today)) {
                    continue;
                }

                if (isset($stats[$status])) {
                    $stats[$status]++;
                } else {
                    $stats['?']++;
                }
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'stats' => $stats,
        ];
    }

    /**
     * Check for existing attendance records for the given period.
     *
     * @param  int     $kelasId
     * @param  int     $tahunAjaranId
     * @param  Carbon  $monthStart
     * @param  int     $totalDays
     * @return array   List of dates that already have attendance: ['2026-07-01', '2026-07-02', ...]
     */
    public function getExistingDates(int $kelasId, int $tahunAjaranId, Carbon $monthStart, int $totalDays): array
    {
        $endDate = $monthStart->copy()->endOfMonth();

        $existingAbsensi = Absensi::where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->whereBetween('tanggal', [$monthStart->toDateString(), $endDate->toDateString()])
            ->pluck('tanggal')
            ->map(function ($t) {
                return Carbon::parse($t)->format('Y-m-d');
            })
            ->toArray();

        return $existingAbsensi;
    }

    /**
     * Import attendance data to database.
     *
     * @param  array   $matchedData   From matchStudentsWithOcr()
     * @param  int     $kelasId
     * @param  int     $tahunAjaranId
     * @param  int     $userId        Admin who performs the import
     * @param  Carbon  $monthStart
     * @param  int     $totalDays
     * @param  array   $existingDates Dates that already have attendance (for skip/update logic)
     * @param  string  $duplicateMode 'skip' or 'update'
     * @return array{success: bool, imported: int, skipped: int, updated: int, errors: string[]}
     */
    public function importAttendance(
        array $matchedData,
        int $kelasId,
        int $tahunAjaranId,
        int $userId,
        Carbon $monthStart,
        int $totalDays,
        array $existingDates,
        string $duplicateMode = 'skip'
    ): array {
        $imported = 0;
        $skipped = 0;
        $updated = 0;
        $errors = [];
        $today = Carbon::today();

        DB::beginTransaction();

        try {
            for ($day = 1; $day <= $totalDays; $day++) {
                $date = $monthStart->copy()->day($day);

                // Skip if not same month (overflow from day > daysInMonth)
                if ($date->month !== $monthStart->month) {
                    continue;
                }

                // Skip Jumat
                if ($date->isFriday()) {
                    continue;
                }

                // Skip future dates
                if ($date->gt($today)) {
                    continue;
                }

                $dateStr = $date->toDateString();
                $isExisting = in_array($dateStr, $existingDates);

                if ($isExisting && $duplicateMode === 'skip') {
                    $skipped++;
                    continue;
                }

                $absensi = Absensi::updateOrCreate(
                    [
                        'kelas_id' => $kelasId,
                        'tanggal' => $dateStr,
                        'tahun_ajaran_id' => $tahunAjaranId,
                    ],
                    [
                        'user_id' => $userId,
                    ]
                );

                foreach ($matchedData as $row) {
                    $status = $row['statuses'][(string) $day] ?? '?';

                    // Never save '?' to database
                    if ($status === '?') {
                        continue;
                    }

                    AbsensiDetail::updateOrCreate(
                        [
                            'absensi_id' => $absensi->id,
                            'student_id' => $row['student_id'],
                        ],
                        [
                            'status' => $status,
                            'keterangan' => 'Import dari foto',
                        ]
                    );
                }

                if ($isExisting) {
                    $updated++;
                } else {
                    $imported++;
                }
            }

            DB::commit();

            return [
                'success' => true,
                'imported' => $imported,
                'skipped' => $skipped,
                'updated' => $updated,
                'errors' => $errors,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Attendance import failed', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'imported' => 0,
                'skipped' => 0,
                'updated' => 0,
                'errors' => ['Gagal menyimpan absensi: ' . $e->getMessage()],
            ];
        }
    }

    /**
     * Cleanup temporary uploaded file.
     */
    public function cleanupFile(string $filePath): void
    {
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }

    /**
     * Get the day-of-week name in Indonesian.
     */
    public function getDayName(Carbon $date): string
    {
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        return $days[$date->dayOfWeek] ?? '';
    }
}
