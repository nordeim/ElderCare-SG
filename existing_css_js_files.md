# vite.config.js
```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        visualizer({
            filename: 'stats.html',
            open: false,
            gzipSize: true,
            brotliSize: true,
        }),
    ],
});

```

# resources/css/app.css
```css
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
    :root {
        color-scheme: light;
        --color-trust: 28 61 90;
        --color-gold: 240 165 0;
        --color-amber: 252 223 166;
        --color-wellness: 61 154 116;
        --color-canvas: 247 249 252;
        --color-slate: 100 116 139;
        --color-slate-dark: 51 65 85;
        background-color: rgb(var(--color-canvas));
    }

    html {
        @apply scroll-smooth;
    }

    body {
        @apply bg-canvas font-body text-body-md text-slate-dark antialiased;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        @apply font-heading text-trust;
    }

    a {
        @apply text-trust underline-offset-4 transition-colors duration-200 ease-out;
    }

    a:hover {
        @apply text-gold;
    }

    ::selection {
        background-color: theme('colors.gold', '#F0A500');
        color: white;
    }

    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
    }

    [x-cloak] {
        display: none !important;
    }
}

@layer components {
    .cta-button {
        @apply inline-flex items-center justify-center rounded-full bg-gold px-6 py-3 font-semibold uppercase tracking-wide text-trust shadow-card transition duration-300 ease-out hover:bg-gold/90 hover:text-trust focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-gold;
    }

    .pill-tag {
        @apply inline-flex items-center rounded-full bg-trust px-3 py-1 text-sm font-medium text-white shadow-sm;
    }

    .availability-badge {
        @apply inline-flex items-center gap-3 rounded-full border border-white/40 bg-white/10 px-4 py-2 text-sm font-medium backdrop-blur;
    }

    .availability-indicator {
        @apply inline-flex h-3 w-3 items-center justify-center rounded-full bg-white/40;
    }

    .availability-indicator::after {
        content: '';
        @apply block h-2 w-2 rounded-full transition;
    }

    .availability-indicator--loading::after {
        @apply animate-ping bg-white;
    }

    .availability-indicator--error::after {
        @apply bg-red-400;
    }

    .availability-indicator--fallback::after {
        @apply bg-amber-300;
    }

    .availability-indicator--high::after {
        @apply bg-emerald-300;
    }

    .availability-indicator--medium::after {
        @apply bg-yellow-300;
    }

    .availability-indicator--low::after {
        @apply bg-orange-300;
    }

    .availability-indicator--none::after {
        @apply bg-red-300;
    }

    .section-card {
        @apply rounded-3xl bg-white p-8 shadow-card ring-1 ring-slate/10;
    }

    .hotspot-pill {
        @apply flex min-w-[12rem] flex-col gap-1 rounded-2xl bg-white px-4 py-3 text-left shadow-card ring-1 ring-slate/10 transition hover:bg-gold/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold;
    }

    .hotspot-pill--active {
        @apply bg-gold/10 ring-gold;
    }

    .staff-carousel {
        @apply relative;
    }

    .staff-carousel__viewport {
        @apply overflow-hidden;
    }

    .staff-carousel__container {
        @apply flex gap-6 transition-transform;
    }

    .staff-carousel__slide {
        @apply basis-full md:basis-2/3 lg:basis-1/2;
    }

    .toggle-switch {
        @apply h-6 w-11 rounded-full bg-slate/30 transition-all duration-200 ease-out;
    }

    .peer:checked + .toggle-switch {
        @apply bg-trust;
    }

    .toggle-switch::after {
        content: '';
        @apply block h-5 w-5 translate-x-[2px] rounded-full bg-white transition-all duration-200 ease-out;
    }

    .peer:checked + .toggle-switch::after {
        @apply translate-x-[24px];
    }

    .faq-item {
        @apply rounded-2xl border border-slate/10 bg-white p-4 transition hover:border-gold/40;
    }

    .faq-item--active {
        @apply border-gold/60 shadow-inner;
    }

    .faq-item__trigger {
        @apply flex w-full items-center justify-between gap-4 text-left text-base font-semibold text-trust focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold;
    }

    .faq-item__content {
        @apply mt-3 border-t border-slate/10 pt-3 text-sm;
    }

    .resource-card {
        @apply flex h-full flex-col justify-between gap-6 rounded-3xl bg-white p-6 shadow-card ring-1 ring-slate/10 transition hover:-translate-y-1 hover:shadow-lg;
    }

    .resource-card__tag {
        @apply inline-flex items-center rounded-full bg-trust/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-trust;
    }

    .resource-card__meta {
        @apply flex flex-col gap-1;
    }

    .resource-card__cta {
        @apply inline-flex items-center justify-between gap-2 rounded-full bg-trust px-4 py-3 text-sm font-semibold text-white transition hover:bg-gold hover:text-trust focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-gold;
    }

    .resource-card__cta[data-disabled="true"] {
        @apply pointer-events-none cursor-default bg-slate/30 text-slate;
    }
}

@layer utilities {
    .content-visibility-auto {
        content-visibility: auto;
        contain-intrinsic-size: 960px 720px;
    }

    .fade-in-up {
        @apply animate-fade-in-up;
    }
}

```

