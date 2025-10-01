<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const COOKIE_KEY = 'eldercare_locale';
    public const SESSION_KEY = 'locale';
    public const COOKIE_TTL_MINUTES = 60 * 24 * 30; // 30 days

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);

        App::setLocale($locale);

        if ($request->hasSession()) {
            $request->session()->put(self::SESSION_KEY, $locale);
        }

        /** @var Response $response */
        $response = $next($request);

        if (!$request->hasCookie(self::COOKIE_KEY) || $request->cookie(self::COOKIE_KEY) !== $locale) {
            Cookie::queue(
                name: self::COOKIE_KEY,
                value: $locale,
                minutes: self::COOKIE_TTL_MINUTES,
                path: '/',
                domain: null,
                secure: config('session.secure', false),
                httpOnly: false,
                sameSite: config('session.same_site', 'lax')
            );
        }

        return $response;
    }

    protected function resolveLocale(Request $request): string
    {
        $supported = config('app.supported_locales', ['en']);
        $default = config('app.locale', 'en');

        $candidates = [
            $request->route('locale'),
            $request->get('locale'),
        ];

        if ($request->hasSession()) {
            $candidates[] = $request->session()->get(self::SESSION_KEY);
        }

        $candidates[] = $request->cookie(self::COOKIE_KEY);
        $candidates[] = $request->getPreferredLanguage($supported);

        foreach ($candidates as $candidate) {
            if ($candidate && in_array($candidate, $supported, true)) {
                return $candidate;
            }
        }

        return $default;
    }
}
