<?php

return [
    'driver' => env('ANALYTICS_DRIVER'),

    'plausible' => [
        'domain' => env('PLAUSIBLE_DOMAIN'),
        'script' => env('PLAUSIBLE_SCRIPT', 'https://plausible.io/js/script.js'),
    ],
];