# resources/js/app.js
```js
import './bootstrap';
import './modules/carousel';
import './modules/assessment';
import './modules/assessment-recommendation';
import './modules/availability';
import './modules/tour';
import './modules/cost-estimator';
import './modules/hero';

import './modules/analytics';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

```

# resources/js/bootstrap.js
```js
let axiosPromise;

const configureAxiosDefaults = (instance) => {
    if (!instance?.defaults) {
        return instance;
    }

    instance.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    const token = document.head ? document.head.querySelector('meta[name="csrf-token"]') : null;

    if (token) {
        instance.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    }

    return instance;
};

window.loadAxios = async () => {
    if (typeof window.axios === 'function') {
        return configureAxiosDefaults(window.axios);
    }

    if (!axiosPromise) {
        axiosPromise = import('axios')
            .then((module) => {
                const axiosInstance = configureAxiosDefaults(module.default ?? module);
                window.axios = axiosInstance;
                return axiosInstance;
            })
            .catch((error) => {
                console.error('Failed to load axios module', error);
                return null;
            });
    }

    return axiosPromise;
};

```

# resources/js/modules/tour.js
```js
const createAnalyticsEmitter = (namespace = 'tour') => {
    if (window.eldercareAnalytics?.createEmitter) {
        return window.eldercareAnalytics.createEmitter(namespace);
    }

    if (window.eldercareAnalytics?.emit) {
        return (event, detail = {}) => {
            window.eldercareAnalytics.emit(`${namespace}.${event}`, detail);
        };
    }

    return () => {};
};

const normalizeHotspots = (hotspots) => {
    if (!Array.isArray(hotspots)) {
        return [];
    }

    return hotspots.filter((item) => item && item.id);
};

const tourComponent = (options = {}) => {
    const config = {
        hotspots: [],
        transcriptUrl: null,
        analyticsNamespace: 'tour',
        ...options,
    };

    return {
        isOpen: false,
        hotspots: normalizeHotspots(config.hotspots),
        transcriptUrl: config.transcriptUrl,
        analyticsNamespace: config.analyticsNamespace ?? 'tour',
        activeHotspotId: null,
        dialogLabelId: `tour-${Math.random().toString(36).slice(2)}-label`,
        visitedHotspotIds: new Set(),
        completeEmitted: false,
        lastFocusedElement: null,
        analyticsEmitter: createAnalyticsEmitter(config.analyticsNamespace ?? 'tour'),

        init() {
            if (this.hotspots.length > 0) {
                this.activeHotspotId = this.hotspots[0].id;
            }
        },

        get activeHotspot() {
            return this.hotspots.find((hotspot) => hotspot.id === this.activeHotspotId) ?? null;
        },

        open() {
            if (this.isOpen) {
                return;
            }

            if (!this.activeHotspotId && this.hotspots.length > 0) {
                this.activeHotspotId = this.hotspots[0].id;
            }

            this.lastFocusedElement = document.activeElement instanceof HTMLElement ? document.activeElement : null;
            this.isOpen = true;
            document.body.style.setProperty('overflow', 'hidden');

            if (this.activeHotspotId) {
                this.visitedHotspotIds.add(this.activeHotspotId);
            }

            this.analyticsEmitter('open', {
                hotspotId: this.activeHotspotId,
                totalHotspots: this.hotspots.length,
            });

            this.$nextTick(() => {
                const buttons = this.getHotspotButtons();
                if (buttons.length > 0) {
                    buttons[0].focus();
                }
            });
        },

        close() {
            if (!this.isOpen) {
                return;
            }

            this.isOpen = false;
            document.body.style.removeProperty('overflow');

            this.$nextTick(() => {
                if (this.lastFocusedElement) {
                    this.lastFocusedElement.focus();
                }
            });
        },

        selectHotspot(id) {
            if (!id || this.activeHotspotId === id) {
                return;
            }

            this.activeHotspotId = id;
            this.visitedHotspotIds.add(id);

            this.analyticsEmitter('hotspot', {
                hotspotId: id,
                totalVisited: this.visitedHotspotIds.size,
                totalHotspots: this.hotspots.length,
            });

            if (!this.completeEmitted && this.visitedHotspotIds.size === this.hotspots.length && this.hotspots.length > 0) {
                this.completeEmitted = true;
                this.analyticsEmitter('complete', {
                    totalHotspots: this.hotspots.length,
                });
            }
        },

        navigateHotspots(delta) {
            if (!this.hotspots.length) {
                return;
            }

            const currentIndex = this.hotspots.findIndex((hotspot) => hotspot.id === this.activeHotspotId);
            const nextIndex = (currentIndex + delta + this.hotspots.length) % this.hotspots.length;
            const nextHotspot = this.hotspots[nextIndex];

            if (!nextHotspot) {
                return;
            }

            this.activeHotspotId = nextHotspot.id;
            this.visitedHotspotIds.add(nextHotspot.id);

            this.analyticsEmitter('hotspot', {
                hotspotId: nextHotspot.id,
                totalVisited: this.visitedHotspotIds.size,
                totalHotspots: this.hotspots.length,
            });

            if (!this.completeEmitted && this.visitedHotspotIds.size === this.hotspots.length && this.hotspots.length > 0) {
                this.completeEmitted = true;
                this.analyticsEmitter('complete', {
                    totalHotspots: this.hotspots.length,
                });
            }

            this.$nextTick(() => {
                const buttons = this.getHotspotButtons();
                const activeButton = buttons.find((button) => button.dataset.hotspotId === nextHotspot.id);

                if (activeButton) {
                    activeButton.focus();
                }
            });
        },

        handleHotspotKeydown(event) {
            if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
                event.preventDefault();
                this.navigateHotspots(1);
            }

            if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
                event.preventDefault();
                this.navigateHotspots(-1);
            }
        },

        getHotspotButtons() {
            return Array.from(this.$root.querySelectorAll('[data-hotspot-button]'));
        },
    };
};

window.tourComponent = tourComponent;

export { tourComponent };

```

