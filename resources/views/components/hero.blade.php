@props([
    'headline' => 'Compassionate daycare for your loved ones',
    'subheadline' => 'Experience Singapore’s warmest daytime sanctuary with programs tailored to every senior’s needs.',
    'primaryCta' => [
        'href' => '#booking',
        'label' => 'Book a visit',
    ],
    'secondaryCta' => [
        'href' => '#tour',
        'label' => 'Watch virtual tour',
    ],
])

<section
    class="relative overflow-hidden bg-trust text-white"
    x-data="{ availability: null }"
    x-init="availability = Alpine.store('availability')"
>
    <div class="absolute inset-0">
        <video
            class="h-full w-full object-cover"
            playsinline
            muted
            autoplay
            loop
            poster="/assets/hero-fallback.jpg"
        >
            <source src="/assets/hero-video.mp4" type="video/mp4">
            <source src="/assets/hero-video.webm" type="video/webm">
        </video>
        <div class="absolute inset-0 bg-trust/70"></div>
    </div>

    <div class="relative mx-auto flex max-w-section flex-col gap-6 px-6 py-24 md:py-32">
        <div class="max-w-2xl space-y-4">
            <h1 class="text-4xl font-semibold leading-tight sm:text-5xl lg:text-6xl">{{ $headline }}</h1>
            <p class="text-lg text-white/85 sm:text-xl">{{ $subheadline }}</p>

            <div x-cloak x-show="availability" class="mt-2">
                <div class="availability-badge w-full flex-wrap rounded-2xl border border-white/30 bg-white/10 p-4 text-left sm:w-auto sm:flex-nowrap" role="status" aria-live="polite">
                    <span class="availability-indicator" :class="availability?.indicatorClass" aria-hidden="true"></span>
                    <div class="flex min-w-0 flex-1 flex-col">
                        <span class="text-sm font-semibold" x-text="availability?.statusLabel ?? 'Checking availability…'"></span>
                        <span class="text-xs text-white/80" x-text="availability?.statusDetail ?? 'Hang tight while we fetch the latest visit openings.'"></span>
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
