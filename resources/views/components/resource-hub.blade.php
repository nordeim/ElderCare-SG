@props([
    'resources' => collect(),
    'sectionId' => 'resources',
    'headline' => 'Download caregiver-ready guides',
    'kicker' => 'Resource hub',
    'description' => 'These printable resources cover intake preparation, transport logistics, and nutrition planning. Additional languages available upon request.',
])

@php
    $resources = $resources
        ->filter(fn ($resource) => $resource->is_active)
        ->sortBy('display_order')
        ->values();
@endphp

@if ($resources->isNotEmpty())
<section id="{{ $sectionId }}" class="bg-canvas py-16">
    <div class="mx-auto max-w-section px-6">
        <div class="mb-8 text-center lg:text-left">
            <p class="pill-tag mx-auto lg:mx-0">{{ $kicker }}</p>
            <h2 class="mt-4 text-3xl font-semibold text-trust sm:text-4xl">{{ $headline }}</h2>
            <p class="mt-3 text-slate">{{ $description }}</p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($resources as $resource)
                <article class="resource-card">
                    <div class="space-y-3">
                        <p class="resource-card__tag">{{ $resource->persona_tag ? \Illuminate\Support\Str::headline($resource->persona_tag) : 'Caregivers' }}</p>
                        <h3 class="text-xl font-semibold text-trust">{{ $resource->title }}</h3>
                        <p class="text-sm text-slate">{{ $resource->description }}</p>
                    </div>

                    <div class="resource-card__meta">
                        <span class="text-xs uppercase tracking-wide text-slate-dark">PDF download</span>
                        <span class="text-xs text-slate">Updated {{ $resource->updated_at?->format('M Y') ?? now()->format('M Y') }}</span>
                    </div>

                    <a
                        href="{{ Storage::disk('public')->exists($resource->file_path) ? Storage::url($resource->file_path) : '#' }}"
                        class="resource-card__cta"
                        @if (! Storage::disk('public')->exists($resource->file_path))
                            aria-disabled="true"
                            data-disabled="true"
                        @endif
                        data-analytics-id="resource-download"
                        data-resource-slug="{{ $resource->slug }}"
                    >
                        <span>Download guide</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M7.5 12 12 16.5m0 0 4.5-4.5M12 16.5V3" />
                        </svg>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