# resources/js/modules/assessment-recommendation.js
```js
import Alpine from 'alpinejs';

const createStore = () => ({
    segmentKey: null,
    segment: null,
    summary: null,
    updatedAt: null,
    fallbackLabel: 'Book a visit',
    fallbackHref: '#booking',
    setFallback(summary) {
        if (!summary) {
            return;
        }

        if (summary.fallbackLabel) {
            this.fallbackLabel = summary.fallbackLabel;
        }

        if (summary.fallbackCta) {
            this.fallbackHref = summary.fallbackCta;
        }

        this.summary = summary;
    },
    apply(detail) {
        if (!detail || !detail.segmentKey || !detail.segment) {
            this.reset();
            return;
        }

        this.segmentKey = detail.segmentKey;
        this.segment = detail.segment;
        if (detail.summary) {
            this.setFallback(detail.summary);
        }
        this.updatedAt = new Date().toISOString();
    },
    reset() {
        Object.assign(this, {
            segmentKey: null,
            segment: null,
            summary: null,
            updatedAt: null,
        });
    },
    get hasRecommendation() {
        return Boolean(this.segmentKey && this.segment);
    },
    get primaryCtaLabel() {
        return this.segment?.cta_label ?? this.summary?.fallbackLabel ?? this.fallbackLabel;
    },
    get primaryCtaHref() {
        return this.segment?.cta_href ?? this.summary?.fallbackCta ?? this.fallbackHref;
    },
    get programs() {
        return Array.isArray(this.segment?.programs) ? this.segment.programs : [];
    },
    get highlights() {
        return Array.isArray(this.segment?.highlights) ? this.segment.highlights : [];
    },
});

const registerListeners = () => {
    const recommendationStore = Alpine.store('assessmentRecommendation');

    if (!recommendationStore) {
        return;
    }

    const applyDetail = (detail) => {
        if (!detail) {
            return;
        }

        if (detail.summary) {
            recommendationStore.setFallback(detail.summary);
        }

        if (detail.segmentKey && detail.segment) {
            recommendationStore.apply(detail);
        }
    };

    const resetStore = () => {
        recommendationStore.reset();
    };

    window.addEventListener('assessment.complete', (event) => applyDetail(event.detail));
    window.addEventListener('assessment.skip', (event) => applyDetail(event.detail));
    window.addEventListener('assessment.submitted', (event) => applyDetail(event.detail));
    window.addEventListener('assessment.restart', resetStore);
    window.addEventListener('assessment.close', resetStore);
    window.addEventListener('assessment.open', resetStore);
};

if (typeof window !== 'undefined') {
    document.addEventListener('alpine:init', () => {
        if (!Alpine.store('assessmentRecommendation')) {
            Alpine.store('assessmentRecommendation', createStore());
        }

        registerListeners();
    }, { once: true });
}

```

# resources/js/modules/analytics.js
```js
const dispatchBrowserEvent = (name, detail = {}) => {
    if (typeof window.dispatchEvent === 'function') {
        window.dispatchEvent(new CustomEvent(name, { detail }));
    }
};

const emit = (name, detail = {}) => {
    dispatchBrowserEvent(name, detail);

    if (typeof window.plausible === 'function') {
        window.plausible(name, { props: detail });
    }
};

const createEmitter = (namespace = 'app') => {
    return (event, detail = {}) => emit(`${namespace}.${event}`, detail);
};

export { emit, createEmitter };

if (!window.eldercareAnalytics) {
    window.eldercareAnalytics = { emit, createEmitter };
}

```

