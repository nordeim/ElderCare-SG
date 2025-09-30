<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ElderCare SG — Compassionate daycare solutions for seniors in Singapore.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ElderCare SG')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap">

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

    @stack('scripts')
</body>
</html>
