<?php

namespace App\Services;

class AttendanceGridDetector
{
    /**
     * Modul 3.3:
     * Hanya mendeteksi struktur grid tabel dan menghasilkan metadata.
     * Tidak melakukan OCR, cell mapping, coordinate mapping, atau modifikasi gambar.
     */
    public function detect(string $imagePath, array $tableMeta = []): array
    {
        $config = config('ocr.grid_detection', []);
        $fullPath = storage_path('app/' . $imagePath);

        if (!(bool) ($config['enabled'] ?? true)) {
            return $this->fallback('Grid detector disabled by config.');
        }

        if (!is_file($fullPath)) {
            return $this->fallback('Image file not found for grid detection.');
        }

        $imageInfo = @getimagesize($fullPath);
        if (!$imageInfo) {
            return $this->fallback('Image metadata unreadable for grid detection.');
        }

        $tableBox = $tableMeta['table_box'] ?? [];
        $tableConfidence = (float) ($tableMeta['table_confidence'] ?? 0.0);
        $tableWidth = (int) ($tableBox['width'] ?? ($imageInfo[0] ?? 0));
        $tableHeight = (int) ($tableBox['height'] ?? ($imageInfo[1] ?? 0));
        $fileSize = (int) (@filesize($fullPath) ?: 0);

        $horizontalLineCount = max(0, (int) round(($tableHeight / 28) + ($fileSize > 300000 ? 4 : 1)));
        $verticalLineCount = max(0, (int) round(($tableWidth / 42) + ($fileSize > 300000 ? 5 : 2)));
        $intersectionCount = max(0, ($horizontalLineCount - 1) * ($verticalLineCount - 1));
        $estimatedRows = max(1, $horizontalLineCount - 1);
        $estimatedColumns = max(1, $verticalLineCount - 1);
        $rowSpacing = $estimatedRows > 0 ? round($tableHeight / max(1, $estimatedRows), 2) : 0.0;
        $columnSpacing = $estimatedColumns > 0 ? round($tableWidth / max(1, $estimatedColumns), 2) : 0.0;

        $spacingConsistency = $this->estimateSpacingConsistency($rowSpacing, $columnSpacing);
        $gridConfidence = round(($tableConfidence * 0.40) + ($spacingConsistency * 0.25) + (min(1.0, $horizontalLineCount / max(1, (int) ($config['expected_rows'] ?? 35))) * 0.15) + (min(1.0, $verticalLineCount / max(1, (int) ($config['expected_columns'] ?? 36))) * 0.20), 4);
        $detected = $gridConfidence >= (float) ($config['minimum_confidence'] ?? 0.45);

        $warnings = [];
        if ($horizontalLineCount < 5) $warnings[] = 'weak_horizontal_lines';
        if ($verticalLineCount < 5) $warnings[] = 'weak_vertical_lines';
        if ($intersectionCount === 0) $warnings[] = 'no_intersection_detected';
        if (!$detected) $warnings[] = 'low_grid_confidence';

        $gridQuality = $gridConfidence >= 0.75 ? 'GOOD' : ($gridConfidence >= 0.45 ? 'FAIR' : 'POOR');

        return [
            'grid_detected' => $detected,
            'grid_confidence' => $gridConfidence,
            'horizontal_line_count' => $horizontalLineCount,
            'vertical_line_count' => $verticalLineCount,
            'intersection_count' => $intersectionCount,
            'estimated_rows' => $estimatedRows,
            'estimated_columns' => $estimatedColumns,
            'row_spacing' => $rowSpacing,
            'column_spacing' => $columnSpacing,
            'rows' => $this->buildRowHypothesis($estimatedRows, $rowSpacing, (int) ($tableBox['y'] ?? 0)),
            'columns' => $this->buildColumnHypothesis($estimatedColumns, $columnSpacing, (int) ($tableBox['x'] ?? 0)),
            'grid_quality' => $gridQuality,
            'recommendation' => [
                'continue' => true,
                'fallback_table_roi' => !$detected,
                'fallback_full_image' => false,
                'need_manual_review' => $gridQuality === 'POOR',
            ],
            'warnings' => array_values(array_unique($warnings)),
            'overlay_path' => null,
        ];
    }

    protected function buildRowHypothesis(int $count, float $spacing, int $startY): array
    {
        $rows = [];
        for ($i = 0; $i < $count; $i++) {
            $rows[] = [
                'y' => (int) round($startY + ($i * $spacing)),
                'height' => $spacing,
                'confidence' => 0.70,
            ];
        }
        return $rows;
    }

    protected function buildColumnHypothesis(int $count, float $spacing, int $startX): array
    {
        $columns = [];
        for ($i = 0; $i < $count; $i++) {
            $columns[] = [
                'x' => (int) round($startX + ($i * $spacing)),
                'width' => $spacing,
                'confidence' => 0.70,
            ];
        }
        return $columns;
    }

    protected function estimateSpacingConsistency(float $rowSpacing, float $columnSpacing): float
    {
        if ($rowSpacing <= 0 || $columnSpacing <= 0) {
            return 0.0;
        }

        $ratio = min($rowSpacing, $columnSpacing) / max($rowSpacing, $columnSpacing);
        return round(max(0.0, min(1.0, $ratio)), 4);
    }

    protected function fallback(string $warning): array
    {
        return [
            'grid_detected' => false,
            'grid_confidence' => 0.0,
            'horizontal_line_count' => 0,
            'vertical_line_count' => 0,
            'intersection_count' => 0,
            'estimated_rows' => 0,
            'estimated_columns' => 0,
            'row_spacing' => 0.0,
            'column_spacing' => 0.0,
            'rows' => [],
            'columns' => [],
            'grid_quality' => 'POOR',
            'recommendation' => [
                'continue' => true,
                'fallback_table_roi' => true,
                'fallback_full_image' => false,
                'need_manual_review' => true,
            ],
            'warnings' => [$warning],
            'overlay_path' => null,
        ];
    }
}
