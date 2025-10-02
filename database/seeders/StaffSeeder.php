<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Staff::query()->truncate();

        $staff = [
            [
                'name' => 'Amelia Tan',
                'role' => 'Care Experience Director',
                'credentials' => 'RN, MSc Gerontology',
                'bio' => 'Leads immersive orientation tours and ensures every family understands our dementia-friendly design.',
                'photo_url' => '/assets/staff/amelia-tan.jpg',
                'photo_alt_text' => 'Portrait of Amelia Tan smiling in a sunlit activity room.',
                'is_featured' => true,
                'hotspot_tag' => 'welcome_lounge',
                'languages_spoken' => ['en', 'zh'],
                'years_experience' => 14,
                'on_call' => true,
            ],
            [
                'name' => 'Marcus Lee',
                'role' => 'Physiotherapy Lead',
                'credentials' => 'PT (SingHealth)',
                'bio' => 'Designs movement sessions and adaptive equipment plans for varying mobility levels.',
                'photo_url' => '/assets/staff/marcus-lee.jpg',
                'photo_alt_text' => 'Marcus Lee guiding a senior through physiotherapy exercises.',
                'is_featured' => true,
                'hotspot_tag' => 'mobility_studio',
                'languages_spoken' => ['en', 'zh'],
                'years_experience' => 11,
                'on_call' => true,
            ],
            [
                'name' => 'Dr. Priya Nair',
                'role' => 'Clinical Wellness Partner',
                'credentials' => 'MBBS, Geriatrics',
                'bio' => 'Coordinates medical reviews, nutrition plans, and family care conferences.',
                'photo_url' => '/assets/staff/priya-nair.jpg',
                'photo_alt_text' => 'Dr. Priya Nair reviewing care notes with a family member.',
                'is_featured' => true,
                'hotspot_tag' => 'wellness_clinic',
                'languages_spoken' => ['en', 'ta'],
                'years_experience' => 16,
                'on_call' => true,
            ],
            [
                'name' => 'Nurul Afiqah',
                'role' => 'Hospitality Experience Manager',
                'credentials' => 'Dip Hospitality Management',
                'bio' => 'Curates sensory lounges and seasonal culinary offerings for meaningful social moments.',
                'photo_url' => '/assets/staff/nurul-afiqah.jpg',
                'photo_alt_text' => 'Nurul Afiqah preparing tea service in the sensory lounge.',
                'is_featured' => false,
                'hotspot_tag' => 'sensory_lounge',
                'languages_spoken' => ['en', 'ms'],
                'years_experience' => 9,
                'on_call' => false,
            ],
            [
                'name' => 'Grace Wong',
                'role' => 'Dementia Care Coach',
                'credentials' => 'Certified Dementia Care Specialist',
                'bio' => 'Runs reminiscence sessions and sensory regulation plans within the memory studio.',
                'photo_url' => '/assets/staff/grace-wong.jpg',
                'photo_alt_text' => 'Grace Wong facilitating a reminiscence circle.',
                'is_featured' => false,
                'hotspot_tag' => 'memory_studio',
                'languages_spoken' => ['en', 'zh'],
                'years_experience' => 7,
                'on_call' => false,
            ],
            [
                'name' => 'Samuel Ho',
                'role' => 'Music Therapist',
                'credentials' => 'MA Music Therapy',
                'bio' => 'Designs music and movement interventions to boost mood and neuroplasticity.',
                'photo_url' => '/assets/staff/samuel-ho.jpg',
                'photo_alt_text' => 'Samuel Ho leading a group music therapy session.',
                'is_featured' => false,
                'hotspot_tag' => 'creative_suite',
                'languages_spoken' => ['en'],
                'years_experience' => 6,
                'on_call' => false,
            ],
            [
                'name' => 'Faridah Salleh',
                'role' => 'Nutritionist',
                'credentials' => 'BSc Nutrition & Dietetics',
                'bio' => 'Crafts dietary plans that honour cultural preferences while meeting clinical goals.',
                'photo_url' => '/assets/staff/faridah-salleh.jpg',
                'photo_alt_text' => 'Faridah Salleh plating a balanced Peranakan meal.',
                'is_featured' => false,
                'hotspot_tag' => 'culinary_lab',
                'languages_spoken' => ['en', 'ms'],
                'years_experience' => 12,
                'on_call' => true,
            ],
            [
                'name' => 'Theresa Lim',
                'role' => 'Senior Social Worker',
                'credentials' => 'MSW, Certified Care Transition Coach',
                'bio' => 'Supports families through care conferences and coordinates community resources.',
                'photo_url' => '/assets/staff/theresa-lim.jpg',
                'photo_alt_text' => 'Theresa Lim counselling a caregiver in the family hub.',
                'is_featured' => false,
                'hotspot_tag' => 'family_hub',
                'languages_spoken' => ['en', 'zh'],
                'years_experience' => 10,
                'on_call' => true,
            ],
        ];

        foreach ($staff as $member) {
            Staff::create($member);
        }
    }
}
