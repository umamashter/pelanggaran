<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AttendanceBenchmarkService
{
    /**
     * Observer pasif benchmark.
     * Tidak boleh mengubah hasil parsing, session, adapter, atau flow import.
     */
    public function capture(array $context): void
    {
        if (!(bool) config('ocr.benchmark.enabled', false)) {
            return;
        }

        try {
            $report = $this->buildReport($context);
            $paths = $this->preparePaths($report);

            if ((bool) config('ocr.benchmark.save_json', true)) {
                $this->writeJson($paths['json'], $report);
            }

            if ((bool) config('ocr.benchmark.save_csv', true)) {
                $this->writeCsv($paths['csv'], $report);
            }

            if ((bool) config('ocr.benchmark.save_summary_log', true)) {
                Log::info('Attendance benchmark summary', [
                    'correlation_id' => $report['correlation_id'],
                    'provider' => $report['provider'],
                    'pipeline_status' => $report['pipeline_status'],
                    'template_confidence' => $report['template']['template_confidence'] ?? null,
                    'layout_confidence' => $report['template']['layout_confidence'] ?? null,
                    'alignment_score' => $report['template']['alignment_score'] ?? null,
                    'table_confidence' => $report['table']['table_confidence'] ?? null,
                    'grid_confidence' => $report['grid']['grid_confidence'] ?? null,
                    'cell_mapping_confidence' => $report['cell_map']['cell_mapping_confidence'] ?? null,
                    'coordinate_mapping_confidence' => $report['coordinate_map']['mapping_confidence'] ?? null,
                    'ocr_average_confidence' => $report['ocr']['average_confidence'] ?? null,
                    'student_average_confidence' => $report['student_mapping']['average_confidence'] ?? null,
                    'processing_time_ms' => $report['pipeline']['total_duration_ms'],
                    'average_confidence' => $report['result']['average_confidence'],
                    'warnings' => $report['result']['warning_count'],
                    'errors' => $report['result']['error_count'],
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Attendance benchmark capture failed', [
                'correlation_id' => $context['correlation_id'] ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function buildReport(array $context): array
    {
        $universal = $context['universal'] ?? [];
        $students = $universal['students'] ?? [];
        $statuses = 0;
        $confidenceValues = [];

        foreach ($students as $student) {
            $attendance = $student['attendance'] ?? [];
            $statuses += count($attendance);
            if (isset($student['confidence']) && is_numeric($student['confidence'])) {
                $confidenceValues[] = (float) $student['confidence'];
            }
        }

        return [
            'schema_version' => config('ocr.benchmark.schema_version', '1.0'),
            'correlation_id' => $context['correlation_id'] ?? ($universal['meta']['correlation_id'] ?? null),
            'timestamp' => now()->toIso8601String(),
            'provider' => $context['provider'] ?? ($universal['provider'] ?? 'unknown'),
            'parse_mode' => $context['parse_mode'] ?? 'unknown',
            'pipeline_status' => $context['pipeline_status'] ?? ($universal['decision']['status'] ?? 'UNKNOWN'),
            'decision' => [
                'category' => $universal['decision']['category'] ?? null,
                'code' => $universal['decision']['code'] ?? null,
                'message' => $universal['decision']['message'] ?? null,
            ],
            'template' => [
                'matched' => $universal['meta']['template']['matched'] ?? null,
                'template_name' => $universal['meta']['template']['template_name'] ?? null,
                'template_confidence' => $universal['meta']['template']['confidence'] ?? null,
                'layout_confidence' => $universal['meta']['template']['layout_confidence'] ?? null,
                'alignment_score' => $universal['meta']['template']['alignment_score'] ?? null,
                'fallback_used' => (bool) ($universal['meta']['template']['recommendation']['fallback_to_analyzer'] ?? false),
            ],
            'table' => [
                'table_detected' => $universal['meta']['table']['detected'] ?? null,
                'table_confidence' => $universal['meta']['table']['table_confidence'] ?? null,
                'table_area' => $universal['meta']['table']['table_area_ratio'] ?? null,
                'fallback_used' => (bool) ($universal['meta']['table']['recommendation']['fallback_full_image'] ?? false),
                'overlay_path' => $universal['meta']['table']['overlay_path'] ?? null,
            ],
            'grid' => [
                'grid_detected' => $universal['meta']['grid']['grid_detected'] ?? null,
                'grid_confidence' => $universal['meta']['grid']['grid_confidence'] ?? null,
                'estimated_rows' => $universal['meta']['grid']['estimated_rows'] ?? null,
                'estimated_columns' => $universal['meta']['grid']['estimated_columns'] ?? null,
                'intersection_count' => $universal['meta']['grid']['intersection_count'] ?? null,
                'grid_quality' => $universal['meta']['grid']['grid_quality'] ?? null,
                'fallback_used' => (bool) ($universal['meta']['grid']['recommendation']['fallback_table_roi'] ?? false),
            ],
            'cell_map' => [
                'cell_count' => count($universal['meta']['cell_map']['cells'] ?? []),
                'student_row_count' => count($universal['meta']['cell_map']['student_rows'] ?? []),
                'date_column_count' => count($universal['meta']['cell_map']['date_columns'] ?? []),
                'recap_column_count' => count($universal['meta']['cell_map']['recap_columns'] ?? []),
                'cell_mapping_confidence' => $universal['meta']['cell_map']['confidence'] ?? null,
                'fallback_used' => (bool) ($universal['meta']['cell_map']['recommendation']['fallback_table_roi'] ?? false),
            ],
            'coordinate_map' => [
                'coordinate_mapping_enabled' => $universal['meta']['coordinate_map']['success'] ?? null,
                'mapped_cells' => $universal['meta']['coordinate_map']['statistics']['mapped_cells'] ?? null,
                'mapping_confidence' => $universal['meta']['coordinate_map']['statistics']['mapping_confidence'] ?? null,
                'attendance_cells' => $universal['meta']['coordinate_map']['statistics']['attendance_cells'] ?? null,
                'recap_cells' => $universal['meta']['coordinate_map']['statistics']['recap_cells'] ?? null,
                'header_cells' => $universal['meta']['coordinate_map']['statistics']['header_cells'] ?? null,
                'fallback_used' => (bool) ($universal['meta']['coordinate_map']['recommendation']['fallback_to_roi'] ?? false),
            ],
            'ocr' => [
                'ocr_provider' => $universal['meta']['ocr']['provider'] ?? null,
                'cells_processed' => $universal['meta']['ocr']['cells_processed'] ?? null,
                'cells_success' => $universal['meta']['ocr']['cells_success'] ?? null,
                'cells_failed' => $universal['meta']['ocr']['cells_failed'] ?? null,
                'average_confidence' => $universal['meta']['ocr']['average_confidence'] ?? null,
                'duration_ms' => $universal['meta']['ocr']['duration_ms'] ?? null,
                'fallback_used' => $universal['meta']['ocr']['fallback_used'] ?? null,
            ],
            'student_mapping' => [
                'rows_processed' => $universal['meta']['student_mapping']['rows_processed'] ?? null,
                'rows_matched' => $universal['meta']['student_mapping']['matched_rows'] ?? null,
                'rows_unmatched' => $universal['meta']['student_mapping']['unmatched_rows'] ?? null,
                'average_confidence' => $universal['meta']['student_mapping']['average_confidence'] ?? null,
                'average_quality' => $universal['meta']['student_mapping']['average_quality'] ?? null,
                'fallback_used' => $universal['meta']['student_mapping']['recommendation']['fallback_required'] ?? null,
            ],
            'image_quality' => [
                'brightness' => $universal['image_quality']['brightness'] ?? null,
                'contrast' => $universal['image_quality']['contrast'] ?? null,
                'blur' => $universal['image_quality']['blur'] ?? null,
                'noise' => $universal['image_quality']['noise'] ?? null,
            ],
            'pipeline' => [
                'total_duration_ms' => $context['total_duration_ms'] ?? null,
                'provider_duration_ms' => $context['provider_duration_ms'] ?? null,
                'memory_usage_bytes' => $context['memory_usage_bytes'] ?? memory_get_usage(true),
            ],
            'result' => [
                'student_count' => count($students),
                'status_count' => $statuses,
                'warning_count' => count($universal['warnings'] ?? []),
                'error_count' => $context['error_count'] ?? 0,
                'average_confidence' => !empty($confidenceValues) ? round(array_sum($confidenceValues) / count($confidenceValues), 4) : null,
            ],
            'validation' => [
                'expected_students' => null,
                'parsed_students' => count($students),
                'expected_statuses' => null,
                'parsed_statuses' => $statuses,
                'expected_dates' => null,
                'parsed_dates' => null,
                'accuracy' => null,
            ],
        ];
    }

    protected function preparePaths(array $report): array
    {
        $root = base_path(config('ocr.benchmark.report_root', 'tests/attendance-ai/reports'));
        if (!is_dir($root) && !@mkdir($root, 0777, true) && !is_dir($root)) {
            throw new \RuntimeException('Tidak dapat membuat folder benchmark reports.');
        }

        $timestamp = now()->format('Ymd_His');
        $provider = preg_replace('/[^A-Za-z0-9_-]/', '_', (string) ($report['provider'] ?? 'unknown'));
        $baseName = 'benchmark_' . $timestamp . '_' . $provider;

        return [
            'json' => $root . DIRECTORY_SEPARATOR . $baseName . '.json',
            'csv'  => $root . DIRECTORY_SEPARATOR . $baseName . '.csv',
        ];
    }

    protected function writeJson(string $path, array $report): void
    {
        $json = json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false || @file_put_contents($path, $json) === false) {
            throw new \RuntimeException('Gagal menulis benchmark JSON.');
        }
    }

    protected function writeCsv(string $path, array $report): void
    {
        $handle = @fopen($path, 'w');
        if (!$handle) {
            throw new \RuntimeException('Gagal membuka benchmark CSV.');
        }

        $rows = [
            ['schema_version', $report['schema_version']],
            ['correlation_id', $report['correlation_id']],
            ['timestamp', $report['timestamp']],
            ['provider', $report['provider']],
            ['parse_mode', $report['parse_mode']],
            ['pipeline_status', $report['pipeline_status']],
            ['decision_category', $report['decision']['category']],
            ['decision_code', $report['decision']['code']],
            ['decision_message', $report['decision']['message']],
            ['template_matched', $report['template']['matched'] ? '1' : '0'],
            ['template_name', $report['template']['template_name']],
            ['template_confidence', $report['template']['template_confidence']],
            ['layout_confidence', $report['template']['layout_confidence']],
            ['alignment_score', $report['template']['alignment_score']],
            ['template_fallback_used', $report['template']['fallback_used'] ? '1' : '0'],
            ['table_detected', $report['table']['table_detected'] ? '1' : '0'],
            ['table_confidence', $report['table']['table_confidence']],
            ['table_area', $report['table']['table_area']],
            ['table_fallback_used', $report['table']['fallback_used'] ? '1' : '0'],
            ['table_overlay_path', $report['table']['overlay_path']],
            ['grid_detected', $report['grid']['grid_detected'] ? '1' : '0'],
            ['grid_confidence', $report['grid']['grid_confidence']],
            ['grid_estimated_rows', $report['grid']['estimated_rows']],
            ['grid_estimated_columns', $report['grid']['estimated_columns']],
            ['grid_intersection_count', $report['grid']['intersection_count']],
            ['grid_quality', $report['grid']['grid_quality']],
            ['grid_fallback_used', $report['grid']['fallback_used'] ? '1' : '0'],
            ['cell_count', $report['cell_map']['cell_count']],
            ['student_row_count', $report['cell_map']['student_row_count']],
            ['date_column_count', $report['cell_map']['date_column_count']],
            ['recap_column_count', $report['cell_map']['recap_column_count']],
            ['cell_mapping_confidence', $report['cell_map']['cell_mapping_confidence']],
            ['cell_fallback_used', $report['cell_map']['fallback_used'] ? '1' : '0'],
            ['coordinate_mapping_enabled', $report['coordinate_map']['coordinate_mapping_enabled'] ? '1' : '0'],
            ['coordinate_mapped_cells', $report['coordinate_map']['mapped_cells']],
            ['coordinate_mapping_confidence', $report['coordinate_map']['mapping_confidence']],
            ['coordinate_attendance_cells', $report['coordinate_map']['attendance_cells']],
            ['coordinate_recap_cells', $report['coordinate_map']['recap_cells']],
            ['coordinate_header_cells', $report['coordinate_map']['header_cells']],
            ['coordinate_fallback_used', $report['coordinate_map']['fallback_used'] ? '1' : '0'],
            ['ocr_provider', $report['ocr']['ocr_provider']],
            ['ocr_cells_processed', $report['ocr']['cells_processed']],
            ['ocr_cells_success', $report['ocr']['cells_success']],
            ['ocr_cells_failed', $report['ocr']['cells_failed']],
            ['ocr_average_confidence', $report['ocr']['average_confidence']],
            ['ocr_duration_ms', $report['ocr']['duration_ms']],
            ['ocr_fallback_used', $report['ocr']['fallback_used'] ? '1' : '0'],
            ['student_rows_processed', $report['student_mapping']['rows_processed']],
            ['student_rows_matched', $report['student_mapping']['rows_matched']],
            ['student_rows_unmatched', $report['student_mapping']['rows_unmatched']],
            ['student_average_confidence', $report['student_mapping']['average_confidence']],
            ['student_average_quality', $report['student_mapping']['average_quality']],
            ['student_fallback_used', $report['student_mapping']['fallback_used'] ? '1' : '0'],
            ['brightness', $report['image_quality']['brightness']],
            ['contrast', $report['image_quality']['contrast']],
            ['blur', $report['image_quality']['blur']],
            ['noise', $report['image_quality']['noise']],
            ['total_duration_ms', $report['pipeline']['total_duration_ms']],
            ['provider_duration_ms', $report['pipeline']['provider_duration_ms']],
            ['memory_usage_bytes', $report['pipeline']['memory_usage_bytes']],
            ['student_count', $report['result']['student_count']],
            ['status_count', $report['result']['status_count']],
            ['warning_count', $report['result']['warning_count']],
            ['error_count', $report['result']['error_count']],
            ['average_confidence', $report['result']['average_confidence']],
        ];

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
    }
}
