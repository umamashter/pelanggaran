<?php

namespace App\Services;

class AttendanceTableDetector
{
    /**
     * Quality improvement 3.2:
     * Detector disiapkan agar CV-ready dengan tahapan estimateTable -> buildMetadata -> generateOverlay -> fallback.
     * Masih heuristik, namun lebih kaya metadata dan lebih mudah di-debug.
     */
    public function detect(string $imagePath, array $templateMeta = [], array $analysis = []): array
    {
        $config = config('ocr.table_detection', []);
        $fullPath = storage_path('app/' . $imagePath);

        if (!(bool) ($config['enabled'] ?? true)) {
            return $this->fallback('Table detector disabled by config.');
        }

        if (!is_file($fullPath)) {
            return $this->fallback('Image file not found for table detection.');
        }

        $imageInfo = @getimagesize($fullPath);
        if (!$imageInfo) {
            return $this->fallback('Image metadata unreadable for table detection.');
        }

        $estimated = $this->estimateTable($fullPath, $imageInfo, $templateMeta, $analysis, $config);
        $metadata = $this->buildMetadata($estimated, $config);

        if ((bool) ($config['save_debug_overlay'] ?? false)) {
            $metadata['overlay_path'] = $this->generateOverlay($fullPath, $metadata, $config);
        }

        return $metadata;
    }

    protected function estimateTable(string $fullPath, array $imageInfo, array $templateMeta, array $analysis, array $config): array
    {
        $width = (int) ($imageInfo[0] ?? 0);
        $height = (int) ($imageInfo[1] ?? 0);
        $expectedTopRatio = (float) ($config['expected_table_top_ratio'] ?? 0.22);
        $expectedLeftRatio = (float) ($config['expected_table_left_ratio'] ?? 0.08);
        $expectedWidthRatio = (float) ($config['expected_table_width_ratio'] ?? 0.84);
        $expectedHeightRatio = (float) ($config['expected_table_height_ratio'] ?? 0.60);

        $templateAnchors = $templateMeta['anchors']['estimated_table_origin'] ?? null;
        $templateConfidence = (float) ($templateMeta['confidence'] ?? 0.0);
        $layoutConfidence = (float) ($templateMeta['layout_confidence'] ?? 0.0);
        $fileSize = (int) (@filesize($fullPath) ?: 0);

        $x = $templateAnchors['x'] ?? (int) round($width * $expectedLeftRatio);
        $y = $templateAnchors['y'] ?? (int) round($height * $expectedTopRatio);
        $tableWidth = (int) round($width * $expectedWidthRatio);
        $tableHeight = (int) round($height * $expectedHeightRatio);
        $tableAreaRatio = ($width > 0 && $height > 0) ? (($tableWidth * $tableHeight) / max(1, ($width * $height))) : 0.0;

        $borderDensity = $this->estimateBorderDensity($width, $height, $fileSize);
        $lineDensity = $this->estimateLineDensity($width, $height, $fileSize);
        $whitespaceProfile = $this->estimateWhitespaceProfile($width, $height, $fileSize);
        $layoutScore = round(($borderDensity * 0.30) + ($lineDensity * 0.35) + ((1 - abs(0.35 - $whitespaceProfile)) * 0.15) + ($layoutConfidence * 0.20), 4);
        $tableConfidence = round(($templateConfidence * 0.35) + ($layoutConfidence * 0.20) + ($borderDensity * 0.15) + ($lineDensity * 0.15) + ($layoutScore * 0.15), 4);

        return [
            'image_width' => $width,
            'image_height' => $height,
            'table_box' => [
                'x' => max(0, $x),
                'y' => max(0, $y),
                'width' => max(0, min($tableWidth, $width - $x)),
                'height' => max(0, min($tableHeight, $height - $y)),
            ],
            'table_confidence' => $tableConfidence,
            'table_area_ratio' => round($tableAreaRatio, 4),
            'layout_score' => $layoutScore,
            'border_density' => $borderDensity,
            'line_density' => $lineDensity,
            'estimated_origin' => ['x' => max(0, $x), 'y' => max(0, $y)],
        ];
    }