# resources/js/modules/assessment.js
```js
const clone = (value) => JSON.parse(JSON.stringify(value));

const defaultAnalyticsEmitter = (namespace) => {
    const prefix = namespace ? `${namespace}` : 'assessment';

    return (event, detail = {}) => {
        const name = `${prefix}.${event}`;

        if (typeof window.dispatchEvent === 'function') {
            window.dispatchEvent(new CustomEvent(name, { detail }));
        }

        if (typeof window.plausible === 'function') {
            window.plausible(name, { props: detail });
        }
    };
};

const buildInitialAnswers = (steps) => {
    return steps.reduce((accumulator, step) => {
        accumulator[step.id] = step.type === 'multi' ? [] : null;
        return accumulator;
    }, {});
};

const rules = {
    active_day: (answers) => answers.mobility === 'independent' && answers.cognitive_support === 'engaged',
    memory_care: (answers) => answers.cognitive_support === 'dementia',
    supportive_care: (answers) =>
        answers.mobility === 'assistance' ||
        answers.mobility === 'full_support' ||
        (Array.isArray(answers.health_considerations) && answers.health_considerations.includes('mobility_aids')),
    respite_support: (answers) =>
        answers.transportation === 'yes' ||
        (Array.isArray(answers.caregiver_goals) && answers.caregiver_goals.includes('respite')),
};

const fallbackSegmentKey = 'exploration';

const determineSegmentKey = (answers) => {
    if (rules.memory_care(answers)) {
        return 'memory_care';
    }

    if (rules.supportive_care(answers)) {
        return 'supportive_care';
    }

    if (rules.respite_support(answers)) {
        return 'respite_support';
    }

    if (rules.active_day(answers)) {
        return 'active_day';
    }

    return fallbackSegmentKey;
};

const createSummary = (segmentKey, segmentsConfig, summaryConfig) => {
    const segment = segmentsConfig[segmentKey] ?? segmentsConfig[fallbackSegmentKey];

    return {
        segmentKey,
        segment,
        fallbackLabel: summaryConfig.cta_fallback,
        fallbackCta: '#booking',
    };
};

export const assessmentFlow = (config) => {
    const steps = config.steps ?? [];
    const labels = config.labels ?? {};
    const emitter = defaultAnalyticsEmitter(config.analytics?.event_namespace);

    return {
        isOpen: false,
        state: 'intro',
        currentIndex: 0,
        answers: buildInitialAnswers(steps),
        summary: null,
        submissionState: 'idle',
        submissionError: null,
        get totalSteps() {
            return steps.length;
        },
        get currentStep() {
            if (this.state !== 'questions') {
                return null;
            }

            return steps[this.currentIndex] ?? null;
        },
        get options() {
            if (!this.currentStep) {
                return [];
            }

            const options = this.currentStep.options ?? [];
            const extras = this.currentStep.options_extra ?? [];

            return [...options, ...extras];
        },
        get canProceed() {
            if (this.state !== 'questions' || !this.currentStep) {
                return false;
            }

            const answer = this.answers[this.currentStep.id];

            if (this.currentStep.type === 'multi') {
                return Array.isArray(answer) && answer.length > 0;
            }

            return Boolean(answer);
        },
        get isFinalStep() {
            return this.currentIndex === this.totalSteps - 1;
        },
        get progressLabel() {
            return `${labels.step_prefix ?? 'Step'} ${this.currentIndex + 1} ${labels.step_separator ?? 'of'} ${this.totalSteps}`;
        },
        open() {
            this.isOpen = true;
            this.state = 'intro';
            this.currentIndex = 0;
            this.answers = buildInitialAnswers(steps);
            this.summary = null;
            this.submissionState = 'idle';
            this.submissionError = null;
            emitter('open', { segmentKey: null });
        },
        close() {
            this.isOpen = false;
            this.state = 'intro';
            this.currentIndex = 0;
            this.answers = buildInitialAnswers(steps);
            this.summary = null;
            this.submissionState = 'idle';
            this.submissionError = null;
            emitter('close', { segmentKey: null });
        },
        start() {
            this.state = 'questions';
            this.currentIndex = 0;
            emitter('start', { step: steps[0]?.id });
        },
        skip() {
            this.state = 'summary';
            const segmentKey = determineSegmentKey(this.answers);
            this.summary = createSummary(segmentKey, config.segments, config.summary);
            emitter('skip', {
                segmentKey,
                summary: this.summary,
                segment: this.summary.segment,
                answers: clone(this.answers),
            });
        },
        restart() {
            const previousSegmentKey = this.summary?.segmentKey ?? null;
            const previousSegment = this.summary?.segment ?? null;
            this.state = 'questions';
            this.currentIndex = 0;
            this.answers = buildInitialAnswers(steps);
            this.summary = null;
            this.submissionState = 'idle';
            this.submissionError = null;
            emitter('restart', {
                step: steps[0]?.id,
                previousSegmentKey,
                previousSegment,
            });
        },
        back() {
            if (this.currentIndex === 0) {
                this.state = 'intro';
                emitter('back_to_intro');
                return;
            }

            this.currentIndex -= 1;
            emitter('step_back', { step: steps[this.currentIndex]?.id });
        },
        next() {
            if (!this.currentStep) {
                return;
            }

            emitter('step_submit', {
                step: this.currentStep.id,
                answer: clone(this.answers[this.currentStep.id]),
            });

            if (this.isFinalStep) {
                this.complete();
                return;
            }

            this.currentIndex += 1;
            emitter('step_enter', { step: steps[this.currentIndex]?.id });
        },
        complete() {
            const segmentKey = determineSegmentKey(this.answers);
            this.summary = createSummary(segmentKey, config.segments, config.summary);
            this.state = 'summary';
            this.submissionState = 'pending';
            this.submissionError = null;

            emitter('complete', {
                segmentKey,
                summary: this.summary,
                segment: this.summary.segment,
                answers: clone(this.answers),
            });

            this.submitOutcome(segmentKey).catch(() => {
                // handled within submitOutcome
            });
        },
        toggleOption(value) {
            if (!this.currentStep) {
                return;
            }

            if (this.currentStep.type === 'multi') {
                const answerSet = new Set(this.answers[this.currentStep.id] ?? []);

                if (answerSet.has(value)) {
                    answerSet.delete(value);
                } else {
                    answerSet.add(value);
                }

                this.answers[this.currentStep.id] = Array.from(answerSet);
            } else {
                this.answers[this.currentStep.id] = this.answers[this.currentStep.id] === value ? null : value;
            }
        },
        isSelected(value) {
            if (!this.currentStep) {
                return false;
            }

            const answer = this.answers[this.currentStep.id];

            if (this.currentStep.type === 'multi') {
                return Array.isArray(answer) && answer.includes(value);
            }

            return answer === value;
        },
        async submitOutcome(segmentKey) {
            try {
                this.submissionState = 'pending';
                this.submissionError = null;

                const axiosLoader = typeof window.loadAxios === 'function'
                    ? window.loadAxios()
                    : import('axios')
                        .then((module) => module.default ?? module)
                        .catch((error) => {
                            console.error('Failed to load axios for assessment submission', error);
                            return null;
                        });

                const axiosInstance = await axiosLoader;

                if (!axiosInstance) {
                    this.submissionState = 'skipped';
                    return;
                }

                await axiosInstance.post('/assessment-insights', {
                    answers: clone(this.answers),
                    segment_key: segmentKey,
                });

                this.submissionState = 'success';
                emitter('submitted', {
                    segmentKey,
                    summary: this.summary,
                    segment: this.summary?.segment,
                });
            } catch (error) {
                this.submissionState = 'error';
                this.submissionError = error?.message ?? 'Submission failed';
                emitter('submit_error', {
                    segmentKey,
                    error: this.submissionError,
                });
            }
        },
    };
};

if (typeof window !== 'undefined') {
    window.assessmentFlow = assessmentFlow;
}

```

