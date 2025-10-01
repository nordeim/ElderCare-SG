@props([
    'sectionId' => 'tour',
    'kicker' => 'Facility tour',
    'headline' => 'Preview our spaces before you visit',
    'description' => null,
    'highlights' => [],
    'ctaLabel' => 'Watch virtual tour',
    'ctaHref' => '#booking',
    'previewImage' => '/assets/virtual-tour-placeholder.jpg',
    'previewAlt' => 'ElderCare SG facility virtual tour',
    'hotspots' => [],
    'transcriptUrl' => null,
])

@php
    $highlights = array_filter($highlights);

    $resolvePublicAsset = function (?string $path, string $fallback) {
        if (! $path) {
            return $fallback;
        }

        $normalized = ltrim($path, '/');

        return file_exists(public_path($normalized)) ? '/' . $normalized : $fallback;
    };

    $resolvedPreviewImage = $resolvePublicAsset($previewImage, 'https://placehold.co/800x450?text=Tour+Preview');

    $resolvedHotspots = collect($hotspots)->map(function ($hotspot) use ($resolvePublicAsset) {
        if (! isset($hotspot['media'])) {
            return $hotspot;
        }

        $media = $hotspot['media'];

        if (($media['type'] ?? 'image') === 'image') {
            $media['src'] = $resolvePublicAsset($media['src'] ?? null, 'https://placehold.co/800x450?text=Hotspot');
        }

        if (($media['type'] ?? null) === 'video') {
            $media['poster'] = $resolvePublicAsset($media['poster'] ?? null, 'https://placehold.co/800x450?text=Video');
            $media['src'] = $resolvePublicAsset($media['src'] ?? null, '');
            $media['captions'] = $resolvePublicAsset($media['captions'] ?? null, '');
        }

        $hotspot['media'] = $media;

        return $hotspot;
    })->all();
@endphp

<section
    id="{{ $sectionId }}"
    class="bg-white py-16"
    x-data="tourComponent({
        hotspots: @js($resolvedHotspots),
        transcriptUrl: @js($transcriptUrl),
        analyticsNamespace: 'tour',
    })"
    x-init="init()"
>
    <div class="mx-auto max-w-section grid gap-8 px-6 lg:grid-cols-2 lg:items-center">
        <div class="relative overflow-hidden rounded-3xl shadow-card">
            <img
                src="{{ $resolvedPreviewImage }}"
                alt="{{ $previewAlt }}"
                class="h-full w-full object-cover"
            >
            <button
                type="button"
                class="absolute inset-0 flex items-center justify-center bg-trust/40 text-white transition hover:bg-trust/60 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white"
                @click="open()"
                :aria-expanded="isOpen"
            >
                <span class="inline-flex items-center gap-3 rounded-full bg-white/90 px-5 py-3 text-sm font-semibold text-trust">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.25 5.653c0-1.392 1.5-2.257 2.708-1.544l9.512 5.347a1.785 1.785 0 0 1 0 3.09l-9.512 5.347A1.785 1.785 0 0 1 5.25 16.348z" />
                    </svg>
                    {{ $ctaLabel }}
                </span>
            </button>
        </div>

        <div class="space-y-4">
            <p class="pill-tag">{{ $kicker }}</p>
            <h2 class="text-3xl font-semibold text-trust sm:text-4xl">{{ $headline }}</h2>

            @if ($description)
                <p class="text-slate">{{ $description }}</p>
            @endif

            @if (!empty($highlights))
                <ul class="space-y-2 text-slate">
                    @foreach ($highlights as $highlight)
                        <li class="flex items-start gap-2">
                            <span class="mt-1 h-2 w-2 rounded-full bg-gold"></span>
                            <span>{{ $highlight }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif

            <a href="{{ $ctaHref }}" class="cta-button">Schedule a guided visit</a>
        </div>
    </div>

    <div
        x-cloak
        x-show="isOpen"
        x-transition.opacity.duration.200ms
        class="fixed inset-0 z-50 flex items-center justify-center bg-trust/80 px-4 py-8"
        role="dialog"
        aria-modal="true"
        :aria-labelledby="dialogLabelId"
        @keydown.escape.window.prevent="close()"
    >
        <div class="relative flex w-full max-w-4xl flex-col gap-6 rounded-3xl bg-white p-6 shadow-2xl" @click.away="close()">
            <header class="flex items-start justify-between gap-4">
                <div>
                    <p class="pill-tag inline-flex">{{ $kicker }}</p>
                    <h3 class="mt-2 text-2xl font-semibold text-trust" :id="dialogLabelId">{{ $headline }}</h3>
                </div>
                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate/5 text-slate transition hover:bg-slate/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-trust"
                    @click="close()"
                >
                    <span class="sr-only">Close tour</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </header>

            <div class="grid gap-4 lg:grid-cols-5 lg:gap-6">
                <nav class="flex flex-row gap-3 overflow-x-auto lg:col-span-2 lg:flex-col" aria-label="Tour hotspots">
                    <template x-for="hotspot in hotspots" :key="hotspot.id">
                        <button
                            type="button"
                            class="hotspot-pill"
                            @click="selectHotspot(hotspot.id)"
                            @keydown="handleHotspotKeydown($event)"
                            :class="{ 'hotspot-pill--active': hotspot.id === activeHotspotId }"
                            data-hotspot-button
                            :data-hotspot-id="hotspot.id"
                        >
                            <span class="text-sm font-semibold text-trust" x-text="hotspot.title"></span>
                            <span class="text-xs text-slate" x-text="hotspot.description"></span>
                        </button>
                    </template>
                </nav>

                <div class="space-y-3 rounded-2xl bg-canvas p-4 lg:col-span-3">
                    <template x-if="activeHotspot">
                        <div>
                            <h4 class="text-lg font-semibold text-trust" x-text="activeHotspot.title"></h4>
                            <p class="mt-2 text-slate" x-text="activeHotspot.description"></p>

                            <div class="mt-4">
                                <template x-if="activeHotspot.media?.type === 'video'">
                                    <video
                                        class="w-full rounded-2xl"
                                        controls
                                        playsinline
                                        :poster="activeHotspot.media?.poster ?? ''"
                                    >
                                        <source :src="activeHotspot.media?.src" type="video/mp4">
                                        <track kind="captions" :src="activeHotspot.media?.captions" srclang="en" label="English captions">
                                        Your browser does not support the video tag.
                                    </video>
                                </template>
                                <template x-if="activeHotspot.media?.type !== 'video'">
                                    <img
                                        :src="activeHotspot.media?.src"
                                        :alt="activeHotspot.media?.alt ?? activeHotspot.title"
                                        class="w-full rounded-2xl object-cover"
                                    >
                                </template>
                            </div>

                            <div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-slate">
                                <template x-if="transcriptUrl">
                                    <a :href="`${transcriptUrl}${activeHotspot.anchor || ''}`" class="inline-flex items-center gap-2 font-semibold text-trust hover:text-gold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-9a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 4.5 21h9a2.25 2.25 0 0 0 2.25-2.25V15" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9l6-6m0 0v3.75M18 3h-3.75" />
                                        </svg>
                                        View transcript
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="!activeHotspot">
                        <p class="text-sm text-slate">Select a hotspot to explore rooms, programs, and services.</p>
                    </template>
                </div>
            </div>
        </div>
    </div>
</section>
