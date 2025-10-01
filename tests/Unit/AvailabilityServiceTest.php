<?php

namespace Tests\Unit;

use App\Services\AvailabilityService;
use App\Services\Providers\AvailabilityProvider;
use Carbon\CarbonImmutable;
use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class AvailabilityServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['cache.default' => 'array']);
    }

    protected function makeCache(): CacheRepository
    {
        return new CacheRepository(new ArrayStore());
    }

    public function test_refresh_caches_and_returns_slots(): void
    {
        CarbonImmutable::setTestNow('2025-10-01 08:00:00', 'Asia/Singapore');

        $provider = new class implements AvailabilityProvider {
            public function fetch(): array
            {
                return [
                    'status' => 'ok',
                    'slots' => [
                        ['date' => '2025-10-02', 'available' => 4],
                        ['date' => '2025-10-03', 'available' => 2],
                    ],
                    'updated_at' => '2025-10-01T08:00:00+08:00',
                ];
            }
        };

        $cache = $this->makeCache();

        $service = new AvailabilityService(
            provider: $provider,
            cache: $cache,
            cacheKey: 'availability.test',
            cacheTtl: 300,
            staleAfter: 600,
            fallbackMessage: 'Fallback message'
        );

        $result = $service->refresh();

        $this->assertSame('ok', $result['status']);
        $this->assertFalse($result['is_stale']);
        $this->assertSame(2, count($result['slots']));
        $this->assertFalse($result['fallback_used']);

        $cached = $service->getAvailability();
        $this->assertSame($result['slots'], $cached['slots']);
    }

    public function test_refresh_returns_fallback_when_provider_fails(): void
    {
        CarbonImmutable::setTestNow('2025-10-01 08:00:00', 'Asia/Singapore');

        $provider = new class implements AvailabilityProvider {
            public function fetch(): array
            {
                throw new \RuntimeException('Provider down');
            }
        };

        $cache = $this->makeCache();

        Log::spy();

        $service = new AvailabilityService(
            provider: $provider,
            cache: $cache,
            cacheKey: 'availability.failure',
            cacheTtl: 300,
            staleAfter: 600,
            fallbackMessage: 'Fallback message'
        );

        $result = $service->refresh();

        $this->assertSame('unavailable', $result['status']);
        $this->assertTrue($result['fallback_used']);
        $this->assertTrue($result['is_stale']);
        Log::shouldHaveReceived('warning')->once();
    }

    public function test_is_stale_detects_outdated_payload(): void
    {
        CarbonImmutable::setTestNow('2025-10-01 12:00:00', 'Asia/Singapore');

        $provider = new class implements AvailabilityProvider {
            public function fetch(): array
            {
                return [];
            }
        };

        $cache = $this->makeCache();

        $service = new AvailabilityService(
            provider: $provider,
            cache: $cache,
            cacheKey: 'availability.stale',
            cacheTtl: 60,
            staleAfter: 120,
            fallbackMessage: 'Fallback message'
        );

        $this->assertTrue($service->isStale('2025-10-01T11:57:59+08:00'));
        $this->assertFalse($service->isStale('2025-10-01T11:59:30+08:00'));
    }
}
