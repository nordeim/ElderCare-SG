@props([
    'faqs' => collect(),
    'sectionId' => 'faq',
    'headline' => 'Answers to common caregiver questions',
    'kicker' => 'Frequently asked questions',
    'description' => 'Learn more about admissions, logistics, and programme planning. Contact our care concierge for personalised guidance.',
])

@php
    $groupedFaqs = $faqs
        ->sortBy(['category', 'display_order'])
        ->groupBy(function ($faq) {
            return $faq->category ?: 'General';
        })
        ->map(function ($items, $category) {
            return [
                'category' => $category,
                'items' => $items->map(function ($faq) {
                    return [
                        'id' => 'faq-' . $faq->id,
                        'question' => $faq->question,
                        'answer' => $faq->answer,
                    ];
                })->values(),
            ];
        })
        ->values();
@endphp

<section
    id="{{ $sectionId }}"
    class="bg-white py-16"
    x-data="faqAccordion({ faqGroups: @js($groupedFaqs) })"
>
    <div class="mx-auto max-w-section px-6">
        <div class="mb-10 text-center lg:text-left">
            <p class="pill-tag mx-auto lg:mx-0">{{ $kicker }}</p>
            <h2 class="mt-4 text-3xl font-semibold text-trust sm:text-4xl">{{ $headline }}</h2>
            <p class="mt-3 text-slate">{{ $description }}</p>
        </div>

        <div class="mb-6 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <label class="relative flex-1">
                <span class="sr-only">Search FAQs</span>
                <input
                    type="search"
                    class="w-full rounded-full border border-slate/20 bg-canvas px-5 py-3 text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold"
                    placeholder="Search questions or keywords"
                    x-model="query"
                    @input.debounce.250ms="filterFaqs()"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 5.25 5.25a7.5 7.5 0 0 0 11.4 9.9z" />
                </svg>
            </label>
            <div class="text-sm text-slate" role="status" aria-live="polite">
                <span x-text="filteredCount"></span> questions available
            </div>
        </div>

        <div class="space-y-6">
            <template x-for="group in filteredGroups" :key="group.category">
                <section>
                    <h3 class="mb-3 text-xl font-semibold text-trust" x-text="group.category"></h3>
                    <div class="space-y-3">
                        <template x-for="item in group.items" :key="item.id">
                            <article class="faq-item" :class="{ 'faq-item--active': isOpen(item.id) }">
                                <h4>
                                    <button
                                        type="button"
                                        class="faq-item__trigger"
                                        :aria-expanded="isOpen(item.id)"
                                        :aria-controls="`${item.id}-content`"
                                        :id="`${item.id}-label`"
                                        @click="toggle(item.id)"
                                        @keydown.enter.prevent="toggle(item.id)"
                                        @keydown.space.prevent="toggle(item.id)"
                                        data-analytics-id="faq-expand"
                                        :data-faq-id="item.id"
                                    >
                                        <span x-text="item.question"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform" :class="{ 'rotate-180': isOpen(item.id) }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </h4>
                                <div
                                    class="faq-item__content"
                                    x-show="isOpen(item.id)"
                                    x-collapse
                                    role="region"
                                    :id="`${item.id}-content`"
                                    :aria-labelledby="`${item.id}-label`"
                                >
                                    <p x-html="item.answer"></p>
                                </div>
                            </article>
                        </template>
                    </div>
                </section>
            </template>

            <template x-if="!filteredGroups.length">
                <p class="text-sm text-slate">No questions match your search. Contact our care concierge for personalised support.</p>
            </template>
        </div>
    </div>

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqs->map(function ($faq) {
                return [
                    '@type' => 'Question',
                    'name' => $faq->question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq->answer,
                    ],
                ];
            }),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
</section>

@pushOnce('scripts')
<script>
    window.faqAccordion = ({ faqGroups = [] }) => ({
        faqGroups,
        query: '',
        filteredGroups: faqGroups,
        openIds: new Set(),

        init() {
            this.filteredGroups = faqGroups;
            this.filteredCount = this.countItems(faqGroups);
        },

        filterFaqs() {
            const normalized = this.query.toLowerCase();
            if (!normalized) {
                this.filteredGroups = this.faqGroups;
            } else {
                this.filteredGroups = this.faqGroups
                    .map((group) => {
                        const items = group.items.filter((item) => {
                            return (
                                item.question.toLowerCase().includes(normalized) ||
                                item.answer.toLowerCase().includes(normalized)
                            );
                        });

                        if (!items.length) {
                            return null;
                        }

                        return { ...group, items };
                    })
                    .filter(Boolean);
            }

            this.filteredCount = this.countItems(this.filteredGroups);
        },

        toggle(id) {
            if (this.isOpen(id)) {
                this.openIds.delete(id);
            } else {
                this.openIds.add(id);
                window.eldercareAnalytics?.emit?.('faq.expand', { faqId: id });
            }
        },

        isOpen(id) {
            return this.openIds.has(id);
        },

        countItems(groups) {
            return groups.reduce((total, group) => total + group.items.length, 0);
        },
    });
</script>
@endPushOnce
