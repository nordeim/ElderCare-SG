<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class LocaleSwitchTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function user_can_switch_to_supported_locale_and_persist_preference(): void
    {
        $this->from(route('home'))
            ->post(route('locale.switch', ['locale' => 'zh']))
            ->assertRedirect(route('home'))
            ->assertCookie('eldercare_locale', 'zh');

        $this->assertSame('zh', session('locale'));
        $this->assertSame('zh', App::getLocale());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function unsupported_locale_falls_back_to_default(): void
    {
        $this->from(route('home'))
            ->post(route('locale.switch', ['locale' => 'fr']))
            ->assertRedirect(route('home'))
            ->assertCookie('eldercare_locale', config('app.locale'));

        $this->assertSame(config('app.locale'), session('locale'));
        $this->assertSame(config('app.locale'), App::getLocale());
    }
}
