<?php

namespace Tests\Feature;

use Tests\TestCase;

class AbsensiImportPipelineTest extends TestCase
{
    public function test_pipeline_services_can_be_resolved_with_mock_provider_enabled(): void
    {
        config()->set('ocr.enable_pipeline_v2', true);
        config()->set('ocr.enable_mock_vision_provider', true);

        $pipeline = app(\App\Services\AttendanceImportPipelineService::class);
        $rules = app(\App\Services\AttendanceImportRules::class);
        $adapter = app(\App\Services\AttendanceImportAdapter::class);
        $mock = app(\App\Services\MockVisionProvider::class);

        $this->assertNotNull($pipeline);
        $this->assertNotNull($rules);
        $this->assertNotNull($adapter);
        $this->assertNotNull($mock);

        $universal = $adapter->buildUniversalSkeleton([
            'provider' => 'mock_vision',
            'decision' => [
                'can_continue' => true,
                'status' => \App\Services\AttendanceImportRules::STATUS_SUCCESS,
                'code' => null,
                'message' => 'ok',
                'next_provider' => 'mock_vision',
                'correlation_id' => 'IMP-20260804-000001',
                'timestamp' => now()->toIso8601String(),
            ],
            'meta' => [
                'correlation_id' => 'IMP-20260804-000001',
            ],
        ]);

        $legacy = $adapter->toLegacyPreview($universal, 'sample text', 'tmp/file.jpg', 1, 1, 8, 2026, 'mock_vision');

        $this->assertSame('IMP-20260804-000001', $legacy['correlation_id']);
        $this->assertArrayHasKey('decision_report', $legacy);
        $this->assertArrayHasKey('universal_json', $legacy);
    }
}
