@props([
    'staff' => collect(),
    'sectionId' => 'team',
    'headline' => 'Meet our dedicated care team',
    'kicker' => 'Care professionals',
    'description' => 'Every guide you meet during the tour brings decades of expertise in senior care, hospitality, and clinical support.',
])

@php
    $staff = collect($staff)->filter(fn ($member) => $member !== null);
@endphp

@if ($staff->isNotEmpty())
<section id="{{ $sectionId }}" class="bg-canvas py-16">
    <div class="mx-auto max-w-section px-6">
        <div class="mb-8 space-y-3 text-center">
            <p class="pill-tag mx-auto">{{ $kicker }}</p>
            <h2 class="text-3xl font-semibold text-trust sm:text-4xl">{{ $headline }}</h2>
            <p class="text-slate">{{ $description }}</p>
        </div>

        <div class="staff-carousel" x-data x-init="window.initEmbla?.($refs.viewport)">
            <div class="staff-carousel__viewport" x-ref="viewport">
                <div class="staff-carousel__container">
                    @foreach ($staff as $member)
                        <article class="staff-carousel__slide">
                            <div class="section-card h-full space-y-4">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full bg-gold/20">
                                        <img src="{{ $member->photo_url }}" alt="{{ $member->photo_alt_text }}" class="h-full w-full object-cover" loading="lazy">
                                    </span>
                                    <div>
                                        <p class="text-base font-semibold text-trust">{{ $member->name }}</p>
                                        <p class="text-xs uppercase tracking-wide text-slate">{{ $member->role }}</p>
                                    </div>
                                </div>

                                <p class="text-sm text-slate">{{ $member->bio }}</p>

                                @if ($member->hotspot_tag)
                                    <div class="inline-flex items-center gap-2 rounded-full bg-trust/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-trust">
                                        <span class="h-2 w-2 rounded-full bg-gold"></span>
                                        Guides {{ str_replace('_', ' ', $member->hotspot_tag) }}
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
