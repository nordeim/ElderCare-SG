<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class BookingService
{
    public function bookingUrl(): string
    {
        return config('services.booking.url', 'https://calendly.com/eldercare-sg');
    }

    public function logClick(string $context = 'hero'): void
    {
        Log::info('Booking CTA engaged', [
            'context' => $context,
            'timestamp' => now()->toISOString(),
        ]);
    }
}
