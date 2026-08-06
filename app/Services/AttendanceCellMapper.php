<?php

namespace App\Services;

class AttendanceCellMapper
{
    /**
     * Modul 3.4:
     * Hanya membangun struktur sel dari metadata grid.
     * Tidak melakukan OCR, lookup database, coordinate mapping, atau membaca isi sel.
     */
    public function map(array $gridMeta = [], array $tableMeta = []): array
    {
        $config = config('ocr.cell_mapping', []);

        if (!(bool) ($config['enabled'] ?? true)) {
            return $this->fallback('Cell mapper disabled by config.');
        }

        $gridDetected = (bool) ($gridMeta['grid_detected'] ?? false);
        $rows = $gridMeta['rows'] ?? [];
        $columns = $gridMeta['columns'] ?? [];

        if (!$gridDetected || empty($rows) || empty($columns)) {
            return $this->fallback('Grid metadata is incomplete for cell mapping.');
        }

        $expectedDateColumns = (int) ($config['expected_date_columns'] ?? 31);
        $expectedRecapColumns = (int) ($config['expected_recap_columns'] ?? 3);
        $tableBox = $tableMeta['table_box'] ?? [];

        $dateColumns = [];
        $recapColumns = [];
        $warnings = [];
        $cells = [];
        $studentRows = [];

        $totalColumns = count($columns);
        $splitIndex = max(0, $totalColumns - $expectedRecapColumns);

        foreach ($columns as $index => $column) {
            if ($index < min($expectedDateColumns, $splitIndex)) {
                $dateColumns[] = [
                    'column_index' => $index + 1,
                    'x' => $column['x'] ?? 0,
                    'width' => $column['width'] ?? 0,
                    'confidence' => $column['confidence'] ?? 0.70,
                ];
            } else {
                $recapColumns[] = [
                    'column_index' => $index + 1,
                    'x' => $column['x'] ?? 0,
                    'width' => $column['width'] ?? 0,
                    'confidence' => $column['confidence'] ?? 0.70,
                ];
            }
        }

        foreach ($rows as $rowIndex => $row) {
            $rowConfidence = (float) ($row['confidence'] ?? 0.70);
            if ($rowIndex > 0) {
                $studentRows[] = [
                    'row_index' => $rowIndex,
                    'y' => $row['y'] ?? 0,
                    'height' => $row['height'] ?? 0,
                    'confidence' => $rowConfidence,
                ];
            }

            foreach ($columns as $columnIndex => $column) {
                $cellConfidence = round((($rowConfidence + (float) ($column['confidence'] ?? 0.70)) / 2), 4);
                $cells[] = [
                    'id' => 'R' . $rowIndex . 'C' . $columnIndex,
                    'row' => $rowIndex,
                    'column' => $columnIndex,
                    'x' => $column['x'] ?? 0,
                    'y' => $row['y'] ?? 0,
                    'width' => $column['width'] ?? 0,
                    'height' => $row['height'] ?? 0,
                    'confidence' => $cellConfidence,
                    'estimated' => true,
                    'fallback' => false,
                    'warnings' => [],
                ];
            }
        }

        $headerRegion = !empty($rows[0]) ? [
            'y' => $rows[0]['y'] ?? 0,
            'height' => $rows[0]['height'] ?? 0,
            'confidence' => $rows[0]['confidence'] ?? 0.70,
        ] : [];

        $studentRegion = !empty($studentRows) ? [
            'y' => $studentRows[0]['y'] ?? 0,
            'height' => array_sum(array_column($studentRows, 'height')),
            'confidence' => round(array_sum(array_column($studentRows, 'confidence')) / max(1, count($studentRows)), 4),
        ] : [];

        $dateRegion = [
            'column_count' => count($dateColumns),
            'confidence' => !empty($dateColumns) ? round(array_sum(array_column($dateColumns, 'confidence')) / count($dateColumns), 4) : 0.0,
        ];

        $recapRegion = [
            'column_count' => count($recapColumns),
            'confidence' => !empty($recapColumns) ? round(array_sum(array_column($recapColumns, 'confidence')) / count($recapColumns), 4) : 0.0,
        ];

        if (count($dateColumns) < $expectedDateColumns) {
            $warnings[] = 'date_columns_less_than_expected';
        }
        if (count($recapColumns) < $expectedRecapColumns) {
            $warnings[] = 'recap_columns_less_than_expected';
        }

        $cellMappingConfidence = round((($gridMeta['grid_confidence'] ?? 0.0) * 0.50) + (($studentRegion['confidence'] ?? 0.0) * 0.30) + (($dateRegion['confidence'] ?? 0.0) * 0.20), 4);

        return [
            'cells' => $cells,
            'student_rows' => $studentRows,
            'date_columns' => $dateColumns,
            'recap_columns' => $recapColumns,
            'regions' => [
                'header_region' => $headerRegion,
                'student_region' => $studentRegion,
                'date_region' => $dateRegion,
                'recap_region' => $recapRegion,
                'footer_region' => [],
                'signature_region' => [],
            ],
            'confidence' => $cellMappingConfidence,
            'recommendation' => [
                'ready_for_coordinate_mapper' => $cellMappingConfidence >= (float) ($config['minimum_confidence'] ?? 0.50),
                'fallback_grid_only' => false,
                'fallback_table_roi' => false,
                'manual_review' => $cellMappingConfidence < (float) ($config['minimum_confidence'] ?? 0.50),
            ],
            'warnings' => $warnings,
        ];
    }

    protected function fallback(string $warning): array
    {
        return [
            'cells' => [],
            'student_rows' => [],
            'date_columns' => [],
            'recap_columns' => [],
            'regions' => [
                'header_region' => [],
                'student_region' => [],
                'date_region' => [],
                'recap_region' => [],
                'footer_region' => [],
                'signature_region' => [],
            ],
            'confidence' => 0.0,
            'recommendation' => [
                'ready_for_coordinate_mapper' => false,
                'fallback_grid_only' => true,
                'fallback_table_roi' => true,
                'manual_review' => true,
            ],
            'warnings' => [$warning],
        ];
    }
}
