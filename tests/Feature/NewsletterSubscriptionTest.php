<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('phase6')]
class NewsletterSubscriptionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Session::start();

        config([
            'services.mailchimp.key' => 'test-key',
            'services.mailchimp.list_id' => 'test-list',
            'services.mailchimp.endpoint' => 'https://usX.api.mailchimp.com/3.0',
        ]);
    }

    public function test_successful_subscription_dispatches_success_event(): void
    {
        Http::fake([
            '*api.mailchimp.com/3.0/*' => Http::response([], 200),
        ]);

        $response = $this->post(route('newsletter.subscribe'), [
            '_token' => csrf_token(),
            'email' => 'caregiver@example.com',
        ]);

        $response->assertSessionHas('newsletter_status');
        $response->assertSessionHas('analytics.events', function ($events) {
            return collect($events)->contains(function ($event) {
                return ($event['name'] ?? null) === 'mailchimp.success';
            });
        });
    }

    public function test_failed_subscription_dispatches_failure_event(): void
    {
        Http::fake([
            '*api.mailchimp.com/3.0/*' => Http::response('error', 500),
        ]);

        $response = $this->from('/')->post(route('newsletter.subscribe'), [
            '_token' => csrf_token(),
            'email' => 'caregiver@example.com',
        ]);

        $response->assertSessionHas('newsletter_error');
        $response->assertSessionHas('analytics.events', function ($events) {
            return collect($events)->contains(function ($event) {
                return ($event['name'] ?? null) === 'mailchimp.failure';
            });
        });
    }
}
