<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        Faq::query()->truncate();

        $faqs = [
            [
                'question' => 'What makes ElderCare SG different from traditional daycare?',
                'answer' => 'We combine clinical oversight with hospitality-led programming, ensuring every visit balances safety, stimulation, and social connection.',
                'category' => 'General',
                'display_order' => 1,
            ],
            [
                'question' => 'What medical information do you need before the first visit?',
                'answer' => 'We request a recent care summary, medication list, and emergency contacts. Our clinical team reviews everything before confirming the visit.',
                'category' => 'Admissions',
                'display_order' => 2,
            ],
            [
                'question' => 'Do you offer transportation for seniors with mobility support needs?',
                'answer' => 'Yes. We partner with trained transport escorts and wheelchair-friendly vehicles. Fees depend on distance and time of day.',
                'category' => 'Logistics',
                'display_order' => 1,
            ],
            [
                'question' => 'Can families drop in for part-day sessions?',
                'answer' => 'Half-day sessions are available for trial visits or caregiver respite. We recommend booking in advance so we can tailor the schedule.',
                'category' => 'Programs',
                'display_order' => 1,
            ],
            [
                'question' => 'How do subsidies and financial assistance work?',
                'answer' => 'Means-tested subsidies may apply via MOH schemes. Our care concierge guides families through eligibility checks and paperwork.',
                'category' => 'Fees & Subsidies',
                'display_order' => 1,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
