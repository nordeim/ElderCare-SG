<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MailchimpService
{
    public function subscribe(string $email): bool
    {
        $config = config('services.mailchimp');

        if (! $config['key'] ?? null || ! $config['list_id'] ?? null) {
            Log::channel('analytics')->warning('Mailchimp disabled: missing configuration.', [
                'email' => $this->maskEmail($email),
            ]);

            return false;
        }

        try {
            $attemptId = (string) Str::uuid();

            $response = Http::withBasicAuth('anystring', $config['key'])
                ->retry(3, 200, function ($exception) use ($attemptId, $email) {
                    Log::channel('analytics')->warning('Mailchimp retry due to transport error.', [
                        'attempt_id' => $attemptId,
                        'email' => $this->maskEmail($email),
                        'exception' => $exception?->getMessage(),
                    ]);

                    return true;
                })
                ->post(sprintf('%s/lists/%s/members', rtrim($config['endpoint'], '/'), $config['list_id']), [
                    'email_address' => $email,
                    'status' => 'pending',
                ]);

            if ($response->successful()) {
                Log::channel('analytics')->info('Mailchimp subscription success.', [
                    'attempt_id' => $attemptId,
                    'email' => $this->maskEmail($email),
                    'status' => $response->status(),
                ]);

                $this->flashAnalyticsEvent('mailchimp.success', [
                    'status' => $response->status(),
                ]);

                return true;
            }

            Log::channel('analytics')->warning('Mailchimp subscription failed.', [
                'attempt_id' => $attemptId,
                'email' => $this->maskEmail($email),
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            $this->flashAnalyticsEvent('mailchimp.failure', [
                'status' => $response->status(),
            ]);
        } catch (\Throwable $exception) {
            Log::channel('analytics')->error('Mailchimp subscription error.', [
                'email' => $this->maskEmail($email),
                'message' => $exception->getMessage(),
            ]);

            $responseStatus = null;

            if ($exception instanceof RequestException && $exception->response) {
                $responseStatus = $exception->response->status();
            }

            $this->flashAnalyticsEvent('mailchimp.failure', [
                'status' => $responseStatus,
                'exception' => get_class($exception),
            ]);
        }

        return false;
    }

    protected function maskEmail(string $email): string
    {
        return Str::mask($email, '*', 1, max(1, strlen($email) - 3));
    }

    protected function flashAnalyticsEvent(string $name, array $detail = []): void
    {
        $events = session()->get('analytics.events', []);
        $events[] = [
            'name' => $name,
            'detail' => $detail,
        ];

        session()->flash('analytics.events', $events);
    }
}
