<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AttendanceImportPipelineService
{
    protected AIParserService $aiParserService;
    protected AttendanceImportRules $rules;
    protected MockVisionProvider $mockVisionProvider;
    protected AttendanceBenchmarkService $benchmarkService;
    protected AttendanceTemplateMatcher $templateMatcher;
    protected AttendanceTableDetector $tableDetector;
    protected AttendanceGridDetector $gridDetector;
    protected AttendanceCellMapper $cellMapper;
    protected AttendanceCoordinateMapper $coordinateMapper;
    protected AttendanceCellOcrEngine $cellOcrEngine;
    protected AttendanceStudentRowMatcher $studentRowMatcher;
    protected array $requestCache = [];

    /**
     * Perubahan besar Tahap 2:
     * Pipeline ini sekarang memiliki gatekeeper ringan untuk document classification,
     * image quality, decision engine, dan logging dasar sebelum provider AI dijalankan.
     */
    public function __construct(AIParserService $aiParserService, AttendanceImportRules $rules, MockVisionProvider $mockVisionProvider, AttendanceBenchmarkService $benchmarkService, AttendanceTemplateMatcher $templateMatcher, AttendanceTableDetector $tableDetector, AttendanceGridDetector $gridDetector, AttendanceCellMapper $cellMapper, AttendanceCoordinateMapper $coordinateMapper, AttendanceCellOcrEngine $cellOcrEngine, AttendanceStudentRowMatcher $studentRowMatcher)
    {
        $this->aiParserService = $aiParserService;
        $this->rules = $rules;
        $this->mockVisionProvider = $mockVisionProvider;
        $this->benchmarkService = $benchmarkService;
        $this->templateMatcher = $templateMatcher;
        $this->tableDetector = $tableDetector;
        $this->gridDetector = $gridDetector;
        $this->cellMapper = $cellMapper;
        $this->coordinateMapper = $coordinateMapper;
        $this->cellOcrEngine = $cellOcrEngine;
        $this->studentRowMatcher = $studentRowMatcher;
    }

    public function isEnabled(): bool
    {
        return (bool) config('ocr.enable_pipeline_v2', false);
    }

    public function parseAttendanceImage(string $imagePath, int $bulan, int $tahun, array $context = []): array
    {
        $startedAt = microtime(true);
        $correlationId = $context['correlation_id'] ?? $this->generateCorrelationId();
        $context['correlation_id'] = $correlationId;

        $this->logStage('Pipeline Start', AttendanceImportRules::STATUS_SUCCESS, $startedAt, [
            'correlation_id' => $correlationId,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        $template = $this->runTemplateMatcher($imagePath);
        $this->logStage('Template Matcher', $template['matched'] ? AttendanceImportRules::STATUS_SUCCESS : AttendanceImportRules::STATUS_WARNING, $startedAt, [
            'correlation_id' => $correlationId,
            'template_name' => $template['template_name'] ?? null,
            'template_confidence' => $template['confidence'] ?? null,
            'layout_confidence' => $template['layout_confidence'] ?? null,
            'alignment_score' => $template['alignment_score'] ?? null,
            'estimated_table_origin' => $template['anchors']['estimated_table_origin'] ?? null,
            'recommendation' => $template['recommendation'] ?? [],
            'fallback_reason' => $template['warnings'][0] ?? null,
        ]);

        $classification = $this->runDocumentClassification($imagePath);
        $this->logStage('Document Classification', $classification['status'], $startedAt, [
            'correlation_id' => $correlationId,
            'code' => $classification['code'] ?? null,
            'message' => $classification['message'] ?? null,
        ]);

        $table = $this->runTableDetector($imagePath, $template, []);
        $this->logStage('Table Detector', $table['detected'] ? AttendanceImportRules::STATUS_SUCCESS : AttendanceImportRules::STATUS_WARNING, $startedAt, [
            'correlation_id' => $correlationId,
            'table_confidence' => $table['table_confidence'] ?? null,
            'table_area_ratio' => $table['table_area_ratio'] ?? null,
            'table_box' => $table['table_box'] ?? [],
            'overlay_path' => $table['overlay_path'] ?? null,
            'fallback_reason' => $table['warnings'][0] ?? null,
        ]);

        $quality = $this->runImageQualityCheck($imagePath);
        $this->logStage('Image Quality', $quality['status'], $startedAt, [
            'correlation_id' => $correlationId,
            'overall' => $quality['overall'] ?? null,
            'code' => $quality['code'] ?? null,
            'message' => $quality['message'] ?? null,
        ]);

        $grid = $this->runGridDetector($imagePath, $table);
        $this->logStage('Grid Detector', $grid['grid_detected'] ? AttendanceImportRules::STATUS_SUCCESS : AttendanceImportRules::STATUS_WARNING, $startedAt, [
            'correlation_id' => $correlationId,
            'grid_confidence' => $grid['grid_confidence'] ?? null,
            'estimated_rows' => $grid['estimated_rows'] ?? null,
            'estimated_columns' => $grid['estimated_columns'] ?? null,
            'fallback_reason' => $grid['warnings'][0] ?? null,
        ]);

        $cellMap = $this->runCellMapper($grid, $table);
        $this->logStage('Cell Mapper', (($cellMap['confidence'] ?? 0) >= $this->rules->minimumCellConfidence()) ? AttendanceImportRules::STATUS_SUCCESS : AttendanceImportRules::STATUS_WARNING, $startedAt, [
            'correlation_id' => $correlationId,
            'cell_count' => count($cellMap['cells'] ?? []),
            'student_row_count' => count($cellMap['student_rows'] ?? []),
            'date_column_count' => count($cellMap['date_columns'] ?? []),
            'recap_column_count' => count($cellMap['recap_columns'] ?? []),
            'fallback_reason' => $cellMap['warnings'][0] ?? null,
        ]);

        $coordinateMap = $this->runCoordinateMapper($cellMap);
        $this->logStage('Coordinate Mapper', (($coordinateMap['statistics']['mapping_confidence'] ?? 0) >= $this->rules->coordinateMappingThreshold()) ? AttendanceImportRules::STATUS_SUCCESS : AttendanceImportRules::STATUS_WARNING, $startedAt, [
            'correlation_id' => $correlationId,
            'mapped_cells' => $coordinateMap['statistics']['mapped_cells'] ?? 0,
            'attendance_cells' => $coordinateMap['statistics']['attendance_cells'] ?? 0,
            'recap_cells' => $coordinateMap['statistics']['recap_cells'] ?? 0,
            'fallback_reason' => $coordinateMap['warnings'][0] ?? null,
        ]);

        $ocrMeta = $this->runCellOcr($imagePath, $table, $cellMap, $coordinateMap);
        $this->logStage('Cell OCR', (($ocrMeta['cells_success'] ?? 0) > 0) ? AttendanceImportRules::STATUS_SUCCESS : AttendanceImportRules::STATUS_WARNING, $startedAt, [
            'correlation_id' => $correlationId,
            'provider' => $ocrMeta['provider'] ?? null,
            'cells_processed' => $ocrMeta['cells_processed'] ?? 0,
            'cells_success' => $ocrMeta['cells_success'] ?? 0,
            'cells_failed' => $ocrMeta['cells_failed'] ?? 0,
            'fallback_used' => $ocrMeta['fallback_used'] ?? false,
        ]);

        $studentMapping = $this->runStudentRowMatcher($imagePath, $table, $grid, $cellMap, $coordinateMap, $ocrMeta);
        $this->logStage('Student Row Matcher', (($studentMapping['matched_rows'] ?? 0) > 0) ? AttendanceImportRules::STATUS_SUCCESS : AttendanceImportRules::STATUS_WARNING, $startedAt, [
            'correlation_id' => $correlationId,
            'rows_processed' => $studentMapping['rows_processed'] ?? 0,
            'rows_matched' => $studentMapping['matched_rows'] ?? 0,
            'rows_unmatched' => $studentMapping['unmatched_rows'] ?? 0,
            'fallback_used' => $studentMapping['recommendation']['fallback_required'] ?? false,
        ]);

        $providers = config('ocr.provider_priority', ['openrouter', 'gemini', 'local_ocr']);
        $nextProvider = config('ocr.enable_mock_vision_provider', false) ? 'mock_vision' : ($providers[0] ?? 'openrouter');
        $analysis = [
            'recommendation' => array_merge([
                'deskew' => ($quality['rotation'] ?? 0) > 3,
                'perspective' => (($quality['perspective'] ?? 'GOOD') !== 'GOOD'),
                'denoise' => (($quality['noise'] ?? 100) < 50),
                'enhance_contrast' => (($quality['contrast'] ?? 100) < 50),
                'adaptive_threshold' => (($quality['brightness'] ?? 100) < 40),
            ], $template['recommendation'] ?? []),
            'template' => $template,
        ];

        $decision = $this->runDecisionEngine($classification, $quality, $nextProvider, $correlationId, $analysis);

        $this->logStage('Decision', $decision['status'], $startedAt, [
            'correlation_id' => $correlationId,
            'code' => $decision['code'] ?? null,
            'message' => $decision['message'] ?? null,
            'next_provider' => $decision['next_provider'] ?? null,
        ]);

        if (!$decision['can_continue']) {
            $universal = app(AttendanceImportAdapter::class)->buildUniversalSkeleton([
                'provider' => 'none',
                'bulan' => $bulan,
                'tahun' => $tahun,
                'warnings' => $decision['reason'] ?? [],
                'image_quality' => $quality,
                'decision' => $decision,
                'pipeline' => [
                    'classification' => $classification['status'],
                    'quality' => $quality['status'],
                    'decision' => 'FAILED',
                    'provider' => 'SKIPPED',
                    'validation' => 'SKIPPED',
                ],
                'meta' => [
                    'correlation_id' => $correlationId,
                    'template' => $template,
                    'table' => $table,
                    'grid' => $grid,
                    'cell_map' => $cellMap,
                    'coordinate_map' => $coordinateMap,
                    'ocr' => $ocrMeta,
                    'student_mapping' => $studentMapping,
                    'document_classification' => $classification,
                    'decision' => $decision,
                ],
                'validation' => [],
                'confidence' => [
                    'provider' => 0,
                    'ocr' => 0,
                    'matching' => 0,
                    'overall' => 0,
                ],
            ]);

            Log::warning('Attendance pipeline stopped by gatekeeper.', [
                'correlation_id' => $correlationId,
                'stage' => 'Pipeline Finish',
                'status' => AttendanceImportRules::STATUS_FAILED,
                'duration_ms' => round((microtime(true) - $startedAt) * 1000),
                'decision' => $decision,
            ]);

            return [
                'success' => false,
                'error' => $this->buildDecisionMessage($decision),
                'source' => 'gatekeeper',
                'universal' => array_merge($universal, ['decision' => $decision]),
                'meta' => $universal['meta'],
            ];
        }

        $result = config('ocr.enable_mock_vision_provider', false)
            ? $this->mockVisionProvider->parseFromImage($imagePath, $bulan, $tahun, $context)
            : $this->aiParserService->parseFromImage($imagePath, $bulan, $tahun, $context);

        if (isset($result['universal']) && is_array($result['universal'])) {
            $result['universal']['image_quality'] = $quality;
            $result['universal']['decision'] = $decision;
            $result['universal']['recommendation'] = $analysis['recommendation'] ?? [];
            $result['universal']['pipeline'] = array_merge($result['universal']['pipeline'] ?? [], [
                'classification' => $classification['status'],
                'quality' => $quality['status'],
                'decision' => $decision['status'],
                'provider' => ($result['success'] ?? false) ? 'SUCCESS' : 'FAILED',
            ]);
            $result['universal']['meta']['template'] = $template;
            $result['universal']['meta']['table'] = $table;
            $result['universal']['meta']['grid'] = $grid;
            $result['universal']['meta']['cell_map'] = $cellMap;
            $result['universal']['meta']['coordinate_map'] = $coordinateMap;
            $result['universal']['meta']['ocr'] = $ocrMeta;
            $result['universal']['meta']['student_mapping'] = $studentMapping;
            $result['universal']['meta']['document_classification'] = $classification;
            $result['universal']['meta']['decision'] = $decision;
        }

        $this->logStage('Provider', ($result['success'] ?? false) ? AttendanceImportRules::STATUS_SUCCESS : AttendanceImportRules::STATUS_FAILED, $startedAt, [
            'correlation_id' => $correlationId,
            'provider' => $result['source'] ?? 'unknown',
        ]);

        $this->logStage('Pipeline Finish', ($result['success'] ?? false) ? AttendanceImportRules::STATUS_SUCCESS : AttendanceImportRules::STATUS_FAILED, $startedAt, [
            'correlation_id' => $correlationId,
            'provider' => $result['source'] ?? 'unknown',
            'confidence' => $result['universal']['confidence']['overall'] ?? null,
        ]);

        $this->benchmarkService->capture([
            'correlation_id' => $correlationId,
            'provider' => $result['source'] ?? 'unknown',
            'parse_mode' => $context['parse_mode'] ?? 'ai',
            'pipeline_status' => $result['universal']['decision']['status'] ?? (($result['success'] ?? false) ? AttendanceImportRules::STATUS_SUCCESS : AttendanceImportRules::STATUS_FAILED),
            'total_duration_ms' => round((microtime(true) - $startedAt) * 1000),
            'provider_duration_ms' => $result['universal']['meta']['elapsed_ms'] ?? null,
            'memory_usage_bytes' => memory_get_usage(true),
            'error_count' => ($result['success'] ?? false) ? 0 : 1,
            'universal' => $result['universal'] ?? [],
        ]);

        return $result;
    }

    protected function runTemplateMatcher(string $imagePath): array
    {
        $cacheKey = 'template:' . $imagePath;
        if (isset($this->requestCache[$cacheKey])) {
            return $this->requestCache[$cacheKey];
        }

        if (!(bool) config('ocr.enable_template_matcher', true)) {
            return $this->requestCache[$cacheKey] = [
                'matched' => false,
                'template_name' => null,
                'confidence' => 0.0,
                'anchors' => [],
                'alignment' => [],
                'recommendation' => [
                    'continue_pipeline' => true,
                    'run_preprocessing' => false,
                    'fallback_to_analyzer' => true,
                ],
                'warnings' => ['Template matcher disabled by feature flag.'],
            ];
        }

        return $this->requestCache[$cacheKey] = $this->templateMatcher->match($imagePath);
    }

    protected function runTableDetector(string $imagePath, array $template = [], array $analysis = []): array
    {
        $cacheKey = 'table:' . $imagePath;
        if (isset($this->requestCache[$cacheKey])) {
            return $this->requestCache[$cacheKey];
        }

        if (!(bool) config('ocr.enable_table_detector', true)) {
            return $this->requestCache[$cacheKey] = [
                'detected' => false,
                'table_box' => [],
                'table_confidence' => 0.0,
                'table_area_ratio' => 0.0,
                'recommendation' => [
                    'use_table_roi' => false,
                    'fallback_full_image' => true,
                    'continue_pipeline' => true,
                ],
                'warnings' => ['Table detector disabled by feature flag.'],
            ];
        }

        return $this->requestCache[$cacheKey] = $this->tableDetector->detect($imagePath, $template, $analysis);
    }

    protected function runGridDetector(string $imagePath, array $table = []): array
    {
        $cacheKey = 'grid:' . $imagePath;
        if (isset($this->requestCache[$cacheKey])) {
            return $this->requestCache[$cacheKey];
        }

        if (!(bool) config('ocr.enable_grid_detector', true)) {
            return $this->requestCache[$cacheKey] = [
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
                'recommendation' => $this->rules->gridFallbackPolicy(),
                'warnings' => ['Grid detector disabled by feature flag.'],
                'overlay_path' => null,
            ];
        }

        return $this->requestCache[$cacheKey] = $this->gridDetector->detect($imagePath, $table);
    }

    protected function runCellMapper(array $grid, array $table): array
    {
        $cacheKey = 'cellmap:' . md5(json_encode([$grid, $table]));
        if (isset($this->requestCache[$cacheKey])) {
            return $this->requestCache[$cacheKey];
        }

        if (!(bool) config('ocr.enable_cell_mapper', true)) {
            return $this->requestCache[$cacheKey] = [
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
                'recommendation' => $this->rules->cellFallbackPolicy(),
                'warnings' => ['Cell mapper disabled by feature flag.'],
            ];
        }

        return $this->requestCache[$cacheKey] = $this->cellMapper->map($grid, $table);
    }

    protected function runCoordinateMapper(array $cellMap): array
    {
        $cacheKey = 'coord:' . md5(json_encode($cellMap));
        if (isset($this->requestCache[$cacheKey])) {
            return $this->requestCache[$cacheKey];
        }

        if (!(bool) config('ocr.enable_coordinate_mapper', true)) {
            return $this->requestCache[$cacheKey] = [
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
                'recommendation' => $this->rules->coordinateFallbackPolicy(),
                'warnings' => ['Coordinate mapper disabled by feature flag.'],
            ];
        }

        return $this->requestCache[$cacheKey] = $this->coordinateMapper->map($cellMap);
    }

    protected function runCellOcr(string $imagePath, array $table, array $cellMap, array $coordinateMap): array
    {
        if (!(bool) config('ocr.enable_cell_ocr', true)) {
            return [
                'provider' => config('ocr.cell_ocr.provider', 'local'),
                'cells_processed' => 0,
                'cells_success' => 0,
                'cells_failed' => 0,
                'average_confidence' => 0,
                'average_quality_score' => 0,
                'duration_ms' => 0,
                'fallback_used' => true,
                'retry_count' => 0,
                'cells' => [],
                'warnings' => ['Cell OCR disabled by feature flag.'],
            ];
        }

        if (empty($coordinateMap['coordinates'] ?? [])) {
            return [
                'provider' => config('ocr.cell_ocr.provider', 'local'),
                'cells_processed' => 0,
                'cells_success' => 0,
                'cells_failed' => 0,
                'average_confidence' => 0,
                'average_quality_score' => 0,
                'duration_ms' => 0,
                'fallback_used' => true,
                'retry_count' => 0,
                'cells' => [],
                'warnings' => ['Coordinate map unavailable for cell OCR.'],
            ];
        }

        try {
            return $this->cellOcrEngine->process($imagePath, $table, $cellMap, $coordinateMap);
        } catch (\Throwable $e) {
            return [
                'provider' => config('ocr.cell_ocr.provider', 'local'),
                'cells_processed' => 0,
                'cells_success' => 0,
                'cells_failed' => 0,
                'average_confidence' => 0,
                'average_quality_score' => 0,
                'duration_ms' => 0,
                'fallback_used' => true,
                'retry_count' => 0,
                'cells' => [],
                'warnings' => ['Cell OCR failed and legacy OCR fallback will be used.'],
            ];
        }
    }

    protected function runStudentRowMatcher(string $imagePath, array $table, array $grid, array $cellMap, array $coordinateMap, array $ocrMeta): array
    {
        if (!(bool) config('ocr.enable_student_row_matcher', true)) {
            return [
                'rows_processed' => 0,
                'matched_rows' => 0,
                'unmatched_rows' => 0,
                'average_confidence' => 0,
                'average_quality' => 0,
                'students' => [],
                'recommendation' => [
                    'ready_for_validation' => false,
                    'manual_review' => false,
                    'fallback_required' => true,
                ],
                'warnings' => ['Student row matcher disabled by feature flag.'],
            ];
        }

        if (empty($coordinateMap['coordinates'] ?? []) || empty($ocrMeta['cells'] ?? [])) {
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
                'warnings' => ['Student row matcher prerequisites are incomplete.'],
            ];
        }

        try {
            return $this->studentRowMatcher->match($table, $grid, $cellMap, $coordinateMap, $ocrMeta);
        } catch (\Throwable $e) {
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
                'warnings' => ['Student row matcher failed and metadata fallback will be used.'],
            ];
        }
    }

    protected function runDocumentClassification(string $imagePath): array
    {
        $cacheKey = 'classification:' . $imagePath;
        if (isset($this->requestCache[$cacheKey])) {
            return $this->requestCache[$cacheKey];
        }

        if (!(bool) config('ocr.enable_document_classification', true)) {
            return $this->requestCache[$cacheKey] = [
                'status' => AttendanceImportRules::STATUS_SKIPPED,
                'type' => 'unknown',
                'code' => null,
                'message' => 'Document classification skipped.',
                'reasons' => [],
            ];
        }

        $fullPath = storage_path('app/' . $imagePath);
        if (!is_file($fullPath)) {
            return $this->requestCache[$cacheKey] = [
                'status' => AttendanceImportRules::STATUS_FAILED,
                'type' => 'missing_file',
                'code' => AttendanceImportRules::CODE_UNKNOWN_ERROR,
                'message' => 'Image file is missing.',
                'reasons' => [AttendanceImportRules::CODE_UNKNOWN_ERROR],
            ];
        }

        $name = strtoupper(pathinfo($fullPath, PATHINFO_FILENAME));
        $rules = $this->rules->documentRules();
        $rejectHits = 0;
        foreach ($rules['reject_keywords'] as $keyword) {
            if (str_contains($name, strtoupper($keyword))) {
                $rejectHits++;
            }
        }

        if ($rejectHits >= (int) ($rules['reject_hits'] ?? 2)) {
            return $this->requestCache[$cacheKey] = [
                'status' => AttendanceImportRules::STATUS_FAILED,
                'type' => 'non_attendance_document',
                'code' => AttendanceImportRules::CODE_DOCUMENT_NOT_SUPPORTED,
                'message' => 'Uploaded document is not an attendance book.',
                'reasons' => [AttendanceImportRules::CODE_DOCUMENT_NOT_SUPPORTED],
            ];
        }

        return $this->requestCache[$cacheKey] = [
            'status' => AttendanceImportRules::STATUS_SUCCESS,
            'type' => 'attendance_book',
            'code' => null,
            'message' => 'Attendance document detected.',
            'reasons' => [],
        ];
    }

    protected function runImageQualityCheck(string $imagePath): array
    {
        $cacheKey = 'quality:' . $imagePath;
        if (isset($this->requestCache[$cacheKey])) {
            return $this->requestCache[$cacheKey];
        }

        if (!(bool) config('ocr.enable_image_quality', true)) {
            return $this->requestCache[$cacheKey] = [
                'status' => AttendanceImportRules::STATUS_SKIPPED,
                'overall' => null,
                'code' => null,
                'message' => 'Image quality check skipped.',
                'reasons' => [],
            ];
        }

        $fullPath = storage_path('app/' . $imagePath);
        if (!is_file($fullPath)) {
            return $this->requestCache[$cacheKey] = [
                'status' => AttendanceImportRules::STATUS_FAILED,
                'overall' => 0,
                'code' => AttendanceImportRules::CODE_FILE_CORRUPTED,
                'message' => 'Image file is missing or corrupted.',
                'reasons' => [AttendanceImportRules::CODE_FILE_CORRUPTED],
            ];
        }

        $imageInfo = @getimagesize($fullPath);
        $size = @filesize($fullPath) ?: 0;
        $width = (int) ($imageInfo[0] ?? 0);
        $height = (int) ($imageInfo[1] ?? 0);
        $minSide = min($width, $height);
        $qualityRules = $this->rules->qualityRules();

        $blur = $minSide >= ($qualityRules['min_resolution'] ?? 900) ? 82 : ($minSide >= 700 ? 58 : 12);
        $brightness = $size > 800000 ? 88 : ($size > 300000 ? 62 : 8);
        $contrast = $size > 700000 ? 86 : ($size > 250000 ? 60 : 4);
        $rotation = $width > 0 && $height > 0 ? (abs($width - $height) > 400 ? 2 : 1) : 0;
        $noise = $size > 700000 ? 80 : ($size > 250000 ? 60 : 30);
        $perspective = $width >= $height ? 'GOOD' : 'WARNING';
        $resolution = $minSide >= ($qualityRules['min_resolution'] ?? 900) ? 'GOOD' : ($minSide >= 700 ? 'WARNING' : 'FAILED');

        $scores = [$blur, $brightness, $contrast, $noise, $resolution === 'GOOD' ? 90 : ($resolution === 'WARNING' ? 65 : 30)];
        $overall = (int) round(array_sum($scores) / count($scores));
        $reasons = [];
        $status = AttendanceImportRules::STATUS_SUCCESS;
        $code = null;
        $message = 'Image quality is acceptable.';

        if ($blur <= ($qualityRules['blur']['failed'] ?? 20)) { $status = AttendanceImportRules::STATUS_WARNING; $reasons[] = AttendanceImportRules::CODE_IMAGE_TOO_BLUR; }
        elseif ($blur <= ($qualityRules['blur']['warning'] ?? 55)) { $status = AttendanceImportRules::STATUS_WARNING; $reasons[] = AttendanceImportRules::CODE_SLIGHT_BLUR; }

        if ($brightness <= ($qualityRules['brightness']['failed'] ?? 10)) { $status = AttendanceImportRules::STATUS_WARNING; $reasons[] = AttendanceImportRules::CODE_LOW_BRIGHTNESS; }
        elseif ($brightness <= ($qualityRules['brightness']['warning'] ?? 35)) { $status = AttendanceImportRules::STATUS_WARNING; $reasons[] = AttendanceImportRules::CODE_LOW_BRIGHTNESS; }

        if ($contrast <= ($qualityRules['contrast']['failed'] ?? 5)) { $status = AttendanceImportRules::STATUS_WARNING; $reasons[] = AttendanceImportRules::CODE_LOW_CONTRAST; }
        elseif ($contrast <= ($qualityRules['contrast']['warning'] ?? 25)) { $status = AttendanceImportRules::STATUS_WARNING; $reasons[] = AttendanceImportRules::CODE_LOW_CONTRAST; }

        if ($resolution === 'FAILED') { $status = AttendanceImportRules::STATUS_FAILED; $reasons[] = AttendanceImportRules::CODE_RESOLUTION_TOO_SMALL; }
        elseif ($resolution === 'WARNING') { $status = AttendanceImportRules::STATUS_WARNING; $reasons[] = AttendanceImportRules::CODE_LOW_RESOLUTION; }

        if (!empty($reasons)) {
            $code = $reasons[0];
            $message = 'Image quality warning or failure detected.';
        }

        return $this->requestCache[$cacheKey] = [
            'status' => $status,
            'blur' => $blur,
            'brightness' => $brightness,
            'contrast' => $contrast,
            'rotation' => $rotation,
            'perspective' => $perspective,
            'resolution' => $resolution,
            'noise' => $noise,
            'overall' => $overall,
            'code' => $code,
            'message' => $message,
            'reasons' => array_values(array_unique($reasons)),
        ];
    }

    protected function runDecisionEngine(array $classification, array $quality, string $nextProvider, string $correlationId, array $analysis = []): array
    {
        if (!(bool) config('ocr.enable_decision_engine', true)) {
            return [
                'can_continue' => true,
                'status' => AttendanceImportRules::STATUS_SKIPPED,
                'code' => null,
                'message' => 'Decision engine skipped.',
                'reason' => [],
                'next_provider' => $nextProvider,
                'correlation_id' => $correlationId,
                'timestamp' => now()->toIso8601String(),
            ];
        }

        $decision = $this->rules->shouldContinue($classification, $quality, $nextProvider, $analysis);
        $decision['correlation_id'] = $correlationId;
        $decision['timestamp'] = now()->toIso8601String();

        return $decision;
    }

    protected function buildDecisionMessage(array $decision): string
    {
        return match ($decision['code'] ?? null) {
            AttendanceImportRules::CODE_DOCUMENT_NOT_SUPPORTED => 'Dokumen yang diunggah bukan buku absensi. Silakan unggah foto buku absensi MI Nurul Ulum.',
            AttendanceImportRules::CODE_IMAGE_TOO_BLUR => 'Gambar terlalu buram untuk diproses.',
            AttendanceImportRules::CODE_LOW_BRIGHTNESS => 'Gambar terlalu gelap untuk diproses.',
            AttendanceImportRules::CODE_LOW_CONTRAST => 'Kontras gambar terlalu rendah untuk diproses.',
            AttendanceImportRules::CODE_LOW_RESOLUTION => 'Resolusi gambar terlalu rendah untuk diproses.',
            AttendanceImportRules::CODE_ROTATION_TOO_HIGH => 'Rotasi gambar terlalu tinggi untuk diproses.',
            AttendanceImportRules::CODE_PERSPECTIVE_BAD => 'Perspektif gambar terlalu buruk untuk diproses.',
            default => 'Proses dihentikan oleh decision engine.',
        };
    }

    protected function generateCorrelationId(): string
    {
        return 'IMP-' . now()->format('Ymd') . '-' . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    protected function logStage(string $stage, string $status, float $startedAt, array $context = []): void
    {
        Log::info('Attendance pipeline stage', array_merge([
            'stage' => $stage,
            'status' => $status,
            'duration_ms' => round((microtime(true) - $startedAt) * 1000),
        ], $context));
    }
}
