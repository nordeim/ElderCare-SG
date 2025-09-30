<?php

return [
    'segments' => [
        'memory_care' => [
            'all' => [
                ['field' => 'cognitive_support', 'values' => ['dementia']],
            ],
        ],
        'supportive_care' => [
            'any' => [
                ['field' => 'mobility', 'values' => ['assistance', 'full_support']],
                ['field' => 'health_considerations', 'values' => ['mobility_aids']],
            ],
        ],
        'respite_support' => [
            'any' => [
                ['field' => 'transportation', 'values' => ['yes']],
                ['field' => 'caregiver_goals', 'values' => ['respite']],
            ],
        ],
        'active_day' => [
            'all' => [
                ['field' => 'mobility', 'values' => ['independent']],
                ['field' => 'cognitive_support', 'values' => ['engaged']],
            ],
        ],
        'exploration' => [
            'fallback' => true,
        ],
    ],
];
