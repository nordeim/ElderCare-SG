<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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

        $redirectTo = url()->previous() ?: route('home');

        return Redirect::to($redirectTo);
    }
}
