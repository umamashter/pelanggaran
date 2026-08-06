<?php

namespace App\Services;

class AttendanceImportRules
{
    public const STATUS_SUCCESS = 'SUCCESS';
    public const STATUS_WARNING = 'WARNING';
    public const STATUS_FAILED = 'FAILED';
    public const STATUS_SKIPPED = 'SKIPPED';

    public const CODE_DOCUMENT_NOT_SUPPORTED = 'DOCUMENT_NOT_SUPPORTED';
    public const CODE_IMAGE_TOO_BLUR = 'IMAGE_TOO_BLUR';
    public const CODE_LOW_BRIGHTNESS = 'LOW_BRIGHTNESS';
    public const CODE_LOW_CONTRAST = 'LOW_CONTRAST';
    public const CODE_LOW_RESOLUTION = 'LOW_RESOLUTION';
    public const CODE_ROTATION_TOO_HIGH = 'ROTATION_TOO_HIGH';
    public const CODE_PERSPECTIVE_BAD = 'PERSPECTIVE_BAD';
    public const CODE_PROVIDER_TIMEOUT = 'PROVIDER_TIMEOUT';
    public const CODE_OCR_FAILED = 'OCR_FAILED';
    public const CODE_UNKNOWN_ERROR = 'UNKNOWN_ERROR';
    public const CODE_INVALID_IMAGE = 'INVALID_IMAGE';
    public const CODE_EMPTY_IMAGE = 'EMPTY_IMAGE';
    public const CODE_FILE_CORRUPTED = 'FILE_CORRUPTED';
    public const CODE_RESOLUTION_TOO_SMALL = 'RESOLUTION_TOO_SMALL';
    public const CODE_UNREADABLE_IMAGE = 'UNREADABLE_IMAGE';
    public const CODE_SLIGHT_BLUR = 'SLIGHT_BLUR';
    public const CODE_MINOR_ROTATION = 'MINOR_ROTATION';
    public const CODE_MINOR_PERSPECTIVE = 'MINOR_PERSPECTIVE';
    public const CODE_SHADOW_DETECTED = 'SHADOW_DETECTED';
    public const CODE_UNEVEN_LIGHTING = 'UNEVEN_LIGHTING';
    public const CODE_DESKEW_RECOMMENDED = 'DESKEW_RECOMMENDED';
    public const CODE_PERSPECTIVE_CORRECTION_RECOMMENDED = 'PERSPECTIVE_CORRECTION_RECOMMENDED';
    public const CODE_DENOISE_RECOMMENDED = 'DENOISE_RECOMMENDED';
    public const CODE_CONTRAST_ENHANCEMENT_RECOMMENDED = 'CONTRAST_ENHANCEMENT_RECOMMENDED';
    public const CODE_THRESHOLD_RECOMMENDED = 'THRESHOLD_RECOMMENDED';
    /**
     * Perubahan besar Tahap 2:
     * Rule engine sederhana untuk gatekeeper sebelum provider AI dijalankan.
     * Dibuat terpusat agar threshold/decision tidak tersebar di controller/service lain.
     */
    public function documentRules(): array
    {
        return config('ocr.document_rules', [
            'attendance_keywords' => ['ABSENSI', 'SISWA', 'KELAS', 'BULAN', 'TAHUN', 'MI', 'NURUL', 'ULUM'],
            'reject_keywords'     => ['RAPORT', 'RAPOR', 'KARTU TANDA PENDUDUK', 'KTP', 'IJAZAH', 'SURAT', 'NILAI'],
            'min_attendance_hits' => 2,
            'reject_hits'         => 2,
        ]);
    }

    public function templateRules(): array
    {
        return config('ocr.template_matching', [
            'enabled' => true,
            'minimum_confidence' => 0.70,
            'minimum_anchor_score' => 0.65,
            'minimum_alignment_score' => 0.65,
            'template_tolerance' => 0.35,
            'matching_mode' => 'heuristic',
            'allow_fallback' => true,
            'expected_orientation' => 'landscape',
            'target_aspect_ratio' => 1.414,
        ]);
    }

    public function tableRules(): array
    {
        return config('ocr.table_detection', [
            'enabled' => true,
            'minimum_confidence' => 0.70,
            'minimum_table_area' => 0.30,
            'minimum_border_density' => 0.05,
            'minimum_line_density' => 0.03,
            'table_detection_mode' => 'heuristic',
            'allow_fallback' => true,
            'save_debug_overlay' => false,
            'debug_root' => 'storage/app/ocr/debug',
            'expected_table_top_ratio' => 0.22,
            'expected_table_left_ratio' => 0.08,
            'expected_table_width_ratio' => 0.84,
            'expected_table_height_ratio' => 0.60,
        ]);
    }

