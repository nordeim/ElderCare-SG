<?php

namespace App\Services;

use Illuminate\Contracts\Config\Repository as Config;
use Psr\Log\LoggerInterface;

class AssessmentService
{
    public function __construct(
        protected Config $config,
        protected LoggerInterface $logger,
    ) {
    }

    /**
     * @param  array<string, mixed>  $answers
     */
    public function determineSegmentKey(array $answers): string
    {
        $segments = $this->config->get('assessment.segments', []);
        $fallbackKey = 'exploration';

        if (! is_array($segments)) {
            return $fallbackKey;
        }

        foreach ($segments as $key => $segment) {
            if (! empty($segment['fallback'])) {
                $fallbackKey = $key;
                continue;
            }

            if ($this->matchesSegment($answers, $segment)) {
                return $key;
            }
        }

        return $fallbackKey;
    }

    /**
     * @param  array<string, mixed>  $answers
     */
    public function createSummary(array $answers): array
    {
        $segmentKey = $this->determineSegmentKey($answers);
        $segments = $this->config->get('assessment.segments', []);

        if (! is_array($segments)) {
            $segments = [];
        }

        return [
            'segment_key' => $segmentKey,
            'segment' => $segments[$segmentKey] ?? [],
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function logOutcome(array $payload): void
    {
        $this->logger->info('Assessment outcome recorded', [
            'segment' => $payload['segment_key'] ?? null,
            'answers' => $payload['answers'] ?? null,
            'meta' => $payload['meta'] ?? null,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $answers
     * @param  array<string, array<int, string>>  $rules
     */
    protected function matchesSegment(array $answers, array $segment): bool
    {
        $allRules = $segment['all'] ?? [];
        $anyRules = $segment['any'] ?? [];
        $legacyRules = $segment['rules'] ?? [];

        if (! empty($legacyRules) && ! $this->matchesAllRules($answers, $this->normalizeLegacyRules($legacyRules))) {
            return false;
        }

        if (! empty($allRules) && ! $this->matchesAllRules($answers, $allRules)) {
            return false;
        }

        if (! empty($anyRules) && ! $this->matchesAnyRules($answers, $anyRules)) {
            return false;
        }

        if (empty($allRules) && empty($anyRules) && empty($legacyRules)) {
            return false;
        }

        return true;
    }

    protected function matchesAllRules(array $answers, array $rules): bool
    {
        foreach ($rules as $rule) {
            if (! $this->matchesRule($answers, $rule)) {
                return false;
            }
        }

        return true;
    }

    protected function matchesAnyRules(array $answers, array $rules): bool
    {
        foreach ($rules as $rule) {
            if ($this->matchesRule($answers, $rule)) {
                return true;
            }
        }

        return false;
    }

    protected function matchesRule(array $answers, array $rule): bool
    {
        $field = $rule['field'] ?? null;
        $values = $rule['values'] ?? [];

        if (! $field || ! is_array($values) || empty($values)) {
            return false;
        }

        $answer = $answers[$field] ?? null;

        if (is_array($answer)) {
            return (bool) array_intersect($values, $answer);
        }

        return in_array($answer, $values, true);
    }

    protected function normalizeLegacyRules(array $rules): array
    {
        $normalized = [];

        foreach ($rules as $field => $values) {
            $normalized[] = [
                'field' => $field,
                'values' => is_array($values) ? $values : [$values],
            ];
        }

        return $normalized;
    }
}
