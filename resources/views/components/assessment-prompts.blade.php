@php
    $promptConfig = [
        'default' => [
            [
                'slug' => 'prompt-estimator-overview',
                'title' => 'See costs tailored to your family',
                'description' => 'Use the cost estimator to balance program days, transport, and add-ons before you book a visit.',
                'cta_label' => 'Open cost estimator',
                'cta_href' => '#estimator',
                'cta_analytics' => 'estimator',
            ],
        ],
        'active_day' => [
            [
                'slug' => 'prompt-plan-schedule',
                'title' => 'Plan the ideal weekly schedule',
                'description' => 'Preview program fees for different day combinations and see how subsidies affect your total.',
                'cta_label' => 'Adjust visit days',
                'cta_href' => '#estimator',
                'cta_analytics' => 'estimator',
            ],
            [
                'slug' => 'prompt-read-testimonials',
                'title' => 'Hear from active day families',
                'description' => 'Jump to our testimonials to see how other caregivers build social routines with us.',
                'cta_label' => 'View testimonials',
                'cta_href' => '#testimonials',
                'cta_analytics' => 'testimonials',
            ],
        ],
        'supportive_care' => [
            [
                'slug' => 'prompt-clinical-consult',
                'title' => 'Schedule a clinical walkthrough',
                'description' => 'Book a consultation to review therapy plans, nurse ratios, and customised reporting.',
                'cta_label' => 'Plan a clinical visit',
                'cta_href' => '#booking',
                'cta_analytics' => 'booking',
            ],
            [
                'slug' => 'prompt-estimator-therapy',
                'title' => 'Estimate therapy-inclusive fees',
                'description' => 'Toggle physiotherapy and concierge add-ons inside the estimator to forecast your monthly total.',
                'cta_label' => 'Open cost estimator',
                'cta_href' => '#estimator',
                'cta_analytics' => 'estimator',
            ],
        ],
        'memory_care' => [
            [
                'slug' => 'prompt-memory-guides',
                'title' => 'Download memory care guides',
                'description' => 'Access caregiver checklists and home routine ideas curated by our dementia specialists.',
                'cta_label' => 'Browse caregiver resources',
                'cta_href' => '#resources',
                'cta_analytics' => 'resources',
            ],
            [
                'slug' => 'prompt-memory-tour',
                'title' => 'Tour memory-safe spaces',
                'description' => 'Book a visit to explore sensory rooms, therapy gyms, and care team workflows.',
                'cta_label' => 'Book memory care visit',
                'cta_href' => '#booking',
                'cta_analytics' => 'booking',
            ],
        ],
        'respite_support' => [
            [
                'slug' => 'prompt-transport-checklist',
                'title' => 'Prepare transport & drop-off',
                'description' => 'Download the transport checklist to streamline pickups, escorts, and packing routines.',
                'cta_label' => 'Download transport checklist',
                'cta_href' => '#resources',
                'cta_analytics' => 'resources',
            ],
            [
                'slug' => 'prompt-flexible-pass',
                'title' => 'Build your flexible pass',
                'description' => 'Chat with our concierge about mixing transport, half-days, and respite options that match your schedule.',
                'cta_label' => 'Talk to our concierge',
                'cta_href' => '#booking',
                'cta_analytics' => 'booking',
            ],
        ],
        'exploration' => [
            [
                'slug' => 'prompt-resources-overview',
                'title' => 'Save caregiver planning guides',
                'description' => 'Download printable intake checklists and nutrition planners to review with your family.',
                'cta_label' => 'Browse caregiver resources',
                'cta_href' => '#resources',
                'cta_analytics' => 'resources',
            ],
            [
                'slug' => 'prompt-join-updates',
                'title' => 'Join monthly caregiver updates',
                'description' => 'Subscribe for new program announcements, subsidy insights, and caregiver workshop invites.',
                'cta_label' => 'Subscribe for updates',
                'cta_href' => '#newsletter',
                'cta_analytics' => 'newsletter',
            ],
        ],
    ];
@endphp

<section
    id="assessment-prompts"
    class="bg-white py-16"
    x-data="assessmentPrompts({ prompts: @js($promptConfig) })"
>
    <div class="mx-auto max-w-section px-6">
        <div class="mb-8 text-center lg:text-left">
            <p class="pill-tag mx-auto lg:mx-0">Personalised next steps</p>
            <h2 class="mt-4 text-3xl font-semibold text-trust sm:text-4xl">Keep planning with tailored suggestions</h2>
            <p class="mt-3 text-slate">Recommendations update after you complete the guided assessment. Explore the prompts that align with your family’s goals.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-2" x-show="activePrompts.length" x-cloak>
            <template x-for="prompt in activePrompts" :key="prompt.slug">
                <article class="section-card flex h-full flex-col justify-between gap-6">
                    <div class="space-y-3">
                        <p class="pill-tag inline-flex bg-gold text-trust text-xs uppercase tracking-wide">Suggested</p>
                        <h3 class="text-xl font-semibold text-trust" x-text="prompt.title"></h3>
                        <p class="text-sm text-slate" x-text="prompt.description"></p>
                    </div>
                    <a
                        :href="prompt.cta_href"
                        class="resource-card__cta"
                        data-analytics-id="assessment-prompt"
                        :data-prompt-slug="prompt.slug"
                        @click="handlePromptClick(prompt)"
                    >
                        <span x-text="prompt.cta_label"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.25 9.75L17.25 12L14.25 14.25M6.75 12H17.25" />
                        </svg>
                    </a>
                </article>
            </template>
        </div>

        <template x-if="!activePrompts.length">
            <p class="rounded-3xl bg-canvas px-6 py-4 text-sm text-slate">Complete the guided assessment to unlock personalised prompts for resources, pricing tools, and visit scheduling.</p>
        </template>
    </div>
</section>

@pushOnce('scripts')
<script>
    window.assessmentPrompts = ({ prompts = {} }) => ({
        prompts,
        activePrompts: [],
        recommendationStore: null,
        shownPromptSlugs: null,

        init() {
            this.recommendationStore = Alpine.store('assessmentRecommendation');
            this.shownPromptSlugs = new Set();
            this.refreshPrompts();

            if (this.recommendationStore) {
                this.$watch(
                    () => this.recommendationStore.updatedAt,
                    () => this.refreshPrompts()
                );
                this.$watch(
                    () => this.recommendationStore.segmentKey,
                    () => this.refreshPrompts()
                );
            }
        },

        refreshPrompts() {
            const key = this.recommendationStore?.segmentKey ?? 'default';
            const candidates = this.prompts[key] ?? this.prompts.default ?? [];
            this.activePrompts = candidates;

            candidates.forEach((prompt) => {
                if (!this.shownPromptSlugs.has(prompt.slug)) {
                    window.eldercareAnalytics?.emit?.('assessment.prompt_show', {
                        slug: prompt.slug,
                        segmentKey: key,
                    });

                    this.shownPromptSlugs.add(prompt.slug);
                }
            });
        },

        handlePromptClick(prompt) {
            window.eldercareAnalytics?.emit?.('assessment.prompt_click', {
                slug: prompt.slug,
                segmentKey: this.recommendationStore?.segmentKey ?? 'unknown',
                destination: prompt.cta_analytics ?? null,
            });
        },
    });
</script>
@endPushOnce
