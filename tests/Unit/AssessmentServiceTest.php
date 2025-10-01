<?php

namespace Tests\Unit;

use App\Services\AssessmentService;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Mockery;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

class AssessmentServiceTest extends TestCase
{
    protected function makeService(?LoggerInterface $logger = null): AssessmentService
    {
        return new AssessmentService(app(ConfigRepository::class), $logger ?? app(LoggerInterface::class));
    }

    public function test_memory_care_segment_selected_when_dementia_flagged(): void
    {
        $answers = [
            'mobility' => 'assistance',
            'cognitive_support' => 'dementia',
        ];

        $segmentKey = $this->makeService()->determineSegmentKey($answers);

        $this->assertSame('memory_care', $segmentKey);
    }

    public function test_supportive_care_segment_matches_any_rule(): void
    {
        $answers = [
            'mobility' => 'full_support',
            'cognitive_support' => 'engaged',
            'health_considerations' => ['mobility_aids'],
        ];

        $segmentKey = $this->makeService()->determineSegmentKey($answers);

        $this->assertSame('supportive_care', $segmentKey);
    }

    public function test_fallback_segment_used_when_no_rules_match(): void
    {
        $answers = [
            'mobility' => null,
            'cognitive_support' => 'mild_changes',
            'transportation' => 'no',
            'caregiver_goals' => [],
        ];

        $segmentKey = $this->makeService()->determineSegmentKey($answers);

        $this->assertSame('exploration', $segmentKey);
    }

    public function test_create_summary_returns_segment_configuration(): void
    {
        $answers = [
            'mobility' => 'independent',
            'cognitive_support' => 'engaged',
        ];

        $summary = $this->makeService()->createSummary($answers);

        $this->assertSame('active_day', $summary['segment_key']);
        $this->assertIsArray($summary['segment']);
        $this->assertArrayHasKey('all', $summary['segment']);
    }

    public function test_log_outcome_writes_to_logger(): void
    {
        $logger = Mockery::mock(LoggerInterface::class);

        $logger->shouldReceive('info')
            ->once()
            ->withArgs(function (string $message, array $context): bool {
                return $message === 'Assessment outcome recorded'
                    && ($context['segment'] ?? null) === 'active_day'
                    && ($context['answers']['mobility'] ?? null) === 'independent'
                    && isset($context['timestamp']);
            });

        $service = $this->makeService($logger);

        $service->logOutcome([
            'segment_key' => 'active_day',
            'answers' => ['mobility' => 'independent'],
        ]);
    }
}
