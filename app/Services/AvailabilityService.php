<?php

namespace App\Services;

use App\Services\Providers\AvailabilityProvider;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class AvailabilityService
{
    public function __construct(
        protected AvailabilityProvider $provider,
        protected CacheRepository $cache,
        protected string $cacheKey,
        protected int $cacheTtl,
        protected int $staleAfter,
        protected string $fallbackMessage,
        protected string $timezone
    ) {
        $this->cacheTtl = max(60, $this->cacheTtl);
        $this->staleAfter = max($this->cacheTtl, $this->staleAfter);
    }

    public function getAvailability(bool $forceRefresh = false): array
    {
        if ($forceRefresh) {
            return $this->refresh();
        }

        $payload = $this->cache->get($this->cacheKey);

        if (!$payload) {
            return $this->refresh();
        }

        return $this->decorate($payload);
    }

    public function refresh(): array
    {
        try {
            $response = $this->provider->fetch();
            $now = CarbonImmutable::now($this->timezone);
            $payload = [
                'status' => $response['status'] ?? 'ok',
                'slots' => $this->normalizeSlots($response['slots'] ?? []),
                'updated_at' => $response['updated_at'] ?? $now->toIso8601String(),
            ];

            $this->cache->put($this->cacheKey, $payload, $this->cacheTtl);

            return $this->decorate($payload);
        } catch (\Throwable $exception) {
            Log::warning('Availability provider error', [
                'message' => $exception->getMessage(),
            ]);
        }

        $cached = $this->cache->get($this->cacheKey);

        if ($cached) {
            return $this->decorate($cached, fallback: true);
        }

        return $this->fallbackPayload();
    }

    public function isStale(?string $timestamp): bool
    {
        if (!$timestamp) {
            return true;
        }

        $now = CarbonImmutable::now($this->timezone);
        $updatedAt = CarbonImmutable::parse($timestamp)->setTimezone($this->timezone);

        $elapsed = $updatedAt->diffInRealSeconds($now, false);

        return $elapsed > $this->staleAfter;
    }

    protected function normalizeSlots(array $slots): array
    {
        return array_values(array_filter(array_map(function ($slot) {
            $date = Arr::get($slot, 'date');
            $available = (int) Arr::get($slot, 'available', 0);

            if (!$date) {
                return null;
            }

            return [
                'date' => $date,
                'available' => max(0, $available),
            ];
        }, $slots)));
    }

    protected function decorate(array $payload, bool $fallback = false): array
    {
        $updatedAt = $payload['updated_at'] ?? null;

        return [
            'status' => $payload['status'] ?? 'ok',
            'slots' => $payload['slots'] ?? [],
            'updated_at' => $updatedAt,
            'is_stale' => $this->isStale($updatedAt),
            'fallback_message' => $this->fallbackMessage,
            'fallback_used' => $fallback,
        ];
    }

    protected function fallbackPayload(): array
    {
        return [
            'status' => 'unavailable',
            'slots' => [],
            'updated_at' => null,
            'is_stale' => true,
            'fallback_message' => $this->fallbackMessage,
            'fallback_used' => true,
        ];
    }
}
