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
                'tags' => ['experience', 'overview', 'care_model'],
                'audience' => 'caregiver',
                'featured' => true,
                'display_order' => 1,
            ],
            [
                'question' => 'How do subsidies and financial assistance work?',
                'answer' => 'Means-tested subsidies from MOH may apply. Our care concierge guides families through eligibility checks, paperwork, and payment planning.',
                'category' => 'Fees & Subsidies',
                'tags' => ['subsidy', 'financial_assistance', 'moh'],
                'audience' => 'caregiver',
                'featured' => true,
                'display_order' => 2,
            ],
            [
                'question' => 'What medical information do you need before the first visit?',
                'answer' => 'We request a current care summary, medication list, allergy alerts, and emergency contacts. Our clinical team reviews all files within two business days.',
                'category' => 'Admissions',
                'tags' => ['intake', 'medical_forms', 'first_visit'],
                'audience' => 'caregiver',
                'featured' => true,
                'display_order' => 3,
            ],
            [
                'question' => 'Do you offer transportation for seniors with mobility support needs?',
                'answer' => 'Yes. Wheelchair-friendly vehicles and trained escorts are available for both daily and ad-hoc visits. Fees depend on distance and timing.',
                'category' => 'Logistics',
                'tags' => ['transport', 'wheelchair', 'mobility'],
                'audience' => 'caregiver',
                'featured' => false,
                'display_order' => 4,
            ],
            [
                'question' => 'Can families book half-day or drop-in sessions?',
                'answer' => 'Half-day sessions and trial drop-ins are available, subject to capacity. Pre-booking allows us to personalise activities and care staffing.',
                'category' => 'Programs',
                'tags' => ['half_day', 'trial', 'booking'],
                'audience' => 'caregiver',
                'featured' => false,
                'display_order' => 5,
            ],
            [
                'question' => 'What languages do your team members speak?',
                'answer' => 'Our care team supports English, Mandarin, Malay, and Tamil. We match seniors with caregivers and programs that best fit their preferred language.',
                'category' => 'General',
                'tags' => ['language', 'multilingual', 'accessibility'],
                'audience' => 'caregiver',
                'featured' => false,
                'display_order' => 6,
            ],
            [
                'question' => 'How do you support seniors living with dementia?',
                'answer' => 'Specialised dementia coaches facilitate sensory activities, memory stations, and caregiver touchpoints. Each plan is reviewed monthly with families.',
                'category' => 'Care & Wellness',
                'tags' => ['dementia', 'memory_care', 'specialist'],
                'audience' => 'caregiver',
                'featured' => true,
                'display_order' => 7,
            ],
            [
                'question' => 'What happens if a senior feels unwell during their visit?',
                'answer' => 'Our clinical leads perform on-site assessments, involve family contacts, and coordinate with partner hospitals if escalation is required.',
                'category' => 'Care & Wellness',
                'tags' => ['safety', 'incident_response', 'health'],
                'audience' => 'caregiver',
                'featured' => false,
                'display_order' => 8,
            ],
            [
                'question' => 'Do you provide support for working caregivers?',
                'answer' => 'We offer respite credits, evening masterclasses, and a caregiver hotline operating until 10pm for urgent guidance.',
                'category' => 'Caregiver Support',
                'tags' => ['caregiver', 'respite', 'hotline'],
                'audience' => 'caregiver',
                'featured' => false,
                'display_order' => 9,
            ],
            [
                'question' => 'How do you tailor meals to medical or cultural preferences?',
                'answer' => 'Our culinary team collaborates with dietitians to customise menus for diabetes, renal diets, and cultural preferences. Weekly menus are shared with families.',
                'category' => 'Nutrition',
                'tags' => ['food', 'nutrition', 'dietary'],
                'audience' => 'caregiver',
                'featured' => false,
                'display_order' => 10,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
