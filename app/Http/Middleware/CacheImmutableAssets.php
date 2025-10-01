<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CacheImmutableAssets
{
    private const IMMUTABLE_PATTERN = '/^build\/.*\.[a-f0-9]{8}\.(css|js)$/';

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($this->shouldApplyImmutableHeaders($request, $response)) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        }

        return $response;
    }

    private function shouldApplyImmutableHeaders(Request $request, $response): bool
    {
        if (! method_exists($response, 'isSuccessful') || ! $response->isSuccessful()) {
            return false;
        }

        if (! $response instanceof BinaryFileResponse && ! $response->headers->has('Content-Type')) {
            return false;
        }

        $path = ltrim($request->path(), '/');

        return (bool) preg_match(self::IMMUTABLE_PATTERN, $path);
    }
}
