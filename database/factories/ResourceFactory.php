<?php

namespace Database\Factories;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(3);
        $slug = Str::slug($title);

        return [
            'title' => $title,
            'slug' => $slug,
            'description' => $this->faker->paragraph,
            'resource_type' => 'pdf',
            'file_path' => 'resources/' . $slug . '.pdf',
            'external_url' => null,
            'preview_image' => null,
            'persona_tag' => $this->faker->randomElement(['adult_children', 'caregivers', 'professionals']),
            'display_order' => $this->faker->numberBetween(1, 20),
            'requires_login' => false,
            'is_active' => true,
        ];
    }

    public function external(): self
    {
        return $this->state(fn () => [
            'file_path' => null,
            'external_url' => 'https://example.com/resources/' . Str::random(8),
        ]);
    }
}