# resources/js/modules/availability.js
```js
import Alpine from 'alpinejs';

const getAvailabilityMessages = () => window.eldercare?.availabilityMessages ?? null;

const resolveFormatterLocale = (lang) => {
    if (!lang) {
        return 'en-SG';
    }

    const normalized = lang.toLowerCase();

    switch (normalized) {
        case 'zh':
        case 'zh-hans':
        case 'zh-sg':
            return 'zh-SG';
        case 'en':
        case 'en-sg':
        default:
            return 'en-SG';
    }
};

const formatUpdatedLabel = (timestamp, locale = 'en-SG') => {
    if (!timestamp) {
        return '';
    }

    const date = new Date(timestamp);

    if (Number.isNaN(date.getTime())) {
        return '';
    }

    return new Intl.DateTimeFormat(locale, {
        weekday: 'short',
        hour: '2-digit',
        minute: '2-digit',
    }).format(date);
};

const createAvailabilityStore = () => ({
    status: 'loading',
    slots: [],
    updatedAt: null,
    isStale: true,
    fallbackMessage: '',
    fallbackUsed: false,
    error: null,
    isLoading: false,
    initialized: false,
    pollId: null,
    locale: resolveFormatterLocale(document.documentElement.lang || 'en'),
    init() {
        if (this.initialized) {
            return;
        }

        this.initialized = true;
        this.fetch(true);
        this.startPolling();

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                this.fetch();
                this.startPolling();
            } else {
                this.stopPolling();
            }
        });
    },
    async fetch(force = false) {
        if (this.isLoading && !force) {
            return;
        }

        this.isLoading = true;
        this.error = null;
        let requestStatus = null;

        try {
            const response = await fetch(`/api/availability${force ? '?refresh=1' : ''}`, {
                headers: {
                    Accept: 'application/json',
                },
            });

            requestStatus = response.status;

            if (!response.ok) {
                throw new Error(`Availability request failed with status ${response.status}`);
            }

            const payload = await response.json();

            this.status = payload.status ?? 'ok';
            this.slots = Array.isArray(payload.slots) ? payload.slots : [];
            this.updatedAt = payload.updated_at ?? null;
            this.isStale = Boolean(payload.is_stale);
            this.fallbackMessage = payload.fallback_message ?? '';
            this.fallbackUsed = Boolean(payload.fallback_used);
            this.error = null;

            const messages = getAvailabilityMessages();
            const detail = {
                force,
                status: this.status,
                total: this.totalAvailable,
                isStale: this.isStale,
                fallbackUsed: this.fallbackUsed,
                updatedAt: this.updatedAt,
                locale: this.locale,
                message: messages?.status?.[this.statusLevel] ?? null,
            };

            window.eldercareAnalytics?.emit('availability.loaded', detail);
        } catch (error) {
            this.error = error instanceof Error ? error.message : 'Availability request failed.';
            window.eldercareAnalytics?.emit('availability.error', {
                force,
                statusCode: requestStatus,
                message: this.error,
                locale: this.locale,
            });
        } finally {
            this.isLoading = false;
        }
    },
    startPolling() {
        this.stopPolling();
        this.pollId = window.setInterval(() => {
            this.fetch();
        }, 180000); // 3 minutes
    },
    stopPolling() {
        if (this.pollId) {
            window.clearInterval(this.pollId);
            this.pollId = null;
        }
    },
    get totalAvailable() {
        return this.slots.reduce((total, slot) => total + Number(slot.available ?? 0), 0);
    },
    get statusLevel() {
        if (this.isLoading && !this.initialized) {
            return 'loading';
        }

        if (this.error) {
            return 'error';
        }

        if (this.fallbackUsed || this.status !== 'ok') {
            return 'fallback';
        }

        const total = this.totalAvailable;

        if (total >= 12) {
            return 'high';
        }

        if (total >= 6) {
            return 'medium';
        }

        if (total >= 1) {
            return 'low';
        }

        return 'none';
    },
    refresh() {
        this.fetch(true);
    },
    get indicatorClass() {
        return `availability-indicator--${this.statusLevel}`;
    },
    get statusLabel() {
        const messages = getAvailabilityMessages();

        switch (this.statusLevel) {
            case 'loading':
                return messages?.status?.loading ?? 'Checking availability…';
            case 'error':
                return messages?.status?.error ?? 'Availability unavailable';
            case 'fallback':
                return messages?.status?.fallback ?? 'Manual confirmation required';
            case 'high':
                return messages?.status?.high ?? 'Ample visit slots available';
            case 'medium':
                return messages?.status?.medium ?? 'Limited slots available';
            case 'low':
                return messages?.status?.low ?? 'Few slots remaining';
            case 'none':
            default:
                return messages?.status?.none ?? 'Currently waitlisting';
        }
    },
    get statusDetail() {
        const messages = getAvailabilityMessages();

        if (this.statusLevel === 'loading') {
            return messages?.detail?.loading ?? 'Hang tight while we fetch the latest visit openings.';
        }

        if (this.statusLevel === 'error') {
            return messages?.detail?.error ?? "We're retrying the scheduling service.";
        }

        if (this.statusLevel === 'fallback') {
            const fallbackCopy = messages?.detail?.fallback ?? 'We will confirm availability within 24 hours.';
            return this.fallbackMessage || fallbackCopy;
        }

        const formattedTime = formatUpdatedLabel(this.updatedAt, this.locale);
        const daysCovered = this.slots.length;
        const slotSingle = messages?.detail?.slot_single ?? 'slot';
        const slotPlural = messages?.detail?.slot_plural ?? 'slots';
        const slotsCopy = this.totalAvailable === 1 ? slotSingle : slotPlural;

        if (!formattedTime) {
            const template = messages?.detail?.summary ?? ':total :slot_word in the next :days days.';
            return template
                .replace(':total', this.totalAvailable)
                .replace(':slot_word', slotsCopy)
                .replace(':days', daysCovered);
        }

        const template = messages?.detail?.summary_with_time ?? ':total :slot_word in the next :days days — updated :time';

        return template
            .replace(':total', this.totalAvailable)
            .replace(':slot_word', slotsCopy)
            .replace(':days', daysCovered)
            .replace(':time', formattedTime);
    },
    get isHealthy() {
        return ['high', 'medium'].includes(this.statusLevel);
    },
});
document.addEventListener('alpine:init', () => {
    if (!Alpine.store('availability')) {
        const store = createAvailabilityStore();
        Alpine.store('availability', store);
        store.init();
    }
});

```

