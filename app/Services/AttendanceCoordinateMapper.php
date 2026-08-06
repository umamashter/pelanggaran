<?php

namespace App\Services;

class AttendanceCoordinateMapper
{
    /**
     * Modul 3.5:
     * Hanya membangun logical coordinate map dari metadata cell map.
     * Tidak melakukan OCR, DB lookup, validation, atau membaca teks.
     */
    public function map(array $cellMap = []): array
    {
        $config = config('ocr.coordinate_mapping', []);

        if (!(bool) ($config['enabled'] ?? true)) {
            return $this->fallback('Coordinate mapper disabled by config.');
        }

        $cells = $cellMap['cells'] ?? [];
        if (empty($cells)) {
            return $this->fallback('Cell map is empty for coordinate mapping.');
        }

        $regions = $cellMap['regions'] ?? [];
        $dateColumns = $cellMap['date_columns'] ?? [];
        $recapColumns = $cellMap['recap_columns'] ?? [];
        $studentRows = $cellMap['student_rows'] ?? [];

        $coordinates = [];
        $headerCount = 0;
        $attendanceCount = 0;
        $dateCount = 0;
        $recapCount = 0;
        $footerCount = 0;
        $unknownCount = 0;
        $warnings = [];
        $confidenceValues = [];

        $recapColumnIndexes = array_map(fn ($c) => $c['column_index'] ?? null, $recapColumns);
        $dateColumnIndexes = array_map(fn ($c) => $c['column_index'] ?? null, $dateColumns);
        $studentRowIndexes = array_map(fn ($r) => $r['row_index'] ?? null, $studentRows);

        foreach ($cells as $cell) {
            $row = (int) ($cell['row'] ?? 0);
            $column = (int) ($cell['column'] ?? 0);
            $x = (int) ($cell['x'] ?? 0);
            $y = (int) ($cell['y'] ?? 0);
            $width = (int) ($cell['width'] ?? 0);
            $height = (int) ($cell['height'] ?? 0);
            $confidence = (float) ($cell['confidence'] ?? 0.0);
            $confidenceValues[] = $confidence;

            $region = 'unknown';
            $logicalType = 'unknown';

            if ($row === 0) {
                $region = 'header';
                $logicalType = 'header';
                $headerCount++;
            } elseif (in_array($column + 1, $recapColumnIndexes, true)) {
                $region = 'recap';
                $recapLabel = $this->resolveRecapType($column + 1, $recapColumnIndexes);
                $logicalType = $recapLabel;
                $recapCount++;
            } elseif (in_array($column + 1, $dateColumnIndexes, true)) {
                $region = 'attendance';
                $logicalType = 'date';
                $attendanceCount++;
                $dateCount++;
            } elseif (in_array($row, $studentRowIndexes, true) && $column === 0) {
                $region = 'student_name';
                $logicalType = 'student_name';
            } else {
                $unknownCount++;
            }

            $coordinates[] = [
                'cell_id' => $cell['id'] ?? ('R' . $row . 'C' . $column),
                'row' => $row,
                'column' => $column,
                'x' => $x,
                'y' => $y,
                'width' => $width,
                'height' => $height,
                'center' => [
                    'x' => (int) round($x + ($width / 2)),
                    'y' => (int) round($y + ($height / 2)),
                ],
                'region' => $region,
                'logical_type' => $logicalType,
                'confidence' => $confidence,
            ];
        }

        if ($unknownCount > 0) {
            $warnings[] = 'unknown_region';
        }
        if (count($coordinates) !== count($cells)) {
            $warnings[] = 'missing_cells';
        }

        $mappingConfidence = !empty($confidenceValues)
            ? round(array_sum($confidenceValues) / count($confidenceValues), 4)
            : 0.0;

        if ($mappingConfidence < (float) ($config['mapping_confidence_threshold'] ?? 0.75)) {
            $warnings[] = 'low_mapping_confidence';
        }

        return [
            'success' => true,
            'rows' => $studentRows,
            'columns' => array_merge($dateColumns, $recapColumns),
            'coordinates' => $coordinates,
            'statistics' => [
                'total_cells' => count($cells),
                'mapped_cells' => count($coordinates),
                'header_cells' => $headerCount,
                'attendance_cells' => $attendanceCount,
                'date_cells' => $dateCount,
                'recap_cells' => $recapCount,
                'footer_cells' => $footerCount,
                'unknown_cells' => $unknownCount,
                'mapping_confidence' => $mappingConfidence,
            ],
            'recommendation' => [
                'coordinate_mapping_good' => $mappingConfidence >= (float) ($config['mapping_confidence_threshold'] ?? 0.75),
                'coordinate_mapping_partial' => $mappingConfidence < (float) ($config['mapping_confidence_threshold'] ?? 0.75),
                'coordinate_mapping_low_confidence' => $mappingConfidence < 0.50,
                'fallback_to_roi' => $unknownCount > 0,
            ],
            'warnings' => array_values(array_unique($warnings)),
        ];
    }

    protected function resolveRecapType(int $columnIndex, array $recapColumnIndexes): string
    {
        $recapColumnIndexes = array_values(array_filter($recapColumnIndexes, fn ($v) => $v !== null));
        $position = array_search($columnIndex, $recapColumnIndexes, true);

        return match ($position) {
            0 => 'recap_A',
            1 => 'recap_I',
            2 => 'recap_S',
            default => 'recap',
        };
    }

    protected function fallback(string $warning): array
    {
        return [
            'success' => false,
            'rows' => [],
            'columns' => [],
            'coordinates' => [],
            'statistics' => [
                'total_cells' => 0,
                'mapped_cells' => 0,
                'header_cells' => 0,
                'attendance_cells' => 0,
                'date_cells' => 0,
                'recap_cells' => 0,
                'footer_cells' => 0,
                'unknown_cells' => 0,
                'mapping_confidence' => 0.0,
            ],
            'recommendation' => [
                'coordinate_mapping_good' => false,
                'coordinate_mapping_partial' => true,
                'coordinate_mapping_low_confidence' => true,
                'fallback_to_roi' => true,
            ],
            'warnings' => [$warning],
        ];
    }
}
