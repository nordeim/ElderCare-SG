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
            ],
            [
                'name' => 'Wellness & Therapy',
                'slug' => 'wellness-therapy',
                'icon' => '💚',
                'headline' => 'Therapeutic care for vitality',
                'description' => 'On-site physiotherapy, occupational therapy, and gentle movement to support seniors’ strength and balance.',
                'highlights' => [
                    'MOH-certified therapists on-site daily',
                    'Hydrotherapy partnerships with community pools',
                    'Personalised fall-prevention plans',
                ],
                'display_order' => 2,
            ],
            [
                'name' => 'Family Support',
                'slug' => 'family-support',
                'icon' => '👥',
                'headline' => 'Supporting caregivers every step',
                'description' => 'Caregiver coaching, respite options, and regular updates to keep families connected and confident.',
                'highlights' => [
                    'Monthly caregiver workshops and support circles',
                    'Secure family portal with daily summaries',
                    'Transitional care planning with hospital partners',
                ],
                'display_order' => 3,
            ],
        ];

        foreach ($programs as $program) {
            Program::updateOrCreate(['slug' => $program['slug']], $program);
        }
    }
}