# resources/js/modules/hero.js
```js
const HERO_SELECTOR = '[data-hero]';
const VIDEO_SELECTOR = '[data-hero-video]';
const INTERSECTION_THRESHOLD = 0.3;

const parseHeroSources = (video) => {
    const raw = video?.dataset?.heroSources;

    if (!raw) {
        return [];
    }

    try {
        const parsed = JSON.parse(raw);
        return Array.isArray(parsed) ? parsed : [];
    } catch (error) {
        console.warn('Failed to parse hero video sources', error);
        return [];
    }
};

const ensureHeroSources = (video) => {
    if (!video || video.dataset.heroSourcesAppended === 'true') {
        return;
    }

    const sources = parseHeroSources(video);

    if (!sources.length) {
        return;
    }

    sources.forEach((sourceConfig) => {
        if (!sourceConfig?.src) {
            return;
        }

        const sourceElement = document.createElement('source');
        sourceElement.src = sourceConfig.src;

        if (sourceConfig.type) {
            sourceElement.type = sourceConfig.type;
        }

        video.appendChild(sourceElement);
    });

    video.dataset.heroSourcesAppended = 'true';
};

const playHeroVideo = (video) => {
    if (!video) {
        return;
    }

    ensureHeroSources(video);

    if (!video.dataset.heroLoaded) {
        video.dataset.heroLoaded = 'true';
        video.preload = 'auto';
        video.load();
    }

    const playPromise = video.play();

    if (playPromise?.catch) {
        playPromise.catch(() => {
            // Autoplay might still be blocked in some contexts; ignore silently.
        });
    }
};

const pauseHeroVideo = (video) => {
    if (!video) {
        return;
    }

    if (!video.paused) {
        video.pause();
    }
};

const observeHeroSection = (section, video) => {
    if (!('IntersectionObserver' in window)) {
        playHeroVideo(video);
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                playHeroVideo(video);
            } else {
                pauseHeroVideo(video);
            }
        });
    }, {
        threshold: INTERSECTION_THRESHOLD,
    });

    observer.observe(section);
};

const initHeroMedia = () => {
    const heroSections = document.querySelectorAll(HERO_SELECTOR);

    heroSections.forEach((section) => {
        const video = section.querySelector(VIDEO_SELECTOR);

        if (!video) {
            return;
        }

        if (video.readyState >= HTMLMediaElement.HAVE_METADATA) {
            observeHeroSection(section, video);
            return;
        }

        video.addEventListener('loadedmetadata', () => {
            observeHeroSection(section, video);
        }, { once: true });

        // Ensure metadata request kicks in when intersection observer registers.
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHeroMedia);
} else {
    initHeroMedia();
}

```

