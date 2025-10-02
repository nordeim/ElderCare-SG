<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ElderCare SG — Compassionate daycare solutions for seniors in Singapore.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ElderCare SG')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap">

    @php
        $appCss = Vite::asset('resources/css/app.css');
        $appJs = Vite::asset('resources/js/app.js');
        $heroPosterPreload = asset('assets/hero-fallback.jpg');
    @endphp

    <link rel="preload" as="style" href="{{ $appCss }}">
    <link rel="preload" as="script" href="{{ $appJs }}" crossorigin>
    <link rel="preload" as="image" href="{{ $heroPosterPreload }}" imagesrcset="{{ $heroPosterPreload }} 1x">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if (config('analytics.driver') === 'plausible' && config('analytics.plausible.domain'))
        <script defer data-domain="{{ config('analytics.plausible.domain') }}" src="{{ config('analytics.plausible.script') }}"></script>
    @endif
</head>
<body class="bg-canvas font-body text-slate-dark">
    <div class="relative flex min-h-screen flex-col">
        @include('partials.nav')

        <main class="flex-1">
            @yield('content')
        </main>

        @include('partials.footer')
    </div>

    <script>
        window.__eldercareAnalyticsQueue = {!! json_encode(session('analytics.events', []), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
        window.__eldercareAnalyticsGoals = {!! json_encode(config('analytics.plausible.goals', []), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
        window.__eldercareAnalyticsDashboard = @json(config('analytics.plausible.shared_dashboard'));
    </script>

    @stack('scripts')
</body>
</html>