    protected function buildMetadata(array $estimated, array $config): array
    {
        $detected = $estimated['table_confidence'] >= (float) ($config['minimum_confidence'] ?? 0.70)
            && $estimated['table_area_ratio'] >= (float) ($config['minimum_table_area'] ?? 0.30);

        $warnings = [];
        if (!$detected) {
            $warnings[] = 'Main table confidence below configured threshold. Falling back to full image.';
        }

        return [
            'detected' => $detected,
            'table_box' => $estimated['table_box'],
            'table_confidence' => $estimated['table_confidence'],
            'table_area_ratio' => $estimated['table_area_ratio'],
            'layout_score' => $estimated['layout_score'],
            'border_density' => $estimated['border_density'],
            'line_density' => $estimated['line_density'],
            'estimated_origin' => $estimated['estimated_origin'],
            'overlay_path' => null,
            'recommendation' => [
                'use_table_roi' => $detected,
                'fallback_full_image' => !$detected,
                'continue_pipeline' => true,
            ],
            'warnings' => $warnings,
        ];
    }

    protected function generateOverlay(string $fullPath, array $metadata, array $config): ?string
    {
        $image = @imagecreatefromstring((string) @file_get_contents($fullPath));
        if (!$image) {
            return null;
        }

        $dir = base_path($config['debug_root'] ?? 'storage/app/ocr/debug');
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        $timestamp = now()->format('Ymd_His');
        $originalPath = $dir . DIRECTORY_SEPARATOR . 'original_' . $timestamp . '.jpg';
        $overlayPath = $dir . DIRECTORY_SEPARATOR . 'table_overlay_' . $timestamp . '.jpg';

        @imagejpeg($image, $originalPath, 90);

        $color = imagecolorallocate($image, 255, 0, 0);
        $textColor = imagecolorallocate($image, 255, 255, 0);
        $box = $metadata['table_box'] ?? [];
        if (!empty($box)) {
            imagerectangle(
                $image,
                (int) ($box['x'] ?? 0),
                (int) ($box['y'] ?? 0),
                (int) (($box['x'] ?? 0) + ($box['width'] ?? 0)),
                (int) (($box['y'] ?? 0) + ($box['height'] ?? 0)),
                $color
            );
            imagestring($image, 3, (int) ($box['x'] ?? 0) + 4, max(0, (int) ($box['y'] ?? 0) - 14), 'TABLE ROI (' . ($metadata['table_confidence'] ?? 0) . ')', $textColor);
        }

        if (!empty($metadata['estimated_origin'])) {
            imagestring($image, 2, 4, 4, 'Origin: ' . ($metadata['estimated_origin']['x'] ?? 0) . ',' . ($metadata['estimated_origin']['y'] ?? 0), $textColor);
        }
        if (!empty($metadata['warnings'][0])) {
            imagestring($image, 2, 4, 20, 'Fallback: ' . $metadata['warnings'][0], $textColor);
        }

        @imagejpeg($image, $overlayPath, 90);
        imagedestroy($image);

        return str_replace(base_path() . DIRECTORY_SEPARATOR, '', $overlayPath);
    }

    protected function estimateBorderDensity(int $width, int $height, int $fileSize): float
    {
        $ratio = $height > 0 ? ($width / max($height, 1)) : 0.0;
        $density = min(1.0, max(0.0, ($ratio * 0.08) + ($fileSize > 300000 ? 0.06 : 0.02)));
        return round($density, 4);
    }

    protected function estimateLineDensity(int $width, int $height, int $fileSize): float
    {
        $area = max(1, $width * $height);
        return round(min(1.0, max(0.0, ($fileSize / $area) * 10)), 4);
    }

    protected function estimateWhitespaceProfile(int $width, int $height, int $fileSize): float
    {
        $area = max(1, $width * $height);
        return round(min(1.0, max(0.0, 1 - (($fileSize / $area) * 6))), 4);
    }

    protected function fallback(string $warning): array
    {
        return [
            'detected' => false,
            'table_box' => [],
            'table_confidence' => 0.0,
            'table_area_ratio' => 0.0,
            'layout_score' => 0.0,
            'border_density' => 0.0,
            'line_density' => 0.0,
            'estimated_origin' => [],
            'overlay_path' => null,
            'recommendation' => [
                'use_table_roi' => false,
                'fallback_full_image' => true,
                'continue_pipeline' => true,
            ],
            'warnings' => [$warning],
        ];
    }
}