# resources/js/modules/cost-estimator.js
```js
const createAnalyticsEmitter = (namespace = 'estimator') => {
    if (window.eldercareAnalytics?.createEmitter) {
        return window.eldercareAnalytics.createEmitter(namespace);
    }

    if (window.eldercareAnalytics?.emit) {
        return (event, detail = {}) => {
            window.eldercareAnalytics.emit(`${namespace}.${event}`, detail);
        };
    }

    return () => {};
};

const normalizePricing = (pricing = {}) => {
    return {
        dailyRate: Number(pricing.dailyRate) || 0,
        transportFee: Number(pricing.transportFee) || 0,
        weeksPerMonth: Number(pricing.weeksPerMonth) || 4.3,
        addOns: Array.isArray(pricing.addOns) ? pricing.addOns : [],
    };
};

const normalizeSubsidies = (subsidies = []) => {
    if (!Array.isArray(subsidies)) {
        return [];
    }

    return subsidies.map((subsidy) => ({
        key: subsidy.key,
        label: subsidy.label,
        rate: Number(subsidy.rate) || 0,
        description: subsidy.description ?? '',
    }));
};

const defaultFormatter = new Intl.NumberFormat('en-SG', {
    style: 'currency',
    currency: 'SGD',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
});

const costEstimatorComponent = (options = {}) => {
    const config = {
        pricing: normalizePricing(options.pricing),
        subsidies: normalizeSubsidies(options.subsidies),
        defaults: options.defaults ?? {},
        analyticsNamespace: options.analyticsNamespace ?? 'estimator',
    };

    const getDefault = (key, fallback) => {
        return config.defaults[key] ?? fallback;
    };

    return {
        pricing: config.pricing,
        subsidies: config.subsidies,
        daysPerWeek: getDefault('daysPerWeek', 3),
        includeTransport: getDefault('includeTransport', true),
        selectedAddOns: Array.isArray(getDefault('selectedAddOns', []))
            ? [...getDefault('selectedAddOns', [])]
            : [],
        selectedSubsidyKey: getDefault('subsidyKey', 'none'),
        showDetails: false,
        analyticsEmitter: createAnalyticsEmitter(config.analyticsNamespace),

        init() {
            this.emitEvent('open');

            this.$watch('daysPerWeek', () => this.emitUpdate());
            this.$watch('includeTransport', () => this.emitUpdate());
            this.$watch('selectedAddOns', () => this.emitUpdate());
            this.$watch('selectedSubsidyKey', () => this.emitUpdate());
        },

        emitEvent(event, detail = {}) {
            this.analyticsEmitter(event, {
                daysPerWeek: this.daysPerWeek,
                includeTransport: this.includeTransport,
                selectedAddOns: this.selectedAddOns,
                subsidyKey: this.selectedSubsidyKey,
                totalMonthly: this.totalMonthly,
                ...detail,
            });
        },

        emitUpdate() {
            this.emitEvent('update');
        },

        toggleDetails() {
            this.showDetails = !this.showDetails;
            this.emitEvent('toggle_details', { showDetails: this.showDetails });
        },

        get selectedSubsidy() {
            return this.subsidies.find((subsidy) => subsidy.key === this.selectedSubsidyKey) ?? null;
        },

        get baseMonthlyCost() {
            const perWeek = this.pricing.dailyRate * this.daysPerWeek;
            return perWeek * this.pricing.weeksPerMonth;
        },

        get transportMonthlyCost() {
            if (!this.includeTransport) {
                return 0;
            }

            const perWeek = this.pricing.transportFee * this.daysPerWeek;
            return perWeek * this.pricing.weeksPerMonth;
        },

        get addOnsMonthlyCost() {
            if (!this.selectedAddOns.length) {
                return 0;
            }

            return this.selectedAddOns.reduce((total, addOnKey) => {
                const addOn = this.pricing.addOns.find((item) => item.key === addOnKey);
                if (!addOn) {
                    return total;
                }

                // Treat add-on amount as weekly cost
                return total + (Number(addOn.amount) || 0) * this.pricing.weeksPerMonth;
            }, 0);
        },

        get grossMonthlyCost() {
            return this.baseMonthlyCost + this.transportMonthlyCost + this.addOnsMonthlyCost;
        },

        get subsidySavings() {
            const rate = this.selectedSubsidy?.rate ?? 0;
            const savings = this.grossMonthlyCost * rate;
            return Math.min(savings, this.grossMonthlyCost);
        },

        get totalMonthly() {
            const total = this.grossMonthlyCost - this.subsidySavings;
            return Math.max(total, 0);
        },

        get effectiveDailyRate() {
            const totalDays = this.daysPerWeek * this.pricing.weeksPerMonth;
            if (!totalDays) {
                return 0;
            }

            return this.totalMonthly / totalDays;
        },

        get selectedAddOnsSummary() {
            if (!this.selectedAddOns.length) {
                return 'Not selected';
            }

            const labels = this.selectedAddOns
                .map((key) => this.pricing.addOns.find((item) => item.key === key)?.label)
                .filter(Boolean);

            return labels.length ? labels.join(', ') : 'Not selected';
        },

        formatCurrency(value) {
            return defaultFormatter.format(Math.round(value));
        },
    };
};

window.costEstimatorComponent = costEstimatorComponent;

export { costEstimatorComponent };

```

