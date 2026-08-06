<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AttendanceCellOcrEngine
{
    protected array $cellCache = [];

    /**
     * Modul 3.6:
     * OCR per-cell dengan scheduler internal.
     * Non-breaking, additive, dan fallback-first.
     */
    public function process(string $imagePath, array $tableMeta = [], array $cellMap = [], array $coordinateMap = []): array
    {
        $startedAt = microtime(true);
        $config = config('ocr.cell_ocr', []);

        if (!(bool) ($config['enabled'] ?? true)) {
            return $this->fallback('Cell OCR disabled by config.', round((microtime(true) - $startedAt) * 1000));
        }

        if (empty($coordinateMap['coordinates'] ?? [])) {
            return $this->fallback('Coordinate map unavailable for cell OCR.', round((microtime(true) - $startedAt) * 1000));
        }

        $queue = $this->buildOcrQueue($coordinateMap['coordinates'] ?? []);
        $queue = $this->prioritizeQueue($queue);

        if (empty($queue)) {
            return $this->fallback('No eligible cells for cell OCR.', round((microtime(true) - $startedAt) * 1000));
        }

        return $this->executeBatchOcr($imagePath, $tableMeta, $cellMap, $coordinateMap, $queue, $config, $startedAt);
    }

    protected function buildOcrQueue(array $coordinates): array
    {
        $queue = [];
        foreach ($coordinates as $coordinate) {
            $logicalType = $coordinate['logical_type'] ?? 'unknown';
            if (!in_array($logicalType, ['date', 'recap_A', 'recap_I', 'recap_S', 'attendance_symbol'], true)) {
                continue;
            }
            $bbox = [
                'x' => $coordinate['x'] ?? 0,
                'y' => $coordinate['y'] ?? 0,
                'width' => $coordinate['width'] ?? 0,
                'height' => $coordinate['height'] ?? 0,
            ];
            if (($bbox['width'] ?? 0) <= 0 || ($bbox['height'] ?? 0) <= 0) {
                continue;
            }

            $queue[] = [
                'cell_id' => $coordinate['cell_id'] ?? null,
                'logical_type' => $logicalType,
                'coordinate' => $coordinate,
                'state' => 'pending',
                'priority' => $this->resolvePriority($logicalType),
            ];
        }

        return $queue;
    }

    protected function prioritizeQueue(array $queue): array
    {
        usort($queue, function ($a, $b) {
            return $a['priority'] <=> $b['priority'];
        });

        return $queue;
    }

    protected function executeBatchOcr(string $imagePath, array $tableMeta, array $cellMap, array $coordinateMap, array $queue, array $config, float $startedAt): array
    {
        $fullImagePath = storage_path('app/' . $imagePath);
        if (!is_file($fullImagePath)) {
            return $this->fallback('Image file missing for batch OCR.', round((microtime(true) - $startedAt) * 1000));
        }

        $pythonPath = config('ocr.python_path', '');
        $scriptPath = base_path(config('ocr.ocr_script', 'scripts/ocr_attendance.py'));
        $tesseractPath = config('ocr.tesseract_path', '');
        if (!is_file($pythonPath) || !is_file($scriptPath)) {
            return $this->fallback('OCR backend unavailable for batch OCR.', round((microtime(true) - $startedAt) * 1000));
        }

        $payload = [
            'mode' => 'batch',
            'table_roi' => $tableMeta['table_box'] ?? null,
            'cell_map' => $cellMap,
            'coordinate_map' => $coordinateMap,
            'queue' => array_slice($queue, 0, $this->cellMaxParallelCells($config)),
            'config' => [
                'minimum_confidence' => $config['minimum_confidence'] ?? 80,
                'enable_bbox' => $config['enable_bbox'] ?? true,
                'enable_confidence' => $config['enable_confidence'] ?? true,
                'enable_debug' => $config['enable_debug'] ?? false,
                'normalization' => $config['normalization'] ?? [],
            ],
        ];

        $tempDirectory = $config['temp_directory'] ?? storage_path('app/temp');
        if (!is_dir($tempDirectory) && !@mkdir($tempDirectory, 0777, true) && !is_dir($tempDirectory)) {
            return $this->fallback('Unable to create temporary OCR payload directory.', round((microtime(true) - $startedAt) * 1000));
        }

        $payloadFile = $tempDirectory . DIRECTORY_SEPARATOR . 'ocr_payload_' . uniqid('', true) . '.json';
        $keepPayload = (bool) ($config['enable_debug'] ?? false) && (bool) ($config['keep_payload_when_debug'] ?? false);

        try {
            if (@file_put_contents($payloadFile, json_encode($payload, JSON_UNESCAPED_UNICODE)) === false) {
                return $this->fallback('Unable to write temporary OCR payload file.', round((microtime(true) - $startedAt) * 1000));
            }

            $cmd = escapeshellarg($pythonPath) . ' ' . escapeshellarg($scriptPath) . ' ' . escapeshellarg($fullImagePath);
            if ($tesseractPath) {
                $cmd .= ' ' . escapeshellarg($tesseractPath);
            }
            $cmd .= ' --mode batch --payload-file ' . escapeshellarg($payloadFile);

            $output = [];
            $exitCode = 0;
            set_time_limit(120);
            exec($cmd . ' 2>&1', $output, $exitCode);
            $jsonStr = implode("\n", $output);
            $decoded = json_decode($jsonStr, true);

            if (!is_array($decoded) || !($decoded['success'] ?? false)) {
                return $this->fallback('Batch cell OCR failed; legacy OCR fallback will be used.', round((microtime(true) - $startedAt) * 1000));
            }
        } finally {
            if (!$keepPayload && is_file($payloadFile)) {
                @unlink($payloadFile);
            }
        }

        $results = [];
        foreach (($decoded['cells'] ?? []) as $row) {
            $bbox = $row['bbox'] ?? [];
            $result = [
                'cell_id' => $row['cell_id'] ?? null,
                'logical_type' => $row['logical_type'] ?? 'unknown',
                'state' => !empty($row['text']) ? 'success' : 'fallback',
                'raw' => $row['raw'] ?? null,
                'text' => $row['text'] ?? null,
                'normalized' => $row['normalized'] ?? $this->normalizeText((string) ($row['text'] ?? ''), $config),
                'confidence' => (int) ($row['confidence'] ?? 0),
                'quality_score' => 0,
                'bbox' => $bbox,
                'geometry_source' => $row['geometry_source'] ?? (empty($bbox) ? 'fallback' : 'ocr'),
                'provider' => $row['provider'] ?? ($config['provider'] ?? 'local'),
                'duration_ms' => (int) ($row['duration_ms'] ?? 0),
                'warnings' => $row['warnings'] ?? [],
                'retried' => (bool) ($row['retried'] ?? false),
            ];
            $result['quality_score'] = $this->computeQualityScore($result, $bbox);
            $results[] = $result;
            if (!empty($result['cell_id'])) {
                $this->cellCache[$result['cell_id']] = $result;
            }
        }

        return $this->aggregateStatistics($results, round((microtime(true) - $startedAt) * 1000), $config, $decoded);
    }

    protected function normalizeText(string $text, array $config): ?string
    {
        $text = trim($text);
        if ($text === '') {
            return null;
        }

        $policy = config('ocr.cell_ocr.normalization', [
            '.' => 'H',
            ',' => 'H',
            '/' => 'H',
            '-' => 'H',
        ]);

        $char = strtoupper(mb_substr($text, 0, 1));
        return $policy[$char] ?? $char;
    }

    protected function computeQualityScore(array $result, array $cellBbox): float
    {
        $confidence = (float) ($result['confidence'] ?? 0);
        $bbox = $result['bbox'] ?? [];
        $bboxArea = max(1, (int) ($bbox['width'] ?? 0) * (int) ($bbox['height'] ?? 0));
        $cellArea = max(1, (int) ($cellBbox['width'] ?? 0) * (int) ($cellBbox['height'] ?? 0));
        $areaRatio = min(1.0, $bboxArea / $cellArea);
        $centered = 1.0;
        $retryPenalty = !empty($result['retried']) ? 0.90 : 1.0;
        $geometryBonus = (($result['geometry_source'] ?? '') === 'ocr') ? 1.0 : 0.85;

        return round((($confidence / 100) * 0.55 + $areaRatio * 0.25 + $centered * 0.20) * $retryPenalty * $geometryBonus, 4);
    }

    protected function aggregateStatistics(array $results, int $durationMs, array $config, array $decoded = []): array
    {
        $processed = count($results);
        $success = 0;
        $failed = 0;
        $retryCount = 0;
        $confidence = [];
        $quality = [];
        $fallbackUsed = false;

        foreach ($results as $row) {
            if (($row['state'] ?? '') === 'success') {
                $success++;
            } else {
                $failed++;
                $fallbackUsed = true;
            }
            if (!empty($row['retried'])) {
                $retryCount++;
            }
            if (isset($row['confidence'])) {
                $confidence[] = (float) $row['confidence'];
            }
            if (isset($row['quality_score'])) {
                $quality[] = (float) $row['quality_score'];
            }
        }

        Log::info('Attendance cell OCR summary', [
            'provider' => $config['provider'] ?? 'local',
            'cells_processed' => $processed,
            'cells_success' => $success,
            'cells_failed' => $failed,
            'average_confidence' => !empty($confidence) ? round(array_sum($confidence) / count($confidence), 2) : 0,
            'average_quality_score' => !empty($quality) ? round(array_sum($quality) / count($quality), 4) : 0,
            'duration_ms' => $durationMs,
            'retry_count' => $retryCount,
            'fallback_used' => $fallbackUsed,
        ]);

        return [
            'provider' => $decoded['provider'] ?? ($config['provider'] ?? 'local'),
            'cells_processed' => $decoded['cells_processed'] ?? $processed,
            'cells_success' => $decoded['cells_success'] ?? $success,
            'cells_failed' => $decoded['cells_failed'] ?? $failed,
            'average_confidence' => $decoded['average_confidence'] ?? (!empty($confidence) ? round(array_sum($confidence) / count($confidence), 2) : 0),
            'average_quality_score' => !empty($quality) ? round(array_sum($quality) / count($quality), 4) : 0,
            'duration_ms' => $decoded['duration_ms'] ?? $durationMs,
            'fallback_used' => $decoded['fallback_used'] ?? $fallbackUsed,
            'retry_count' => $decoded['retry_count'] ?? $retryCount,
            'cells' => $results,
        ];
    }

    protected function resolvePriority(string $logicalType): int
    {
        return match ($logicalType) {
            'attendance_status', 'date' => 1,
            'recap_A', 'recap_I', 'recap_S', 'recap_H' => 2,
            'attendance_header' => 3,
            default => 99,
        };
    }

    protected function cellMaxParallelCells(array $config): int
    {
        return min((int) ($config['max_parallel_cells'] ?? 64), 64);
    }

    protected function fallback(string $warning, int $durationMs): array
    {
        return [
            'provider' => config('ocr.cell_ocr.provider', 'local'),
            'cells_processed' => 0,
            'cells_success' => 0,
            'cells_failed' => 0,
            'average_confidence' => 0,
            'average_quality_score' => 0,
            'duration_ms' => $durationMs,
            'fallback_used' => true,
            'retry_count' => 0,
            'cells' => [],
            'warnings' => [$warning],
        ];
    }

    protected function cellDebugPolicy(): bool
    {
        return (bool) config('ocr.cell_ocr.enable_debug', false);
    }
}
