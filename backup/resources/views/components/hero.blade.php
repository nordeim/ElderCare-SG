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

<section class="relative overflow-hidden bg-trust text-white">
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
        </div>

        <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center">
            <a href="{{ $primaryCta['href'] }}" class="cta-button">
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
