<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        Resource::query()->truncate();

        $resources = [
            [
                'title' => 'Caregiver Orientation Guide',
                'slug' => 'caregiver-orientation-guide',
                'description' => 'Step-by-step overview of the first 30 days, including intake checklist and escalation contacts.',
                'resource_type' => 'pdf',
                'file_path' => 'resources/caregiver-orientation-guide.pdf',
                'persona_tag' => 'adult_children',
                'display_order' => 1,
                'requires_login' => false,
                'preview_image' => '/assets/resources/orientation-guide.jpg',
            ],
            [
                'title' => 'Nutrition & Meal Planning Toolkit',
                'slug' => 'nutrition-meal-planning-toolkit',
                'description' => 'Sample menus, dietary modification tips, and MOH nutrition guidance curated by our dietitians.',
                'resource_type' => 'pdf',
                'file_path' => 'resources/nutrition-meal-planning-toolkit.pdf',
                'persona_tag' => 'caregivers',
                'display_order' => 2,
                'requires_login' => false,
                'preview_image' => '/assets/resources/nutrition-toolkit.jpg',
            ],
            [
                'title' => 'Transport & Drop-off Checklist',
                'slug' => 'transport-drop-off-checklist',
                'description' => 'Printable checklist covering transport arrangements, medication packs, and comfort items.',
                'resource_type' => 'checklist',
                'file_path' => 'resources/transport-drop-off-checklist.pdf',
                'persona_tag' => 'caregivers',
                'display_order' => 3,
                'requires_login' => false,
                'preview_image' => '/assets/resources/transport-checklist.jpg',
            ],
            [
                'title' => 'Subsidies Explained: Webinar Replay',
                'slug' => 'subsidies-explained-webinar',
                'description' => 'On-demand session walking through MOH subsidy tiers and application flow.',
                'resource_type' => 'webinar',
                'file_path' => null,
                'external_url' => 'https://videos.eldercare.sg/webinars/subsidies-explained',
                'persona_tag' => 'caregivers',
                'display_order' => 4,
                'requires_login' => true,
                'preview_image' => '/assets/resources/subsidies-webinar.jpg',
            ],
            [
                'title' => 'Cultural Celebrations Audio Spotlight',
                'slug' => 'cultural-celebrations-audio',
                'description' => 'Audio walkthrough highlighting how we adapt festive programming across cultures.',
                'resource_type' => 'audio',
                'file_path' => null,
                'external_url' => 'https://cdn.eldercare.sg/resources/cultural-celebrations.mp3',
                'persona_tag' => 'community_partners',
                'display_order' => 5,
                'requires_login' => false,
                'preview_image' => '/assets/resources/cultural-audio.jpg',
            ],
            [
                'title' => 'Caregiver Self-Care Checklist',
                'slug' => 'caregiver-self-care-checklist',
                'description' => 'Quick reminders and habits to protect caregiver wellbeing during intensive seasons.',
                'resource_type' => 'checklist',
                'file_path' => 'resources/caregiver-self-care-checklist.pdf',
                'persona_tag' => 'caregivers',
                'display_order' => 6,
                'requires_login' => false,
                'preview_image' => '/assets/resources/self-care-checklist.jpg',
            ],
        ];

        foreach ($resources as $resource) {
            Resource::create($resource);
        }
    }
}