    public function minimumGridConfidence(): float
    {
        return (float) (config('ocr.grid_detection.minimum_confidence', 0.45));
    }

    public function expectedGridRows(): int
    {
        return (int) (config('ocr.grid_detection.expected_rows', 35));
    }

    public function expectedGridColumns(): int
    {
        return (int) (config('ocr.grid_detection.expected_columns', 36));
    }

    public function gridFallbackPolicy(): array
    {
        return [
            'fallback_table_roi' => true,
            'fallback_full_image' => false,
            'continue' => true,
        ];
    }

    public function minimumCellConfidence(): float
    {
        return (float) (config('ocr.cell_mapping.minimum_confidence', 0.50));
    }

    public function expectedStudentRows(): int
    {
        return (int) (config('ocr.cell_mapping.expected_student_rows', 40));
    }

    public function expectedDateColumns(): int
    {
        return (int) (config('ocr.cell_mapping.expected_date_columns', 31));
    }

    public function expectedRecapColumns(): int
    {
        return (int) (config('ocr.cell_mapping.expected_recap_columns', 3));
    }

    public function cellFallbackPolicy(): array
    {
        return [
            'ready_for_coordinate_mapper' => false,
            'fallback_grid_only' => true,
            'fallback_table_roi' => true,
            'manual_review' => true,
        ];
    }

    public function coordinateMappingThreshold(): float
    {
        return (float) (config('ocr.coordinate_mapping.mapping_confidence_threshold', 0.75));
    }

    public function coordinateFallbackPolicy(): array
    {
        return [
            'coordinate_mapping_good' => false,
            'coordinate_mapping_partial' => true,
            'coordinate_mapping_low_confidence' => true,
            'fallback_to_roi' => true,
        ];
    }

    public function coordinateWarningPolicy(): array
    {
        return [
            'coordinate_overlap_detected',
            'unknown_region',
            'missing_cells',
            'low_mapping_confidence',
        ];
    }

    public function cellOcrThreshold(): int
    {
        return (int) (config('ocr.cell_ocr.minimum_confidence', 80));
    }

    public function cellOcrFallbackPolicy(): array
    {
        return [
            'fallback_to_legacy_ocr' => true,
            'continue_on_partial_failure' => true,
            'retry_low_confidence_once' => true,
        ];
    }

    public function cellNormalizationPolicy(): array
    {
        return (array) config('ocr.cell_ocr.normalization', []);
    }

    public function cellMaxParallelCells(): int
    {
        return (int) (config('ocr.cell_ocr.max_parallel_cells', 64));
    }

    public function cellDebugPolicy(): bool
    {
        return (bool) (config('ocr.cell_ocr.enable_debug', false));
    }

    public function studentRowThreshold(): float
    {
        return (float) (config('ocr.student_row_matcher.minimum_confidence', 70));
    }

    public function studentRowFallbackPolicy(): array
    {
        return [
            'skip_on_missing_coordinate_map' => true,
            'skip_on_missing_ocr_meta' => true,
            'continue_on_failure' => true,
        ];
    }

    public function studentRowQualityPolicy(): bool
    {
        return (bool) (config('ocr.student_row_matcher.enable_quality_score', true));
    }

    public function studentRowDebugPolicy(): bool
    {
        return (bool) (config('ocr.student_row_matcher.enable_debug', false));
    }

    public function qualityRules(): array
    {
        return config('ocr.quality', [
            'min_score'            => 45,
            'warning_score'        => 70,
            'classification_threshold' => 60,
            'max_rotation'         => 8,
            'min_resolution'       => 900,
            'blur_warning'         => 55,
            'blur_fail'            => 35,
            'brightness_warning'   => 55,
            'brightness_fail'      => 35,
            'contrast_warning'     => 45,
            'contrast_fail'        => 25,
            'noise_warning'        => 60,
            'noise_fail'           => 35,
        ]);
    }

    public function decisionRules(): array
    {
        return config('ocr.decision_rules', [
            'stop_on_document_failed' => true,
            'stop_on_quality_failed'  => true,
        ]);
    }

    public function shouldReject(array $document, array $quality): array
    {
        $reasons = [];
        $decision = $this->decisionRules();

        if (($document['status'] ?? self::STATUS_SKIPPED) === self::STATUS_FAILED && ($decision['stop_on_document_failed'] ?? true)) {
            $reasons[] = $document['code'] ?? self::CODE_DOCUMENT_NOT_SUPPORTED;
        }

        if (($quality['status'] ?? self::STATUS_SKIPPED) === self::STATUS_FAILED && ($decision['stop_on_quality_failed'] ?? false)) {
            $reasons = array_merge($reasons, $quality['reasons'] ?? [self::CODE_UNKNOWN_ERROR]);
        }

        return [
            'can_continue' => empty($reasons),
            'status'       => empty($reasons) ? self::STATUS_SUCCESS : self::STATUS_FAILED,
            'category'     => empty($reasons) ? null : 'HARD_FAIL',
            'code'         => empty($reasons) ? null : ($reasons[0] ?? self::CODE_UNKNOWN_ERROR),
            'message'      => empty($reasons) ? 'Pipeline can continue.' : 'Pipeline blocked by hard fail condition.',
            'reason'       => array_values(array_unique($reasons)),
        ];
    }

