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
                'description' => 'Step-by-step overview of what to expect during the first month, including intake checklist.',
                'file_path' => 'resources/caregiver-orientation-guide.pdf',
                'persona_tag' => 'adult_children',
                'display_order' => 1,
            ],
            [
                'title' => 'Nutrition & Meal Planning Toolkit',
                'slug' => 'nutrition-meal-planning-toolkit',
                'description' => 'Sample menus, dietary modification tips, and MOH nutrition guidance.',
                'file_path' => 'resources/nutrition-meal-planning-toolkit.pdf',
                'persona_tag' => 'caregivers',
                'display_order' => 2,
            ],
            [
                'title' => 'Transport & Drop-off Checklist',
                'slug' => 'transport-drop-off-checklist',
                'description' => 'Printable checklist covering transport arrangements and personal items to pack.',
                'file_path' => 'resources/transport-drop-off-checklist.pdf',
                'persona_tag' => 'caregivers',
                'display_order' => 3,
            ],
        ];

        foreach ($resources as $resource) {
            Resource::create($resource);
        }
    }
}
