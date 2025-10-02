<?php

namespace Tests\Feature;

use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('phase6')]
class ResourceDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_external_resource_uses_external_url_when_file_missing(): void
    {
        Storage::fake('public');

        $resource = Resource::factory()
            ->external()
            ->create([
                'title' => 'Webinar replay',
                'slug' => 'webinar-replay',
                'description' => 'Recorded caregiver workshop replay.',
            ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('data-resource-slug="' . $resource->slug . '"', false);
        $response->assertSee('href="' . $resource->external_url . '"', false);
        $response->assertDontSee('data-disabled="true"', false);
    }

    public function test_local_resource_uses_storage_link(): void
    {
        Storage::fake('public');

        $resource = Resource::factory()->create([
            'title' => 'Caregiver checklist',
            'slug' => 'caregiver-checklist',
            'file_path' => 'resources/checklist.pdf',
            'external_url' => null,
        ]);

        Storage::disk('public')->put($resource->file_path, 'dummy content');

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('href="' . Storage::url($resource->file_path) . '"', false);
        $response->assertDontSee('data-disabled="true"', false);
    }

    public function test_resource_without_sources_is_disabled(): void
    {
        Storage::fake('public');

        $resource = Resource::factory()->create([
            'title' => 'Pending resource',
            'slug' => 'pending-resource',
            'file_path' => null,
            'external_url' => null,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('data-resource-slug="' . $resource->slug . '"', false);
        $response->assertSee('data-disabled="true"', false);
    }
}
