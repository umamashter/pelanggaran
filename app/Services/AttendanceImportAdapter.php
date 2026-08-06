<?php

namespace App\Services;

use Carbon\Carbon;

class AttendanceImportAdapter
{
    /**
     * Perubahan besar Tahap 1:
     * Adapter ini menjaga backward compatibility dengan mengubah universal JSON
     * pipeline v2 menjadi payload legacy yang masih dipakai controller/view lama.
     */
    public function toLegacyPreview(array $universal, string $ocrText, ?string $fotoPath, int $kelasId, int $tahunAjaranId, int $bulan, int $tahun, string $parseSource): array
    {
        return [
            'kelas_id'        => $kelasId,
            'tahun_ajaran_id' => $tahunAjaranId,
            'bulan'           => $bulan,
            'tahun'           => $tahun,
            'foto_path'       => $fotoPath,
            'ocr_text'        => $ocrText,
            'parser_meta'     => $universal['meta'] ?? [],
            'parse_source'    => $parseSource,
            'ai_warning'      => $universal['warnings'][0] ?? null,
            'correlation_id'  => $universal['meta']['correlation_id'] ?? ($universal['decision']['correlation_id'] ?? null),
            'decision_report' => $universal['decision'] ?? null,
            'universal_json'  => $universal,
        ];
    }

    public function buildLegacyAiResult(array $universal, string $source, ?string $warning = null, ?string $aiError = null): array
    {
        $students = $universal['students'] ?? [];

        $legacyStudents = array_map(function (array $student, int $index) {
            return [
                'no'             => $student['row_number'] ?? ($index + 1),
                'nisn'           => $student['nisn'] ?? null,
                'nama_ocr'       => $student['name'] ?? '',
                'ketidakhadiran' => $student['attendance'] ?? [],
                'warnings'       => $student['warnings'] ?? [],
                'confidence'     => $student['confidence'] ?? null,
                'nomor_induk'    => $student['nomor_induk'] ?? null,
            ];
        }, $students, array_keys($students));

        return [
            'success' => true,
            'data'    => [
                'siswa' => $legacyStudents,
            ],
            'source'    => $source,
            'warning'   => $warning,
            'ai_error'  => $aiError,
            'universal' => $universal,
        ];
    }

    public function buildUniversalSkeleton(array $context = []): array
    {
        return [
            'schema_version'  => '1.0',
            'pipeline_version'=> '2.0',
            'provider'        => $context['provider'] ?? 'unknown',
            'provider_model'  => $context['provider_model'] ?? '',
            'processed_at'    => Carbon::now()->toIso8601String(),
            'kelas'           => $context['kelas'] ?? '',
            'bulan'           => $context['bulan'] ?? null,
            'tahun'           => $context['tahun'] ?? null,
            'students'        => $context['students'] ?? [],
            'warnings'        => $context['warnings'] ?? [],
            'confidence'      => $context['confidence'] ?? [
                'provider' => 0,
                'ocr'      => 0,
                'matching' => 0,
                'overall'  => 0,
            ],
            'validation'      => $context['validation'] ?? [],
            'image_quality'   => $context['image_quality'] ?? [],
            'decision'        => $context['decision'] ?? null,
            'pipeline'        => $context['pipeline'] ?? [
                'classification' => AttendanceImportRules::STATUS_SKIPPED,
                'quality'        => AttendanceImportRules::STATUS_SKIPPED,
                'preprocess'     => AttendanceImportRules::STATUS_SKIPPED,
                'vision'         => AttendanceImportRules::STATUS_SKIPPED,
                'validation'     => AttendanceImportRules::STATUS_SKIPPED,
            ],
            'meta'            => $context['meta'] ?? [],
        ];
    }
}
