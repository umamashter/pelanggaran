<?php

namespace App\Services;

class AttendanceTemplateMatcher
{
    /**
     * Hardening 3.1:
     * Template matcher diperkaya dengan confidence gabungan, anchor estimasi yang lebih representatif,
     * alignment score terpisah, layout confidence, dan recommendation yang lebih informatif.
     */
    public function match(string $imagePath): array
    {
        $fullPath = storage_path('app/' . $imagePath);
        $config = config('ocr.template_matching', []);

        if (!(bool) ($config['enabled'] ?? true)) {
            return $this->fallback('Template matcher disabled by config.');
        }

        if (!is_file($fullPath)) {
            return $this->fallback('Image file not found for template matching.');
        }

        $imageInfo = @getimagesize($fullPath);
        if (!$imageInfo) {
            return $this->fallback('Image metadata unreadable for template matching.');
        }

        $width = (int) ($imageInfo[0] ?? 0);
        $height = (int) ($imageInfo[1] ?? 0);
        $orientation = $width >= $height ? 'landscape' : 'portrait';
        $aspectRatio = $height > 0 ? round($width / $height, 4) : 0.0;
        $fileSize = (int) (@filesize($fullPath) ?: 0);

        $expectedOrientation = $config['expected_orientation'] ?? 'landscape';
        $targetAspect = (float) ($config['target_aspect_ratio'] ?? 1.414);
        $templateTolerance = (float) ($config['template_tolerance'] ?? 0.35);

        $orientationScore = $orientation === $expectedOrientation ? 1.0 : 0.55;
        $pageRatioScore = max(0.0, 1.0 - min(1.0, abs($aspectRatio - $targetAspect) / max($templateTolerance, 0.01)));
        $borderDensityScore = $this->estimateBorderDensityScore($width, $height, $fileSize, (float) ($config['border_density_target'] ?? 0.12));
        $lineDensityScore = $this->estimateLineDensityScore($width, $height, $fileSize, (float) ($config['line_density_target'] ?? 0.10));
        $whitespaceScore = $this->estimateWhitespaceProfileScore($width, $height, $fileSize, (float) ($config['whitespace_profile_target'] ?? 0.32));
        $gridLikelihood = $this->estimateGridLikelihoodScore($width, $height, $fileSize, (float) ($config['grid_likelihood_target'] ?? 0.75));
        $layoutConfidence = round(($borderDensityScore * 0.25) + ($lineDensityScore * 0.30) + ($whitespaceScore * 0.20) + ($gridLikelihood * 0.25), 4);
        $alignmentScore = round(($orientationScore * 0.45) + ($pageRatioScore * 0.35) + ($layoutConfidence * 0.20), 4);
        $sizeScore = $fileSize > 900000 ? 0.95 : ($fileSize > 400000 ? 0.82 : ($fileSize > 150000 ? 0.66 : 0.45));
        $templateConfidence = round(($pageRatioScore * 0.24) + ($orientationScore * 0.16) + ($borderDensityScore * 0.14) + ($lineDensityScore * 0.14) + ($whitespaceScore * 0.10) + ($gridLikelihood * 0.12) + ($layoutConfidence * 0.10), 4);
        $templateConfidence = round(($templateConfidence * 0.90) + ($sizeScore * 0.10), 4);

        $matched = $templateConfidence >= (float) ($config['minimum_confidence'] ?? 0.70);
        $tableTop = (int) round($height * (float) ($config['expected_table_top_ratio'] ?? 0.22));
        $tableLeft = (int) round($width * (float) ($config['expected_table_left_ratio'] ?? 0.08));
        $tableWidth = (int) round($width * (float) ($config['expected_table_width_ratio'] ?? 0.84));
        $tableHeight = (int) round($height * (float) ($config['expected_table_height_ratio'] ?? 0.60));

        $anchors = [
            'page_top' => ['x' => (int) round($width / 2), 'y' => 0],
            'page_bottom' => ['x' => (int) round($width / 2), 'y' => max(0, $height - 1)],
            'page_left' => ['x' => 0, 'y' => (int) round($height / 2)],
            'page_right' => ['x' => max(0, $width - 1), 'y' => (int) round($height / 2)],
            'estimated_table_origin' => ['x' => $tableLeft, 'y' => $tableTop],
        ];

        $alignment = [
            'rotation_estimate' => round(($orientation === 'landscape' ? 0.0 : 2.5), 2),
            'perspective_hint' => $layoutConfidence >= 0.70 ? 'stable' : 'review',
            'scale' => 1.0,
            'offset_x' => 0,
            'offset_y' => 0,
            'alignment_score' => $alignmentScore,
        ];

        $warnings = [];
        if (!$matched) {
            $warnings[] = 'Template absensi tidak dikenali secara penuh. Sistem akan menggunakan analisis umum sehingga akurasi mungkin sedikit menurun.';
        }

        return [
            'matched' => $matched,
            'template_name' => 'mi_nurul_ulum',
            'confidence' => $templateConfidence,
            'alignment_score' => $alignmentScore,
            'layout_confidence' => $layoutConfidence,
            'anchors' => $anchors,
            'alignment' => $alignment,
            'recommendation' => [
                'continue_pipeline' => true,
                'run_preprocessing' => $templateConfidence < 0.80 || $alignmentScore < (float) ($config['minimum_alignment_score'] ?? 0.65),
                'prefer_full_image' => !$matched,
                'prefer_roi' => $matched && $layoutConfidence >= (float) ($config['minimum_layout_confidence'] ?? 0.60),
                'fallback_to_analyzer' => !$matched,
            ],
            'warnings' => $warnings,
        ];
    }

