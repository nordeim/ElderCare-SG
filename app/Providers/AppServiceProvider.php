<?php

namespace App\Providers;

use App\Services\Providers\AvailabilityProvider;
use App\Services\Providers\MockAvailabilityProvider;
use App\Services\AvailabilityService;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AvailabilityProvider::class, function ($app) {
            $config = $app->make(ConfigRepository::class)->get('services.availability');
            $driver = $config['driver'] ?? 'mock';
            $timezone = $config['timezone'] ?? 'Asia/Singapore';

            // Only mock driver defined currently; extendable for real provider later.
            if ($driver === 'mock') {
                return new MockAvailabilityProvider(
                    windowDays: (int) ($config['mock']['window_days'] ?? 7),
                    weeklySlots: (int) ($config['mock']['weekly_slots'] ?? 18),
                    timezone: $timezone
                );
            }

            return new MockAvailabilityProvider(timezone: $timezone);
        });

        $this->app->singleton(AvailabilityService::class, function ($app) {
            $config = $app->make(ConfigRepository::class)->get('services.availability');
            $timezone = $config['timezone'] ?? 'Asia/Singapore';

            return new AvailabilityService(
                provider: $app->make(AvailabilityProvider::class),
                cache: $app->make(CacheRepository::class),
                cacheKey: 'availability.slots',
                cacheTtl: (int) ($config['cache_ttl'] ?? 300),
                staleAfter: (int) ($config['stale_after'] ?? 900),
                fallbackMessage: $config['messages']['fallback'] ?? 'We will confirm availability within 24 hours.',
                timezone: $timezone
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('availability', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}
