<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Tests\TestCase;

class AvailabilityEndpointTest extends TestCase
{
    public function test_endpoint_returns_json_payload(): void
    {
        $response = $this->getJson(route('availability.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'slots',
                'updated_at',
                'is_stale',
                'fallback_message',
                'fallback_used',
            ]);
    }

    public function test_rate_limiter_blocks_excessive_requests(): void
    {
        RateLimiter::for('availability', function () {
            return Limit::perMinute(1)->by('test');
        });

        $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.1']);

        $first = $this->getJson(route('availability.index'));
        $first->assertOk();

        $second = $this->getJson(route('availability.index'));
        $second->assertTooManyRequests();
    }
}
