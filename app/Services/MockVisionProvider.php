<?php

namespace App\Services;

class MockVisionProvider
{
    protected AttendanceImportAdapter $adapter;

    public function __construct(AttendanceImportAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Perubahan besar Tahap 2.5:
     * Mock provider untuk testing offline/regression dengan kontrak universal JSON
     * yang identik dengan provider asli.
     */
    public function parseFromImage(string $imagePath, int $bulan, int $tahun, array $context = []): array
    {
        $students = [
            [
                'row_number'  => 1,
                'nisn'        => '1234567890',
                'nomor_induk' => '001',
                'name'        => 'SISWA MOCK',
                'attendance'  => [
                    ['tanggal' => 1, 'status' => 'H', 'confidence' => 0.95],
                    ['tanggal' => 2, 'status' => 'I', 'confidence' => 0.92],
                ],
                'warnings'    => [],
                'confidence'  => 0.95,
            ],
        ];

        $universal = $this->adapter->buildUniversalSkeleton([
            'provider'       => 'mock_vision',
            'provider_model' => 'mock',
            'kelas'          => $context['kelas'] ?? '',
            'bulan'          => $bulan,
            'tahun'          => $tahun,
            'students'       => $students,
            'warnings'       => [],
            'confidence'     => [
                'provider' => 0.95,
                'ocr'      => 0.95,
                'matching' => 0,
                'overall'  => 0.95,
            ],
            'pipeline'       => [
                'classification' => AttendanceImportRules::STATUS_SUCCESS,
                'quality'        => AttendanceImportRules::STATUS_SUCCESS,
                'decision'       => AttendanceImportRules::STATUS_SUCCESS,
                'provider'       => AttendanceImportRules::STATUS_SUCCESS,
                'validation'     => AttendanceImportRules::STATUS_SKIPPED,
            ],
            'meta' => [
                'mock' => true,
                'image_path' => $imagePath,
            ],
        ]);

        return [
            'success'   => true,
            'data'      => [
                'siswa' => [
                    [
                        'no'             => 1,
                        'nisn'           => '1234567890',
                        'nama_ocr'       => 'SISWA MOCK',
                        'ketidakhadiran' => [
                            ['tanggal' => 2, 'status' => 'I', 'confidence' => 0.92],
                        ],
                        'warnings'       => [],
                        'confidence'     => 0.95,
                        'nomor_induk'    => '001',
                    ],
                ],
            ],
            'source'    => 'mock_vision',
            'universal' => $universal,
        ];
    }
}
