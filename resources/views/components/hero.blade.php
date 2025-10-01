@props([
    'headline' => __('hero.headline'),
    'subheadline' => __('hero.subheadline'),
    'primaryCta' => [
        'href' => '#booking',
        'label' => __('hero.primary_cta.label'),
    ],
    'secondaryCta' => [
        'href' => '#tour',
        'label' => __('hero.secondary_cta.label'),
    ],
])

@php
    $heroPosterPath = 'assets/hero-fallback.jpg';
    $heroPosterExists = file_exists(public_path($heroPosterPath));
    $heroPosterSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600" role="img" aria-label="ElderCare SG hero placeholder"><rect width="800" height="600" fill="#1C3D5A"/><text x="50%" y="50%" fill="#F0A500" font-family="Inter, Arial, sans-serif" font-size="48" font-weight="600" text-anchor="middle" dominant-baseline="middle">ElderCare SG</text></svg>';
    $heroPoster = $heroPosterExists
        ? asset($heroPosterPath)
        : 'data:image/svg+xml,' . rawurlencode($heroPosterSvg);

    $heroVideoSources = collect([
        'assets/hero-video.mp4',
        'assets/hero-video.webm',
    ]);

    $heroHasVideo = $heroVideoSources->every(fn ($path) => file_exists(public_path($path)));
    $heroVideoAssets = $heroHasVideo
        ? $heroVideoSources->map(fn ($path) => asset($path))->values()
        : collect();
@endphp

<section
    class="relative overflow-hidden bg-trust text-white"
    data-hero
    x-data="{ availability: null }"
    x-init="availability = Alpine.store('availability')"
>
    <div class="absolute inset-0">
        @if ($heroHasVideo)
            <video
                class="h-full w-full object-cover"
                playsinline
                muted
                loop
                poster="{{ $heroPoster }}"
                preload="metadata"
                data-hero-video
            >
                <source src="{{ $heroVideoAssets->first() }}" type="video/mp4">
                <source src="{{ $heroVideoAssets->last() }}" type="video/webm">
            </video>
            <noscript>
                <div
                    class="h-full w-full bg-gradient-to-br from-trust via-[#22476A] to-[#183651]"
                    style="background-image: url('{{ $heroPoster }}'); background-size: cover; background-position: center;"
                    aria-hidden="true"
                ></div>
            </noscript>
        @else
            <div
                class="h-full w-full bg-gradient-to-br from-trust via-[#22476A] to-[#183651]"
                style="background-image: url('{{ $heroPoster }}'); background-size: cover; background-position: center;"
                aria-hidden="true"
            ></div>
        @endif
        <div class="absolute inset-0 bg-trust/70"></div>
    </div>

    <div class="relative mx-auto flex max-w-section flex-col gap-6 px-6 py-24 md:py-32">
        <div class="max-w-2xl space-y-4">
            <h1 class="font-semibold leading-tight text-display-lg">{{ $headline }}</h1>
            <p class="text-body-lg text-white/85">{{ $subheadline }}</p>

            <div x-cloak x-show="availability" class="mt-2">
                <div class="availability-badge w-full flex-wrap rounded-2xl border border-white/30 bg-white/10 p-4 text-left sm:w-auto sm:flex-nowrap" role="status" aria-live="polite">
                    <span class="availability-indicator" :class="availability?.indicatorClass" aria-hidden="true"></span>
                    <div class="flex min-w-0 flex-1 flex-col">
                        <span class="text-sm font-semibold" x-text="availability?.statusLabel ?? @js(__('hero.availability.loading'))"></span>
                        <span class="text-xs text-white/80" x-text="availability?.statusDetail ?? @js(__('hero.availability.detail_loading'))"></span>
                    </div>
                    <button
                        type="button"
                        class="mt-2 inline-flex items-center gap-2 rounded-full border border-white/30 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white/80 transition hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/60 sm:mt-0"
                        @click.prevent="availability?.refresh()"
                        :disabled="availability?.isLoading"
                        :aria-disabled="availability?.isLoading ? 'true' : 'false'"
                    >
                        <svg x-show="availability?.isLoading" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 12a7.5 7.5 0 0 1 12.495-5.303"></path>
                        </svg>
                        <span x-show="!availability?.isLoading">Refresh</span>
                        <span x-show="availability?.isLoading">Refreshing…</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center">
            <a
                :href="Alpine.store('assessmentRecommendation')?.primaryCtaHref ?? @js($primaryCta['href'])"
                class="cta-button"
                x-text="Alpine.store('assessmentRecommendation')?.primaryCtaLabel ?? @js($primaryCta['label'])"
            >
                {{ $primaryCta['label'] }}
            </a>
            <a href="{{ $secondaryCta['href'] }}" class="inline-flex items-center gap-2 rounded-full border border-white/60 px-6 py-3 text-sm font-medium uppercase tracking-wide text-white transition hover:border-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.25 9.75L17.25 12L14.25 14.25M6.75 12H17.25" />
                </svg>
                {{ $secondaryCta['label'] }}
            </a>
        </div>
    </div>
</section>

@php($availabilityMessages = [
    'status' => [
        'loading' => __('hero.availability.loading'),
        'error' => __('hero.availability.error'),
        'fallback' => __('hero.availability.fallback'),
        'high' => __('hero.availability.high'),
        'medium' => __('hero.availability.medium'),
        'low' => __('hero.availability.low'),
        'none' => __('hero.availability.none'),
    ],
    'detail' => [
        'loading' => __('hero.availability.detail_loading'),
        'error' => __('hero.availability.detail_error'),
        'fallback' => __('hero.availability.detail_fallback'),
        'summary' => __('hero.availability.summary'),
        'summary_with_time' => __('hero.availability.summary_with_time'),
        'slot_single' => __('hero.availability.slot_single'),
        'slot_plural' => __('hero.availability.slot_plural'),
    ],
])

@push('scripts')
    <script>
        window.eldercare = window.eldercare || {};
        window.eldercare.availabilityMessages = @json($availabilityMessages);
    </script>
@endpush
