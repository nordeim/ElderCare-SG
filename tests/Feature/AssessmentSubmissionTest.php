<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AssessmentSubmissionTest extends TestCase
{
    public function test_assessment_submission_returns_segment_key(): void
    {
        Log::spy();

        $payload = [
            'answers' => [
                'mobility' => 'independent',
                'cognitive_support' => 'engaged',
                'support_frequency' => 'weekdays',
            ],
        ];

        $response = $this->postJson(route('assessment.submit'), $payload);

        $response->assertSuccessful()
            ->assertJsonFragment([
                'segment_key' => 'active_day',
            ]);

        Log::shouldHaveReceived('info')
            ->withArgs(function (string $message, array $context): bool {
                return $message === 'Assessment outcome recorded'
                    && $context['segment'] === 'active_day';
            });
    }

    public function test_validation_errors_return_422(): void
    {
        $response = $this->postJson(route('assessment.submit'), [
            'answers' => [
                'mobility' => 'invalid-value',
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['answers.mobility']);
    }
}
