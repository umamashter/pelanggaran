<?php

namespace App\Services;

class AttendanceStudentRowMatcher
{
    /**
     * Modul 3.7:
     * Mengelompokkan hasil OCR per-cell menjadi kandidat row siswa.
     * Tidak melakukan DB lookup, validation semantik, atau business rules.
     */
    public function match(array $tableMeta = [], array $gridMeta = [], array $cellMap = [], array $coordinateMap = [], array $ocrMeta = []): array
    {
        $config = config('ocr.student_row_matcher', []);

        if (!(bool) ($config['enabled'] ?? true)) {
            return $this->fallback('Student row matcher disabled by config.');
        }

        $studentRows = $cellMap['student_rows'] ?? [];
        $coordinates = $coordinateMap['coordinates'] ?? [];
        $ocrCells = $ocrMeta['cells'] ?? [];

        if (empty($studentRows) || empty($coordinates) || empty($ocrCells)) {
            return $this->fallback('Student row matcher prerequisites are incomplete.');
        }

        $ocrByCellId = [];
        foreach ($ocrCells as $ocrCell) {
            $ocrByCellId[$ocrCell['cell_id'] ?? ''] = $ocrCell;
        }

        $students = [];
        $confidenceValues = [];
        $qualityValues = [];
        $matchedRows = 0;
        $unmatchedRows = 0;
        $globalWarnings = [];

        foreach ($studentRows as $rowMeta) {
            $rowId = (int) ($rowMeta['row_index'] ?? 0);
            $rowCells = array_values(array_filter($coordinates, function ($coordinate) use ($rowId) {
                return (int) ($coordinate['row'] ?? -1) === $rowId;
            }));

            $attendanceCells = [];
            $recapCells = [];
            $warnings = [];
            $successfulOcr = 0;
            $confidenceAccumulator = [];

            foreach ($rowCells as $coordinate) {
                $cellId = $coordinate['cell_id'] ?? '';
                $logicalType = $coordinate['logical_type'] ?? 'unknown';
                $ocr = $ocrByCellId[$cellId] ?? null;

                if (str_starts_with($logicalType, 'recap')) {
                    $recapCells[] = array_merge($coordinate, ['ocr' => $ocr]);
                } elseif (in_array($logicalType, ['date', 'attendance_symbol'], true)) {
                    $attendanceCells[] = array_merge($coordinate, ['ocr' => $ocr]);
                }

                if ($ocr && (($ocr['state'] ?? '') === 'success')) {
                    $successfulOcr++;
                    $confidenceAccumulator[] = (float) ($ocr['confidence'] ?? 0);
                }
            }

            if (empty($attendanceCells)) {
                $warnings[] = 'missing_attendance_cells';
            }
            if (empty($recapCells)) {
                $warnings[] = 'missing_recap_cells';
            }
            if ($successfulOcr === 0) {
                $warnings[] = 'low_confidence';
            }
            if (count($attendanceCells) > 0 && $successfulOcr < count($attendanceCells) / 2) {
                $warnings[] = 'partial_mapping';
            }

            $completeness = count($attendanceCells) > 0 ? min(1.0, $successfulOcr / max(1, count($attendanceCells))) : 0.0;
            $recapCompleteness = count($recapCells) > 0 ? min(1.0, count(array_filter($recapCells, fn ($c) => !empty($c['ocr']))) / max(1, count($recapCells))) : 0.0;
            $avgConfidence = !empty($confidenceAccumulator) ? array_sum($confidenceAccumulator) / count($confidenceAccumulator) : 0.0;
            $regionConsistency = (!empty($attendanceCells) && !empty($recapCells)) ? 1.0 : 0.6;
            $qualityScore = round((($avgConfidence / 100) * 0.45) + ($completeness * 0.30) + ($recapCompleteness * 0.15) + ($regionConsistency * 0.10), 4);
            $rowConfidence = round((($avgConfidence / 100) * 0.50) + ($completeness * 0.30) + ($regionConsistency * 0.20), 4) * 100;

            if ($rowConfidence >= (float) ($config['minimum_confidence'] ?? 70)) {
                $matchedRows++;
            } else {
                $unmatchedRows++;
            }

            $confidenceValues[] = $rowConfidence;
            $qualityValues[] = $qualityScore;

            $students[] = [
                'row_id' => $rowId,
                'student_name' => 'Row-' . $rowId,
                'attendance_cells' => $attendanceCells,
                'recap_cells' => $recapCells,
                'confidence' => round($rowConfidence, 2),
                'quality_score' => round($qualityScore * 100, 2),
                'warnings' => $warnings,
            ];
        }

        if ($unmatchedRows > 0) {
            $globalWarnings[] = 'low_confidence';
        }

        return [
            'rows_processed' => count($studentRows),
            'matched_rows' => $matchedRows,
            'unmatched_rows' => $unmatchedRows,
            'average_confidence' => !empty($confidenceValues) ? round(array_sum($confidenceValues) / count($confidenceValues), 2) : 0,
            'average_quality' => !empty($qualityValues) ? round((array_sum($qualityValues) / count($qualityValues)) * 100, 2) : 0,
            'students' => $students,
            'recommendation' => [
                'ready_for_validation' => $unmatchedRows === 0,
                'manual_review' => $unmatchedRows > 0,
                'fallback_required' => $matchedRows === 0,
            ],
            'warnings' => array_values(array_unique($globalWarnings)),
        ];
    }

    protected function fallback(string $warning): array
    {
        return [
            'rows_processed' => 0,
            'matched_rows' => 0,
            'unmatched_rows' => 0,
            'average_confidence' => 0,
            'average_quality' => 0,
            'students' => [],
            'recommendation' => [
                'ready_for_validation' => false,
                'manual_review' => true,
                'fallback_required' => true,
            ],
            'warnings' => [$warning],
        ];
    }
}
