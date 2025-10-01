<?php

namespace Tests\Feature;

use App\Models\Faq;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqEndpointTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_homepage_displays_faq_headline_and_categories(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Frequently asked questions', false)
            ->assertSee('Answers to common caregiver questions', false)
            ->assertSee('What makes ElderCare SG different from traditional daycare?', false);
    }

    public function test_faq_schema_json_ld_includes_seeded_questions(): void
    {
        $response = $this->get('/');

        $response->assertOk();

        $faqs = Faq::query()->pluck('question');

        foreach ($faqs as $question) {
            $response->assertSee($question, false);
        }
    }
}
