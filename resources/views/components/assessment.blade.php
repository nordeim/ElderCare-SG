@props([
    'headline' => __('assessment.headline'),
    'description' => __('assessment.description'),
    'ctaLabel' => __('assessment.cta_label'),
    'infoHref' => '#programs',
])

@php
    $config = [
        'labels' => trans('assessment.labels'),
        'steps' => trans('assessment.steps'),
        'segments' => trans('assessment.segments'),
        'summary' => trans('assessment.summary'),
        'analytics' => trans('assessment.analytics'),
    ];
@endphp

<section
    id="assessment"
    class="bg-white py-16"
    x-data="assessmentFlow(@js($config))"
>
    <div class="mx-auto max-w-section px-6">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_minmax(0,420px)] lg:items-center">
            <div class="space-y-5">
                <p class="pill-tag">{{ __('assessment.pill_label') }}</p>
                <h2 class="text-3xl font-semibold text-trust sm:text-4xl">{{ $headline }}</h2>
                <p class="text-lg text-slate">{{ $description }}</p>

                <ul class="space-y-3 text-slate">
                    <li class="flex items-start gap-3">
                        <span class="mt-1 h-2 w-2 rounded-full bg-gold"></span>
                        <span>{{ __('assessment.benefits.personalized_programs') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-1 h-2 w-2 rounded-full bg-gold"></span>
                        <span>{{ __('assessment.benefits.clinical_insights') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-1 h-2 w-2 rounded-full bg-gold"></span>
                        <span>{{ __('assessment.benefits.follow_up') }}</span>
                    </li>
                </ul>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <button
                        type="button"
                        class="cta-button"
                        @click="open()"
                    >
                        {{ $ctaLabel }}
                    </button>
                    <a href="{{ $infoHref }}" class="inline-flex items-center gap-2 text-sm font-semibold text-trust hover:text-gold">
                        <span>{{ __('assessment.secondary_link') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.25 9.75L17.25 12L14.25 14.25M6.75 12H17.25" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="section-card space-y-4" aria-live="polite">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gold text-trust">01</span>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-trust">{{ __('assessment.preview.step_one.title') }}</p>
                        <p class="text-sm text-slate">{{ __('assessment.preview.step_one.copy') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gold text-trust">02</span>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-trust">{{ __('assessment.preview.step_two.title') }}</p>
                        <p class="text-sm text-slate">{{ __('assessment.preview.step_two.copy') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gold text-trust">03</span>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-trust">{{ __('assessment.preview.step_three.title') }}</p>
                        <p class="text-sm text-slate">{{ __('assessment.preview.step_three.copy') }}</p>
                    </div>
                </div>
                <p class="text-sm text-slate">{{ __('assessment.preview.footer_note') }}</p>
            </div>
        </div>
    </div>

    <div
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-dark/50 px-4 py-8 text-left"
        x-show="isOpen"
        x-transition.opacity
        x-cloak
        @keydown.escape.prevent.stop="close()"
        role="dialog"
        aria-modal="true"
        aria-labelledby="assessment-modal-title"
    >
        <div class="relative w-full max-w-3xl overflow-hidden rounded-3xl bg-white shadow-card" x-trap.noscroll="isOpen">
            <div class="flex items-center justify-between border-b border-slate/15 px-8 py-6">
                <div>
                    <h3 id="assessment-modal-title" class="text-2xl font-semibold text-trust">
                        {{ __('assessment.modal.title') }}
                    </h3>
                    <p class="text-sm text-slate">{{ __('assessment.modal.description') }}</p>
                </div>
                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate/10 text-slate hover:bg-slate/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold"
                    @click="close()"
                >
                    <span class="sr-only">{{ __('assessment.accessibility.close_label') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="max-h-[80vh] overflow-y-auto px-8 py-6" x-data>
                <template x-if="state === 'questions'">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between text-sm font-medium text-slate" role="status" aria-live="polite">
                            <span x-text="progressLabel"></span>
                            <button type="button" class="text-trust underline decoration-2 underline-offset-4 hover:text-gold" @click="skip()">{{ __('assessment.labels.skip') }}</button>
                        </div>

                        <template x-if="currentStep">
                            <div class="space-y-6" :aria-describedby="`step-${currentStep.id}-description`">
                                <div>
                                    <p class="pill-tag inline-flex">{{ __('assessment.labels.step_prefix') }} <span x-text="currentIndex + 1"></span> {{ __('assessment.labels.step_separator') }} <span x-text="totalSteps"></span></p>
                                    <h4 class="mt-3 text-xl font-semibold text-trust" :id="`step-${currentStep.id}-title`" x-text="currentStep.title"></h4>
                                    <p class="mt-2 text-slate" :id="`step-${currentStep.id}-description`" x-text="currentStep.question"></p>
                                </div>

                                <div class="space-y-3" role="group" :aria-labelledby="`step-${currentStep.id}-title`">
                                    <template x-for="option in options" :key="option.value">
                                        <button
                                            type="button"
                                            class="flex w-full items-center justify-between rounded-2xl border px-5 py-4 text-left transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold"
                                            :class="isSelected(option.value) ? 'border-gold bg-gold text-trust' : 'border-slate/15 hover:border-gold/40'"
                                            :aria-pressed="isSelected(option.value)"
                                            @click="toggleOption(option.value)"
                                        >
                                            <span class="font-medium" x-text="option.label"></span>
                                            <template x-if="currentStep.type === 'multi'">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" :class="isSelected(option.value) ? 'text-trust' : 'text-slate/60'">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m4.5 12.75 6 6 9-13.5" />
                                                </svg>
                                            </template>
                                            <template x-if="currentStep.type === 'single'">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" :class="isSelected(option.value) ? 'text-trust' : 'text-slate/60'">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.75a5.25 5.25 0 1 1 0 10.5 5.25 5.25 0 0 1 0-10.5Z" />
                                                    <circle cx="12" cy="12" r="2.25" fill="currentColor" x-show="isSelected(option.value)"></circle>
                                                </svg>
                                            </template>
                                        </button>
                                    </template>
                                </div>

                                <div class="flex flex-col gap-3 border-t border-slate/10 pt-4 sm:flex-row sm:justify-between">
                                    <div class="flex gap-3">
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-2 rounded-full border border-slate/20 px-5 py-3 text-sm font-semibold text-slate hover:text-trust focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold disabled:opacity-50"
                                            :disabled="currentIndex === 0"
                                            @click="back()"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                            </svg>
                                            {{ __('assessment.labels.back') }}
                                        </button>
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-2 rounded-full border border-slate/20 px-5 py-3 text-sm font-semibold text-slate hover:text-trust focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold"
                                            @click="restart()"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 8.25 7.5 5.25 10.5 8.25" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 15.75 16.5 18.75 13.5 15.75" />
                                            </svg>
                                            {{ __('assessment.labels.restart') }}
                                        </button>
                                    </div>

                                    <button
                                        type="button"
                                        class="cta-button"
                                        :disabled="!canProceed"
                                        @click="next()"
                                        x-text="isFinalStep ? labels.complete : labels.next"
                                    ></button>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="state === 'summary'">
                    <div class="space-y-6">
                        <div>
                            <p class="pill-tag inline-flex" x-text="summary.segment.name"></p>
                            <h4 class="mt-3 text-2xl font-semibold text-trust">{{ trans('assessment.summary.title') }}</h4>
                            <p class="mt-2 text-slate">{{ trans('assessment.summary.intro') }}</p>
                        </div>

                        <div class="rounded-3xl bg-canvas p-6 space-y-5">
                            <div>
                                <h5 class="text-sm font-semibold uppercase tracking-wide text-trust">{{ trans('assessment.summary.programs_heading') }}</h5>
                                <ul class="mt-3 space-y-2 text-slate">
                                    <template x-for="program in summary.segment.programs" :key="program">
                                        <li class="flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-gold"></span>
                                            <span x-text="program"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            <div>
                                <h5 class="text-sm font-semibold uppercase tracking-wide text-trust">{{ trans('assessment.summary.highlights_heading') }}</h5>
                                <ul class="mt-3 space-y-2 text-slate">
                                    <template x-for="highlight in summary.segment.highlights" :key="highlight">
                                        <li class="flex items-start gap-2">
                                            <span class="mt-1 h-2 w-2 rounded-full bg-gold"></span>
                                            <span x-text="highlight"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between">
                            <button type="button" class="inline-flex items-center gap-2 rounded-full border border-slate/20 px-5 py-3 text-sm font-semibold text-slate hover:text-trust focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold" @click="restart()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 8.25 7.5 5.25 10.5 8.25" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 15.75 16.5 18.75 13.5 15.75" />
                                </svg>
                                {{ __('assessment.labels.restart') }}
                            </button>

                            <a :href="summary.segment.cta_href || summary.fallbackCta" class="cta-button inline-flex justify-center" x-text="summary.segment.cta_label || summary.fallbackLabel"></a>
                        </div>

                        <template x-if="submissionState === 'pending'">
                            <p class="text-sm text-slate" role="status">{{ __('assessment.submission.pending') }}</p>
                        </template>
                        <template x-if="submissionState === 'success'">
                            <p class="text-sm text-wellness" role="status">{{ __('assessment.submission.success') }}</p>
                        </template>
                        <template x-if="submissionState === 'error'">
                            <p class="text-sm text-red-600" role="alert">
                                {{ __('assessment.submission.error') }}
                                <span x-text="submissionError"></span>
                            </p>
                        </template>
                    </div>
                </template>

                <template x-if="state === 'intro'">
                    <div class="space-y-4 text-center">
                        <p class="pill-tag mx-auto inline-flex">{{ __('assessment.pill_label') }}</p>
                        <p class="text-slate">{{ __('assessment.modal.placeholder_copy') }}</p>
                        <button type="button" class="cta-button" @click="start()">{{ __('assessment.cta_label') }}</button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</section>