# resources/js/modules/carousel.js
```js
let emblaModulePromise;

window.initEmbla = (element, options = {}) => {
    if (!element) {
        return;
    }

    if (!emblaModulePromise) {
        emblaModulePromise = import('embla-carousel').then((module) => module.default);
    }

    const defaultOptions = {
        loop: true,
        align: 'center',
        skipSnaps: false,
    };

    emblaModulePromise
        .then((EmblaCarousel) => {
            EmblaCarousel(element, { ...defaultOptions, ...options });
        })
        .catch((error) => {
            console.error('Failed to initialize Embla carousel', error);
        });
};

```

# postcss.config.js
```js
export default {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}

```

# tailwind.config.js
```js
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import animate from 'tailwindcss-animate';

const withOpacityValue = (variable) => ({ opacityValue }) => {
  if (opacityValue === undefined) {
    return `rgb(var(${variable}))`;
  }

  return `rgb(var(${variable}) / ${opacityValue})`;
};

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/js/**/*.vue',
    './resources/js/**/*.ts',
    './resources/**/*.php',
  ],
  darkMode: 'media',
  theme: {
    extend: {
      colors: {
        trust: withOpacityValue('--color-trust'),
        gold: withOpacityValue('--color-gold'),
        amber: withOpacityValue('--color-amber'),
        wellness: withOpacityValue('--color-wellness'),
        canvas: withOpacityValue('--color-canvas'),
        slate: {
          DEFAULT: withOpacityValue('--color-slate'),
          dark: withOpacityValue('--color-slate-dark'),
        },
      },
      fontSize: {
        'display-lg': ['clamp(2.75rem, 2.25rem + 1.5vw, 3.5rem)', { lineHeight: '1.05' }],
        'display-md': ['clamp(2.25rem, 2rem + 1vw, 3rem)', { lineHeight: '1.1' }],
        'heading-xl': ['clamp(1.75rem, 1.5rem + 0.8vw, 2.25rem)', { lineHeight: '1.2' }],
        'heading-lg': ['clamp(1.5rem, 1.35rem + 0.6vw, 2rem)', { lineHeight: '1.3' }],
        'heading-md': ['clamp(1.25rem, 1.15rem + 0.4vw, 1.6rem)', { lineHeight: '1.4' }],
        'body-lg': ['clamp(1.125rem, 1.05rem + 0.2vw, 1.25rem)', { lineHeight: '1.6' }],
        'body-md': ['1rem', { lineHeight: '1.65' }],
        'body-sm': ['0.9375rem', { lineHeight: '1.6' }],
        'body-xs': ['0.875rem', { lineHeight: '1.5' }],
      },
      fontFamily: {
        heading: ['"Playfair Display"', 'serif'],
        body: ['Inter', 'sans-serif'],
      },
      boxShadow: {
        card: '0 20px 45px -20px rgba(28, 61, 90, 0.25)',
      },
      maxWidth: {
        section: '1280px',
      },
      transitionTimingFunction: {
        'ease-out-cubic': 'cubic-bezier(0.22, 1, 0.36, 1)',
      },
      keyframes: {
        fadeInUp: {
          '0%': { opacity: 0, transform: 'translateY(24px)' },
          '100%': { opacity: 1, transform: 'translateY(0)' },
        },
      },
      animation: {
        'fade-in-up': 'fadeInUp 0.6s var(--ease-out-cubic, cubic-bezier(0.22, 1, 0.36, 1)) both',
      },
      container: {
        center: true,
        padding: {
          DEFAULT: '1.5rem',
          sm: '2rem',
          lg: '2.5rem',
        },
      },
    },
  },
  plugins: [forms, typography, animate, function ({ addUtilities }) {
    addUtilities({
      '.text-display-lg': {
        fontSize: 'clamp(2.75rem, 2.25rem + 1.5vw, 3.5rem)',
        lineHeight: '1.05',
      },
      '.text-display-md': {
        fontSize: 'clamp(2.25rem, 2rem + 1vw, 3rem)',
        lineHeight: '1.1',
      },
    });
  }],
};


```