    public function shouldWarn(array $document, array $quality): array
    {
        $reasons = [];

        if (($document['status'] ?? self::STATUS_SKIPPED) === self::STATUS_WARNING) {
            $reasons = array_merge($reasons, $document['reasons'] ?? []);
        }

        if (($quality['status'] ?? self::STATUS_SKIPPED) === self::STATUS_WARNING) {
            $reasons = array_merge($reasons, $quality['reasons'] ?? []);
        }

        return [
            'can_continue' => true,
            'status'       => empty($reasons) ? self::STATUS_SUCCESS : self::STATUS_WARNING,
            'category'     => empty($reasons) ? null : 'SOFT_WARNING',
            'code'         => empty($reasons) ? null : ($reasons[0] ?? self::CODE_UNKNOWN_ERROR),
            'message'      => empty($reasons) ? 'No warning detected.' : 'Image quality is suboptimal but still processable.',
            'reason'       => array_values(array_unique($reasons)),
        ];
    }

    public function shouldRecover(array $analysis): array
    {
        $recommendation = $analysis['recommendation'] ?? [];
        $codes = [];

        if (!empty($recommendation['deskew'])) $codes[] = self::CODE_DESKEW_RECOMMENDED;
        if (!empty($recommendation['perspective'])) $codes[] = self::CODE_PERSPECTIVE_CORRECTION_RECOMMENDED;
        if (!empty($recommendation['denoise'])) $codes[] = self::CODE_DENOISE_RECOMMENDED;
        if (!empty($recommendation['enhance_contrast'])) $codes[] = self::CODE_CONTRAST_ENHANCEMENT_RECOMMENDED;
        if (!empty($recommendation['adaptive_threshold'])) $codes[] = self::CODE_THRESHOLD_RECOMMENDED;

        return [
            'can_continue' => true,
            'status'       => empty($codes) ? self::STATUS_SUCCESS : self::STATUS_WARNING,
            'category'     => empty($codes) ? null : 'RECOVERABLE_WARNING',
            'code'         => empty($codes) ? null : $codes[0],
            'message'      => empty($codes) ? 'No preprocessing recommendation.' : 'Image can be improved automatically before provider execution.',
            'reason'       => $codes,
            'recommendation' => $recommendation,
        ];
    }

    public function shouldContinue(array $document, array $quality, string $nextProvider, array $analysis = []): array
    {
        $reject = $this->shouldReject($document, $quality);
        if (!$reject['can_continue']) {
            return [
                'can_continue'   => false,
                'status'         => self::STATUS_FAILED,
                'category'       => 'HARD_FAIL',
                'code'           => $reject['code'] ?? self::CODE_UNKNOWN_ERROR,
                'message'        => $reject['message'] ?? 'Pipeline rejected.',
                'reason'         => $reject['reason'],
                'next_provider'  => null,
                'recommendation' => $analysis['recommendation'] ?? [],
            ];
        }

        $recover = $this->shouldRecover($analysis);
        if (($recover['category'] ?? null) === 'RECOVERABLE_WARNING' && (config('ocr.decision_engine.enable_recoverable_warning', true))) {
            return [
                'can_continue'   => true,
                'status'         => self::STATUS_WARNING,
                'category'       => 'RECOVERABLE_WARNING',
                'code'           => $recover['code'],
                'message'        => $recover['message'],
                'reason'         => $recover['reason'],
                'next_provider'  => $nextProvider,
                'recommendation' => $recover['recommendation'],
            ];
        }

        $warn = $this->shouldWarn($document, $quality);

        return [
            'can_continue'   => true,
            'status'         => $warn['status'] === self::STATUS_WARNING ? self::STATUS_WARNING : self::STATUS_SUCCESS,
            'category'       => $warn['status'] === self::STATUS_WARNING ? 'SOFT_WARNING' : 'SUCCESS',
            'code'           => $warn['code'] ?? null,
            'message'        => $warn['message'] ?? 'Pipeline can continue.',
            'reason'         => $warn['reason'],
            'next_provider'  => $nextProvider,
            'recommendation' => $analysis['recommendation'] ?? [],
        ];
    }
}
