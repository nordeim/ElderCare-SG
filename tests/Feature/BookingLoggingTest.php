<?php

namespace Tests\Feature;

use App\Services\BookingService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Mockery;
use Tests\TestCase;

class BookingLoggingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Session::start();
    }

    public function test_booking_click_logs_context_and_dispatches_event(): void
    {
        Log::spy();
        Log::shouldReceive('channel')->with('analytics')->andReturnSelf();

        Route::middleware('web')->get('/__test-booking-log', function (BookingService $bookingService) {
            $bookingService->logClick('footer');

            return response()->noContent();
        });

        $response = $this
            ->withHeaders([
                'referer' => 'https://example.com/page',
                'User-Agent' => 'PHPUnit Test Agent',
            ])
            ->get('/__test-booking-log');

        $response->assertNoContent();

        Log::shouldHaveReceived('info')->once()->withArgs(function ($message, $context) {
            return $message === 'Booking CTA engaged'
                && ($context['context'] ?? null) === 'footer'
                && ($context['referer'] ?? null) === 'https://example.com/page'
                && ($context['user_agent'] ?? null) === 'PHPUnit Test Agent'
                && ! empty($context['request_id']);
        });

        $response->assertSessionHas('analytics.events', function ($events) {
            return collect($events)->contains(function ($event) {
                return ($event['name'] ?? null) === 'booking.click'
                    && ($event['detail']['context'] ?? null) === 'footer';
            });
        });
    }
}
