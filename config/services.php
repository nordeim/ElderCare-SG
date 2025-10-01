<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'mailchimp' => [
        'key' => env('MAILCHIMP_KEY'),
        'list_id' => env('MAILCHIMP_LIST_ID'),
        'endpoint' => env('MAILCHIMP_ENDPOINT', 'https://usX.api.mailchimp.com/3.0'),
    ],

    'booking' => [
        'url' => env('BOOKING_URL', 'https://calendly.com/eldercare-sg'),
    ],

    'availability' => [
        'driver' => env('AVAILABILITY_DRIVER', 'mock'),
        'cache_ttl' => (int) env('AVAILABILITY_CACHE_TTL', 300),
        'stale_after' => (int) env('AVAILABILITY_STALE_AFTER', 900),
        'timezone' => env('AVAILABILITY_TIMEZONE', 'Asia/Singapore'),
        'messages' => [
            'fallback' => env('AVAILABILITY_FALLBACK_MESSAGE', 'We will confirm availability within 24 hours.'),
        ],
        'mock' => [
            'window_days' => (int) env('AVAILABILITY_MOCK_WINDOW_DAYS', 7),
            'weekly_slots' => (int) env('AVAILABILITY_MOCK_WEEKLY_SLOTS', 18),
        ],
    ],

];