    protected function estimateBorderDensityScore(int $width, int $height, int $fileSize, float $target): float
    {
        $ratio = $height > 0 ? min(1.0, max(0.0, ($width / max($height, 1)) / 2)) : 0.0;
        $sizeFactor = $fileSize > 300000 ? 0.12 : 0.08;
        $density = min(1.0, max(0.0, $ratio * $sizeFactor));
        return round(max(0.0, 1.0 - min(1.0, abs($density - $target) / max($target, 0.01))), 4);
    }

    protected function estimateLineDensityScore(int $width, int $height, int $fileSize, float $target): float
    {
        $area = max(1, $width * $height);
        $density = min(1.0, max(0.0, ($fileSize / $area) * 12));
        return round(max(0.0, 1.0 - min(1.0, abs($density - $target) / max($target, 0.01))), 4);
    }

    protected function estimateWhitespaceProfileScore(int $width, int $height, int $fileSize, float $target): float
    {
        $profile = min(1.0, max(0.0, 1 - (($fileSize / max(1, ($width * $height))) * 8)));
        return round(max(0.0, 1.0 - min(1.0, abs($profile - $target) / max($target, 0.01))), 4);
    }

    protected function estimateGridLikelihoodScore(int $width, int $height, int $fileSize, float $target): float
    {
        $landscapeFactor = $width >= $height ? 0.78 : 0.52;
        $sizeFactor = $fileSize > 300000 ? 0.82 : ($fileSize > 120000 ? 0.66 : 0.42);
        $score = ($landscapeFactor * 0.55) + ($sizeFactor * 0.45);
        return round(max(0.0, 1.0 - min(1.0, abs($score - $target) / max($target, 0.01))), 4);
    }

    protected function fallback(string $warning): array
    {
        return [
            'matched' => false,
            'template_name' => null,
            'confidence' => 0.0,
            'alignment_score' => 0.0,
            'layout_confidence' => 0.0,
            'anchors' => [],
            'alignment' => [],
            'recommendation' => [
                'continue_pipeline' => true,
                'run_preprocessing' => false,
                'prefer_full_image' => true,
                'prefer_roi' => false,
                'fallback_to_analyzer' => true,
            ],
            'warnings' => [$warning],
        ];
    }
}
