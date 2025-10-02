<?php

return [
    'driver' => env('ANALYTICS_DRIVER', 'plausible'),

    'plausible' => [
        'domain' => env('PLAUSIBLE_DOMAIN'),
        'script' => env('PLAUSIBLE_SCRIPT', 'https://plausible.io/js/script.js'),
        'shared_dashboard' => env('PLAUSIBLE_SHARED_DASHBOARD'),
        'goals' => [
            'mailchimp.success' => env('PLAUSIBLE_GOAL_MAILCHIMP', 'mailchimp-success'),
            'mailchimp.failure' => env('PLAUSIBLE_GOAL_MAILCHIMP_FAILURE', 'mailchimp-failure'),
            'booking.click' => env('PLAUSIBLE_GOAL_BOOKING_CLICK', 'booking-click'),
            'resource.download' => env('PLAUSIBLE_GOAL_RESOURCE_DOWNLOAD', 'resource-download'),
            'estimator.submit' => env('PLAUSIBLE_GOAL_ESTIMATOR_SUBMIT', 'estimator-submit'),
        ],
    ],
];
