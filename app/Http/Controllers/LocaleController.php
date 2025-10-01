<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        $supported = config('app.supported_locales', ['en']);
        $fallback = config('app.locale', 'en');

        if (!in_array($locale, $supported, true)) {
            $locale = $fallback;
        }

        App::setLocale($locale);
        $request->session()->put('locale', $locale);

        Cookie::queue(
            name: \App\Http\Middleware\SetLocale::COOKIE_KEY,
            value: $locale,
            minutes: \App\Http\Middleware\SetLocale::COOKIE_TTL_MINUTES,
            path: '/',
            domain: null,
            secure: config('session.secure', false),
            httpOnly: false,
            sameSite: config('session.same_site', 'lax')
        );

        $redirectTo = url()->previous() ?: route('home');

        return Redirect::to($redirectTo);
    }
}
