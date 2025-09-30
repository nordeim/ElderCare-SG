<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MailchimpService
{
    public function subscribe(string $email): bool
    {
        if (! config('services.mailchimp.key')) {
            Log::info('Mailchimp disabled: missing configuration.', ['email' => $email]);

            return false;
        }

        try {
            $response = Http::withBasicAuth('anystring', config('services.mailchimp.key'))
                ->post(sprintf('%s/lists/%s/members', rtrim(config('services.mailchimp.endpoint'), '/'), config('services.mailchimp.list_id')), [
                    'email_address' => $email,
                    'status' => 'pending',
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::warning('Mailchimp subscription failed.', [
                'email' => $email,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $exception) {
            Log::error('Mailchimp subscription error.', [
                'email' => $email,
                'message' => $exception->getMessage(),
            ]);
        }

        return false;
    }
}
