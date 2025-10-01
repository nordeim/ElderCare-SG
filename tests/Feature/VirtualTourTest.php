<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\Staff;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VirtualTourTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function homepage_renders_virtual_tour_hotspots_and_staff_carousel(): void
    {
        Program::create([
            'name' => 'Sample Program',
            'slug' => 'sample-program',
            'description' => 'Supporting seniors with personalized activities.',
            'display_order' => 1,
            'is_active' => true,
        ]);

        Testimonial::create([
            'author_name' => 'Caregiver Lim',
            'author_relation' => 'Daughter',
            'location' => 'Bukit Timah',
            'quote' => 'The team is caring and attentive.',
            'is_active' => true,
            'display_order' => 1,
        ]);

        Staff::create([
            'name' => 'Test Guide',
            'role' => 'Experience Lead',
            'bio' => 'Guides guests through the welcome lounge.',
            'photo_url' => '/assets/staff/test-guide.jpg',
            'photo_alt_text' => 'Test guide',
            'is_featured' => true,
            'hotspot_tag' => 'welcome_lounge',
        ]);

        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertViewHas('tourHotspots', function ($hotspots) {
                return $hotspots->contains(function ($hotspot) {
                    return $hotspot['id'] === 'welcome_lounge';
                });
            })
            ->assertViewHas('tourTranscriptUrl', '/media/transcripts/tour-main.md')
            ->assertSee('Welcome Lounge', false)
            ->assertSee('Test Guide', false)
            ->assertSee('Experience Lead', false);
    }
}
