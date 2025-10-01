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
                'bio' => 'Leads immersive orientation tours and ensures every family understands our dementia-friendly design.',
                'photo_url' => '/assets/staff/amelia-tan.jpg',
                'photo_alt_text' => 'Portrait of Amelia Tan smiling in a sunlit activity room.',
                'is_featured' => true,
                'hotspot_tag' => 'welcome_lounge',
            ],
            [
                'name' => 'Marcus Lee',
                'role' => 'Physiotherapy Lead',
                'bio' => 'Designs movement sessions and adaptive equipment plans for varying mobility levels.',
                'photo_url' => '/assets/staff/marcus-lee.jpg',
                'photo_alt_text' => 'Marcus Lee guiding a senior through physiotherapy exercises.',
                'is_featured' => true,
                'hotspot_tag' => 'mobility_studio',
            ],
            [
                'name' => 'Dr. Priya Nair',
                'role' => 'Clinical Wellness Partner',
                'bio' => 'Coordinates medical reviews, nutrition plans, and family care conferences.',
                'photo_url' => '/assets/staff/priya-nair.jpg',
                'photo_alt_text' => 'Dr. Priya Nair reviewing care notes with a family member.',
                'is_featured' => true,
                'hotspot_tag' => 'wellness_clinic',
            ],
            [
                'name' => 'Nurul Afiqah',
                'role' => 'Hospitality Experience Manager',
                'bio' => 'Curates sensory lounges and seasonal culinary offerings for meaningful social moments.',
                'photo_url' => '/assets/staff/nurul-afiqah.jpg',
                'photo_alt_text' => 'Nurul Afiqah preparing tea service in the sensory lounge.',
                'is_featured' => false,
                'hotspot_tag' => 'sensory_lounge',
            ],
        ];

        foreach ($staff as $member) {
            Staff::create($member);
        }
    }
}
