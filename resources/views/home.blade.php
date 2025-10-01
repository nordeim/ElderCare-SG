@extends('layouts.app')

@section('title', 'ElderCare SG — Compassionate Daycare')

@section('content')
    <x-hero :primary-cta="['href' => $bookingUrl, 'label' => 'Book a visit']" />

    <x-assessment infoHref="#programs" />

    <section id="programs" class="bg-canvas py-16">
        <div class="mx-auto max-w-section px-6" x-data>
            <div class="mb-10 flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
                <div>
                    <p class="pill-tag" x-text="Alpine.store('assessmentRecommendation')?.hasRecommendation ? 'Recommended for you' : 'Programs'"></p>
                    <h2 class="mt-4 text-3xl font-semibold text-trust sm:text-4xl" x-text="Alpine.store('assessmentRecommendation')?.segment?.name ?? 'Holistic daytime care tailored for Singaporean seniors'"></h2>
                    <p class="mt-3 text-slate" x-show="Alpine.store('assessmentRecommendation')?.segment" x-text="Alpine.store('assessmentRecommendation')?.segment?.description"></p>
                </div>
                <a
                    :href="Alpine.store('assessmentRecommendation')?.primaryCtaHref ?? '#booking'"
                    class="cta-button"
                    x-text="Alpine.store('assessmentRecommendation')?.primaryCtaLabel ?? 'Book a consultation'"
                >Book a consultation</a>
            </div>

            <div
                class="mb-12 grid gap-6 rounded-3xl bg-white p-6 shadow-card"
                x-show="Alpine.store('assessmentRecommendation')?.hasRecommendation"
                x-transition
            >
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="pill-tag inline-flex">Personalized highlights</p>
                        <h3 class="mt-2 text-2xl font-semibold text-trust" x-text="Alpine.store('assessmentRecommendation')?.segment?.name"></h3>
                    </div>
                    <a
                        :href="Alpine.store('assessmentRecommendation')?.primaryCtaHref ?? '#booking'"
                        class="cta-button"
                        x-text="Alpine.store('assessmentRecommendation')?.primaryCtaLabel ?? 'Book now'"
                    >Book now</a>
                </div>
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wide text-trust">Programs to explore</h4>
                        <ul class="mt-3 space-y-2 text-sm text-slate">
                            <template x-for="program in Alpine.store('assessmentRecommendation')?.programs" :key="program">
                                <li class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-gold"></span>
                                    <span x-text="program"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wide text-trust">Why we recommend this</h4>
                        <ul class="mt-3 space-y-2 text-sm text-slate">
                            <template x-for="highlight in Alpine.store('assessmentRecommendation')?.highlights" :key="highlight">
                                <li class="flex items-start gap-2">
                                    <span class="mt-1 h-2 w-2 rounded-full bg-gold"></span>
                                    <span x-text="highlight"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($programs as $program)
                    <article class="section-card fade-in-up" data-animate>
                        <div class="flex items-center gap-3 text-sm uppercase tracking-wide text-trust">
                            @if ($program->icon)
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gold text-trust shadow-sm">
                                    {{ $program->icon }}
                                </span>
                            @endif
                            <span>{{ $program->headline ?? $program->name }}</span>
                        </div>
                        <h3 class="mt-4 text-2xl font-semibold text-trust">{{ $program->name }}</h3>
                        <p class="mt-3 text-slate">{{ $program->description }}</p>

                        @if ($program->highlights)
                            <ul class="mt-4 space-y-2 text-sm text-slate">
                                @foreach ($program->highlights as $highlight)
                                    <li class="flex items-start gap-2">
                                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-gold"></span>
                                        <span>{{ $highlight }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <a href="#" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-trust hover:text-gold">Learn more
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.25 9.75L17.25 12L14.25 14.25M6.75 12H17.25" />
                            </svg>
                        </a>
                    </article>
                @empty
                    <p class="text-slate">Program content is coming soon.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="philosophy" class="bg-white py-16">
        <div class="mx-auto max-w-section grid gap-12 px-6 lg:grid-cols-2 lg:items-center">
            <div class="space-y-4">
                <p class="pill-tag">Our Philosophy</p>
                <h2 class="text-3xl font-semibold text-trust sm:text-4xl">Safety, dignity, and joyful engagement guide every moment</h2>
                <p class="text-slate">From evidence-backed routines to personalised care plans, we nurture seniors with respect and warmth. Our licensed team collaborates with families to craft experiences that uplift body, mind, and spirit.</p>
                <ul class="space-y-3 text-slate">
                    <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-gold"></span>MOH-certified nurses and therapists onsite daily.</li>
                    <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-gold"></span>Daily reporting and caregiver briefings keep families informed.</li>
                    <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-gold"></span>Inclusive programming accommodating mobility and cognitive needs.</li>
                </ul>
            </div>
            <div class="section-card space-y-6">
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <p class="text-4xl font-semibold text-trust">15+</p>
                        <p class="text-sm text-slate">years serving seniors</p>
                    </div>
                    <div>
                        <p class="text-4xl font-semibold text-trust">98%</p>
                        <p class="text-sm text-slate">family satisfaction</p>
                    </div>
                    <div>
                        <p class="text-4xl font-semibold text-trust">1:4</p>
                        <p class="text-sm text-slate">staff to resident ratio</p>
                    </div>
                    <div>
                        <p class="text-4xl font-semibold text-trust">24/7</p>
                        <p class="text-sm text-slate">caregiver hotline</p>
                    </div>
                </div>
                <p class="text-sm text-slate">Metrics refreshed quarterly to maintain accreditation and continuous improvement goals.</p>
            </div>
        </div>
    </section>

    <section id="testimonials" class="bg-canvas py-16">
        <div class="mx-auto max-w-section px-6">
            <div class="mb-10 space-y-3 text-center">
                <p class="pill-tag mx-auto">Testimonials</p>
                <h2 class="text-3xl font-semibold text-trust sm:text-4xl">Families share their ElderCare SG journeys</h2>
                <p class="text-slate">Embla-powered carousel coming soon. Below is a placeholder structure to integrate dynamic testimonials.</p>
            </div>

            <div class="overflow-hidden" x-data x-init="window.initEmbla($refs.viewport)">
                <div class="embla__viewport" x-ref="viewport">
                    <div class="embla__container flex gap-6">
                        @forelse ($testimonials as $testimonial)
                            <article class="embla__slide basis-full md:basis-2/3 lg:basis-1/2">
                                <div class="section-card h-full space-y-4">
                                    <p class="text-lg text-slate">“{{ $testimonial->quote }}”</p>
                                    <div class="space-y-1 text-sm text-trust">
                                        <p class="font-semibold">{{ $testimonial->author_name }}</p>
                                        <p class="text-xs text-slate">{{ $testimonial->author_relation }} • {{ $testimonial->location }}</p>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="section-card text-center text-slate">
                                Testimonials are being curated.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-virtual-tour
        :hotspots="$tourHotspots"
        transcript-url="{{ $tourTranscriptUrl }}"
        description="Explore therapy rooms, social lounges, and dining spaces in a guided virtual tour. Accessibility notes and transcripts accompany each media asset."
        :highlights="[
            'Captioned tour with audio descriptions.',
            'Transportation and pickup lockers showcased.',
            'Interactive hotspots highlight safety features.',
        ]"
        cta-href="#booking"
    />

    <x-staff-carousel :staff="$featuredStaff" />

    <section id="booking" class="bg-trust py-16 text-white">
        <div class="mx-auto max-w-section px-6 text-center">
            <h2 class="text-3xl font-semibold sm:text-4xl">Ready to experience ElderCare SG?</h2>
            <p class="mt-4 text-white/85">Book an in-person or virtual tour. We confirm availability within 24 hours and personalise each visit to your family’s needs.</p>
            <a href="{{ $bookingUrl }}" class="cta-button mt-8 inline-flex">Book now</a>
        </div>
    </section>
@endsection
