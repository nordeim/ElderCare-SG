<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'name' => 'Day Programs',
                'slug' => 'day-programs',
                'icon' => '☀️',
                'headline' => 'Structured days, joyful moments',
                'description' => 'Purposeful daily schedules blending cognitive stimulation, social engagement, and restorative breaks.',
                'highlights' => [
                    'Cognitive workshops and reminiscence therapy',
                    'Chef-designed meals with dietary accommodations',
                    'Transportation coordination across Singapore',
                ],
                'display_order' => 1,
                'monthly_rate' => 1850.00,
                'transport_fee' => 120.00,
                'capacity_daily' => 60,
                'availability_status' => 'available',
                'languages_supported' => ['en', 'zh'],
                'analytics_tag' => 'program.day',
            ],
            [
                'name' => 'Memory Care Mornings',
                'slug' => 'memory-care-mornings',
                'icon' => '🧠',
                'headline' => 'Specialised dementia enrichment in a calm studio',
                'description' => 'Half-day cognitive therapies focusing on reminiscence, sensory art, and music tailored to early and mid-stage dementia.',
                'highlights' => [
                    'Alzheimer’s and dementia-certified coaches',
                    'Small group engagement capped at six per coach',
                    'Family debrief with care goals every fortnight',
                ],
                'display_order' => 2,
                'monthly_rate' => 1650.00,
                'transport_fee' => 140.00,
                'capacity_daily' => 24,
                'availability_status' => 'limited',
                'languages_supported' => ['en', 'zh', 'ms'],
                'analytics_tag' => 'program.memory',
            ],
            [
                'name' => 'Weekend Respite Club',
                'slug' => 'weekend-respite',
                'icon' => '🎉',
                'headline' => 'Saturday & Sunday relief with curated social outings',
                'description' => 'Weekend-only coverage combining escorted excursions, community brunch, and restorative care blocks.',
                'highlights' => [
                    'Transportation bundled within central districts',
                    'Curated outings to parks, museums, and hawker tours',
                    'Priority waitlist for weekday upgrades',
                ],
                'display_order' => 3,
                'monthly_rate' => 950.00,
                'transport_fee' => 100.00,
                'capacity_daily' => 18,
                'availability_status' => 'limited',
                'languages_supported' => ['en'],
                'analytics_tag' => 'program.respite',
            ],
            [
                'name' => 'Night Owl Support',
                'slug' => 'night-owl-support',
                'icon' => '🌙',
                'headline' => 'Overnight respite with circadian-friendly routines',
                'description' => 'Twilight-to-dawn supervision featuring sleep coaching, medication adherence, and midnight snack service.',
                'highlights' => [
                    'Sleep hygiene specialists with gerontology focus',
                    'Dedicated quiet suites and night monitoring tech',
                    'Optional allied health check-ins at 7am handover',
                ],
                'display_order' => 4,
                'monthly_rate' => 2100.00,
                'transport_fee' => null,
                'capacity_daily' => 12,
                'availability_status' => 'waitlist',
                'languages_supported' => ['en', 'ta'],
                'analytics_tag' => 'program.overnight',
            ],
            [
                'name' => 'Caregiver Masterclasses',
                'slug' => 'caregiver-masterclasses',
                'icon' => '📚',
                'headline' => 'Evening intensives for confident home caregiving',
                'description' => 'Four-week rotational curriculum covering transfers, behavioural cues, nutrition planning, and caregiver resilience.',
                'highlights' => [
                    'Taught by interdisciplinary clinical team',
                    'Workbook and video library with bilingual subtitles',
                    'Includes respite credits for on-site practice sessions',
                ],
                'display_order' => 5,
                'monthly_rate' => 420.00,
                'transport_fee' => null,
                'capacity_daily' => 40,
                'availability_status' => 'available',
                'languages_supported' => ['en', 'zh'],
                'analytics_tag' => 'program.masterclass',
            ],
        ];

        foreach ($programs as $program) {
            Program::updateOrCreate(['slug' => $program['slug']], $program);
        }
    }
}
