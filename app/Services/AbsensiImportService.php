<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\AbsensiDetail;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AbsensiImportService
{
    /**
     * Match AI-parsed siswa data with actual DB students.
     *
     * New AI format: ketidakhadiran is array of {tanggal, status, confidence}.
     * warnings per siswa indicate uncertain dates needing operator review.
     *
     * Matching priority:
     *  1. NISN exact match
     *  2. Name exact match (normalized)
     *  3. Name contains match (normalized)
     *  4. Fuzzy matching (Levenshtein ≤5)
     *  5. No match → REVIEW
     *
     * Returns matched data with source tracking per cell.
     */
    public function matchStudentsWithAi(
        array $aiSiswaList,
        $dbStudents,
        int $totalDays,
        int $bulan,
        int $tahun,
        array $actualDates = []
    ): array {
        if (empty($actualDates)) {
            $actualDates = range(1, $totalDays);
        }

        $dbList = $dbStudents->values();
        $fridays = $this->calculateFridays($bulan, $tahun);

        $dbByNisn = [];
        $dbByNameNorm = [];
        foreach ($dbList as $s) {
            if ($s->nisn) {
                $dbByNisn[trim($s->nisn)] = $s;
            }
            $dbByNameNorm[$this->normalizeName($s->nama)] = $s;
        }

        $result = [];
        $matchedIds = [];

        foreach ($aiSiswaList as $aiRow) {
            $no      = $aiRow['no'] ?? 0;
            $nisn    = trim($aiRow['nisn'] ?? '');
            $namaOcr = trim($aiRow['nama_ocr'] ?? '');

            // New format: ketidakhadiran is array of {tanggal, status, confidence}
            $ketidakhadiran = $aiRow['ketidakhadiran'] ?? [];
            $aiWarnings     = $aiRow['warnings'] ?? [];

            // Build lookup: day -> status from AI ketidakhadiran array
            $aiByDay = [];
            foreach ($ketidakhadiran as $entry) {
                if (is_array($entry)) {
                    $t = (int) ($entry['tanggal'] ?? 0);
                    $s = strtoupper((string) ($entry['status'] ?? ''));
                    if ($t >= 1 && $t <= 31 && in_array($s, ['H', 'I', 'S', 'A', 'LIBUR', 'UNKNOWN'], true)) {
                        $aiByDay[$t] = $s;
                    }
                }
            }

            // Student matching
            $student = null;
            $matchType = 'none';

            if ($nisn !== '' && isset($dbByNisn[$nisn])) {
                $student = $dbByNisn[$nisn];
                $matchType = 'NISN';
            }

            if (!$student && $namaOcr !== '') {
                $normOcr = $this->normalizeName($namaOcr);
                if (isset($dbByNameNorm[$normOcr])) {
                    $student = $dbByNameNorm[$normOcr];
                    $matchType = 'NAMA_EXACT';
                }
            }

            if (!$student && $namaOcr !== '') {
                $normOcr = $this->normalizeName($namaOcr);
                foreach ($dbByNameNorm as $dbNorm => $dbS) {
                    if (str_contains($dbNorm, $normOcr) || str_contains($normOcr, $dbNorm)) {
                        $student = $dbS;
                        $matchType = 'NAMA_CONTAINS';
                        break;
                    }
                }
            }

            if (!$student && $namaOcr !== '') {
                $normOcr = $this->normalizeName($namaOcr);
                $bestDist = PHP_INT_MAX;
                $bestMatch = null;
                foreach ($dbByNameNorm as $dbNorm => $dbS) {
                    $dist = levenshtein($normOcr, $dbNorm);
                    if ($dist < $bestDist && $dist <= 5) {
                        $bestDist = $dist;
                        $bestMatch = $dbS;
                    }
                }
                if ($bestMatch) {
                    $student = $bestMatch;
                    $matchType = 'FUZZY';
                }
            }

            $studentId = $student ? $student->id : null;
            $namaDb    = $student ? $student->nama : '';
            $nisnDb    = $student ? $nisn : '';

            if ($studentId) {
                $matchedIds[] = $studentId;
            }

            // Build statuses per day
            $statuses = [];
            $sources  = [];
            $warnings = [];
            $review_reasons = [];

            // Collect AI warnings about uncertain dates into per-day warnings
            $uncertainStatuses = [];
            foreach ($aiWarnings as $w) {
                if (isset($w['status']) && isset($w['reason'])) {
                    $uncertainStatuses[] = $w;
                }
            }

            foreach ($actualDates as $day) {
                $dayStr = (string) $day;
                $date = Carbon::createFromDate($tahun, $bulan, $day);
                $isFriday = in_array($day, $fridays, true);

                if ($isFriday) {
                    $statuses[$dayStr] = 'LIBUR';
                    $sources[$dayStr]  = 'SYSTEM';
                    continue;
                }

                if ($date->gt(Carbon::today())) {
                    $statuses[$dayStr] = 'LIBUR';
                    $sources[$dayStr]  = 'SYSTEM';
                    continue;
                }

                // AI found a status for this day
                $aiStatus = $aiByDay[$day] ?? null;
                if ($aiStatus && in_array($aiStatus, ['H', 'I', 'S', 'A'], true)) {
                    $statuses[$dayStr] = $aiStatus;
                    $sources[$dayStr]  = 'AI';
                } elseif ($aiStatus === 'UNKNOWN') {
                    $statuses[$dayStr] = 'UNKNOWN';
                    $sources[$dayStr]  = 'REVIEW';
                    $review_reasons[$dayStr] = 'Status pada foto tidak terbaca dengan yakin.';
                } else {
                    $statuses[$dayStr] = 'UNKNOWN';
                    $sources[$dayStr]  = 'REVIEW';
                    $review_reasons[$dayStr] = 'Tidak ada status yang berhasil dibaca dari foto.';
                }
            }

            // Warnings
            if (!$student) {
                $warnings[] = 'Siswa tidak ditemukan di database.';
            }
            if ($matchType === 'FUZZY') {
                $warnings[] = 'Pencocokan nama menggunakan fuzzy matching. Pastikan benar.';
            }
            if ($matchType === 'NAMA_CONTAINS') {
                $warnings[] = 'Pencocokan nama parsial. Pastikan benar.';
            }
            if (!empty($uncertainStatuses)) {
                foreach ($uncertainStatuses as $us) {
                    $warnings[] = 'Status ' . $us['status'] . ' ditemukan tetapi tanggal tidak pasti: ' . $us['reason'];
                }
            }

            $result[] = [
                'student_id'      => $studentId,
                'nama'            => $student ? $student->nama : ($namaOcr ?: 'Baris ' . $no . ' tidak dikenal'),
                'nisn'            => $nisnDb ?: ($nisn ?: '-'),
                'nama_ocr'        => $namaOcr,
                'no'              => $no,
                'match_type'      => $matchType,
                'statuses'        => $statuses,
                'sources'         => $sources,
                'warnings'        => $warnings,
                'review_reasons'  => $review_reasons,
            ];
        }

        // Add DB students not found in OCR results
        foreach ($dbList as $s) {
            if (!in_array($s->id, $matchedIds, true)) {
                $statuses = [];
                $sources  = [];
                foreach ($actualDates as $day) {
                    $dayStr = (string) $day;
                    $date = Carbon::createFromDate($tahun, $bulan, $day);
                    $isFriday = in_array($day, $fridays, true);

                    if ($isFriday) {
                        $statuses[$dayStr] = 'LIBUR';
                        $sources[$dayStr]  = 'SYSTEM';
                    } elseif ($date->gt(Carbon::today())) {
                        $statuses[$dayStr] = 'LIBUR';
                        $sources[$dayStr]  = 'SYSTEM';
                    } else {
                        $statuses[$dayStr] = 'UNKNOWN';
                        $sources[$dayStr]  = 'REVIEW';
                    }
                }

                $reviewReasons = [];
                foreach ($actualDates as $day) {
                    $dayStr = (string) $day;
                    if (($sources[$dayStr] ?? null) === 'REVIEW') {
                        $reviewReasons[$dayStr] = 'Siswa tidak muncul pada hasil foto, perlu ditinjau manual.';
                    }
                }

                $result[] = [
                    'student_id'      => $s->id,
                    'nama'            => $s->nama,
                    'nisn'            => $s->nisn ?? '-',
                    'nama_ocr'        => '',
                    'no'              => 0,
                    'match_type'      => 'UNMATCHED_DB',
                    'statuses'        => $statuses,
                    'sources'         => $sources,
                    'warnings'        => ['Siswa di database tidak ada di hasil OCR.'],
                    'review_reasons'  => $reviewReasons,
                ];
            }
        }

        usort($result, function ($a, $b) {
            return strcmp($a['nama'], $b['nama']);
        });

        return $result;
    }

    public function normalizeName(string $name): string
    {
        $name = mb_strtolower(trim($name), 'UTF-8');
        $name = preg_replace('/[^\p{L}\p{N}\s]/u', '', $name);
        $name = preg_replace('/\s+/', ' ', $name);
        return trim($name);
    }

    protected function calculateFridays(int $bulan, int $tahun): array
    {
        $fridays = [];
        $daysInMonth = (int) date('t', mktime(0, 0, 0, $bulan, 1, $tahun));
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = Carbon::createFromDate($tahun, $bulan, $d);
            if ($date->isFriday()) {
                $fridays[] = $d;
            }
        }
        return $fridays;
    }

    /**
     * Validate matched import data and compute stats.
     */
    public function validateImportData(array $matchedData, int $totalDays, Carbon $monthStart, array $actualDates = []): array
    {
        if (empty($actualDates)) {
            $actualDates = range(1, $totalDays);
        }

        $stats = [
            'H'             => 0,
            'I'             => 0,
            'S'             => 0,
            'A'             => 0,
            'UNKNOWN'       => 0,
            'libur_jumat'   => 0,
            'belum_terjadi' => 0,
            'review'        => 0,
            'warning'       => 0,
            'source_ai'     => 0,
            'source_review' => 0,
            'source_system' => 0,
            'source_manual' => 0,
        ];

        $today = Carbon::today();

        foreach ($actualDates as $day) {
            $date = $monthStart->copy()->day($day);
            if ($date->month !== $monthStart->month) break;
            if ($date->isFriday()) $stats['libur_jumat']++;
            if ($date->gt($today)) $stats['belum_terjadi']++;
        }

        foreach ($matchedData as $row) {
            if (!$row['student_id']) {
                $stats['review']++;
            }
            if (!empty($row['warnings'])) {
                $stats['warning'] += count($row['warnings']);
            }

            foreach ($actualDates as $day) {
                $dayStr = (string) $day;
                $status = $row['statuses'][$dayStr] ?? 'UNKNOWN';
                $source = $row['sources'][$dayStr] ?? 'REVIEW';
                $date = $monthStart->copy()->day($day);
                if ($date->month !== $monthStart->month) continue;
                if ($date->isFriday() || $date->gt($today)) continue;

                if (isset($stats[$status])) $stats[$status]++;

                $sourceKey = 'source_' . strtolower($source);
                if (isset($stats[$sourceKey])) $stats[$sourceKey]++;
            }
        }

        $errors = [];

        return [
            'valid'  => empty($errors),
            'errors' => $errors,
            'stats'  => $stats,
        ];
    }

    public function getExistingDates(int $kelasId, int $tahunAjaranId, Carbon $monthStart, int $totalDays): array
    {
        $endDate = $monthStart->copy()->endOfMonth();

        return Absensi::where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->whereBetween('tanggal', [$monthStart->toDateString(), $endDate->toDateString()])
            ->pluck('tanggal')
            ->map(fn ($t) => Carbon::parse($t)->format('Y-m-d'))
            ->toArray();
    }

    /**
     * Import attendance to database. Skips LIBUR and future dates.
     * Uses source to set keterangan.
     */
    public function importAttendance(
        array $matchedData,
        int $kelasId,
        int $tahunAjaranId,
        int $userId,
        Carbon $monthStart,
        int $totalDays,
        array $existingDates,
        string $duplicateMode = 'skip',
        array $actualDates = []
    ): array {
        if (empty($actualDates)) {
            $actualDates = range(1, $totalDays);
        }

        $imported = 0;
        $skipped  = 0;
        $updated  = 0;
        $errors   = [];
        $today    = Carbon::today();

        DB::beginTransaction();

        try {
            foreach ($actualDates as $day) {
                $date = $monthStart->copy()->day($day);

                if ($date->month !== $monthStart->month) continue;
                if ($date->isFriday()) continue;
                if ($date->gt($today)) continue;

                $dateStr    = $date->toDateString();
                $isExisting = in_array($dateStr, $existingDates);

                if ($isExisting && $duplicateMode === 'skip') {
                    $skipped++;
                    continue;
                }

                $absensi = Absensi::updateOrCreate(
                    [
                        'kelas_id'         => $kelasId,
                        'tanggal'          => $dateStr,
                        'tahun_ajaran_id'  => $tahunAjaranId,
                    ],
                    ['user_id' => $userId]
                );

                foreach ($matchedData as $row) {
                    if (!$row['student_id']) continue;

                    $status = $row['statuses'][(string) $day] ?? 'UNKNOWN';
                    $source = $row['sources'][(string) $day] ?? 'REVIEW';

                    if ($status === 'LIBUR') continue;
                    if (in_array($status, ['UNKNOWN', 'REVIEW'], true) || $source === 'REVIEW') {
                        $errors[] = 'Masih ada hasil import yang perlu ditinjau sebelum disimpan.';
                        continue 2;
                    }

                    $keteranganMap = [
                        'AI'      => 'Import foto - AI OCR',
                        'REVIEW'  => 'Import foto - Perlu Tinjauan',
                        'SYSTEM'  => 'Import foto - Sistem',
                        'MANUAL'  => 'Import foto - Manual Koreksi',
                    ];
                    $keterangan = $keteranganMap[$source] ?? 'Import dari foto';

                    AbsensiDetail::updateOrCreate(
                        [
                            'absensi_id' => $absensi->id,
                            'student_id' => $row['student_id'],
                        ],
                        [
                            'status'      => $status,
                            'keterangan'  => $keterangan,
                        ]
                    );
                }

                $isExisting ? $updated++ : $imported++;
            }

            DB::commit();

            return [
                'success'  => true,
                'imported' => $imported,
                'skipped'  => $skipped,
                'updated'  => $updated,
                'errors'   => $errors,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Attendance import failed', ['error' => $e->getMessage()]);

            return [
                'success'  => false,
                'imported' => 0,
                'skipped'  => 0,
                'updated'  => 0,
                'errors'   => ['Gagal menyimpan absensi: ' . $e->getMessage()],
            ];
        }
    }

    public function cleanupFile(string $filePath): void
    {
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }

    public function getDayName(Carbon $date): string
    {
        $days = [
            0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
            4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu',
        ];
        return $days[$date->dayOfWeek] ?? '';
    }
}
