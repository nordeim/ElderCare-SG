<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BookingService
{
    public function bookingUrl(): string
    {
        return config('services.booking.url', 'https://calendly.com/eldercare-sg');
    }

    public function logClick(string $context = 'hero'): void
    {
        $payload = [
            'context' => $context,
            'timestamp' => now()->toISOString(),
            'referer' => request()->headers->get('referer'),
            'user_agent' => request()->userAgent(),
            'request_id' => (string) Str::uuid(),
        ];

        Log::info('Booking CTA engaged', $payload);

        session()->flash('analytics.events.booking', [
            'name' => 'booking.click',
            'detail' => $payload,
        ]);
    }
}
