<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CostEstimatorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_estimator_section_renders_with_defaults(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Cost estimator', false)
            ->assertSee('Days per week', false)
            ->assertSee('Projected monthly total', false)
            ->assertSee('See calculation details', false);
    }

    public function test_estimator_component_includes_pricing_configuration(): void
    {
        $response = $this->get('/');

        $response->assertOk();

        $decodedHtml = html_entity_decode($response->getContent());

        $this->assertMatchesRegularExpression('/costEstimatorComponent\(/', $decodedHtml);

        $pricing = $this->extractJsonParsePayload($decodedHtml, 'pricing');
        $defaults = $this->extractJsonParsePayload($decodedHtml, 'defaults');

        $this->assertSame(36, $pricing['transportFee']);
        $this->assertContains('meals', array_column($pricing['addOns'], 'key'));
        $this->assertSame('chaspioneer', $defaults['subsidyKey']);
    }

    private function extractJsonParsePayload(string $html, string $key): array
    {
        $pattern = sprintf('/%s:\s*JSON\\.parse\(\'((?:\\\\\'|[^\'])*)\'\)/', preg_quote($key, '/'));

        $this->assertMatchesRegularExpression($pattern, $html);

        preg_match($pattern, $html, $matches);

        $this->assertNotEmpty($matches[1] ?? null, sprintf('JSON.parse payload for %s not found', $key));

        $json = str_replace(['\\u0022', '\\u0027'], ['"', "'"], $matches[1]);
        $json = str_replace('\\/', '/', $json);

        $decoded = json_decode($json, true);

        $this->assertIsArray($decoded, sprintf('Unable to decode JSON.parse payload for %s', $key));

        return $decoded;
    }
}
