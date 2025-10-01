@props([
    'sectionId' => 'estimator',
    'kicker' => 'Cost estimator',
    'headline' => 'Estimate your monthly investment',
    'description' => 'Adjust visit frequency, transport, and add-ons to preview your family’s monthly fees. Subsidy ranges reflect common MOH assistance tiers.',
])

@php
    $pricing = [
        'dailyRate' => 128,
        'transportFee' => 36,
        'weeksPerMonth' => 4.3,
        'addOns' => [
            [
                'key' => 'meals',
                'label' => 'Chef-prepared therapeutic meals',
                'description' => 'Customised meals with clinical nutrition oversight.',
                'amount' => 18,
            ],
            [
                'key' => 'therapy',
                'label' => 'Weekly physiotherapy session',
                'description' => '45-minute individual therapy tailored to mobility goals.',
                'amount' => 42,
            ],
            [
                'key' => 'carecoach',
                'label' => 'Care coach concierge',
                'description' => 'Family liaison for care planning, medical appointments, and reports.',
                'amount' => 55,
            ],
        ],
    ];

    $subsidies = [
        [
            'key' => 'none',
            'label' => 'No subsidy',
            'rate' => 0.0,
            'description' => 'Full fee without government assistance.',
        ],
        [
            'key' => 'chaspioneer',
            'label' => 'CHAS / Pioneer (up to 30%)',
            'rate' => 0.3,
            'description' => 'Typical support for CHAS Blue/Orange or Pioneer Generation households.',
        ],
        [
            'key' => 'mohmeans',
            'label' => 'MOH Means-tested (up to 50%)',
            'rate' => 0.5,
            'description' => 'Estimated savings for means-tested subsidy tiers.',
        ],
    ];

    $defaults = [
        'daysPerWeek' => 3,
        'includeTransport' => true,
        'selectedAddOns' => ['meals'],
        'subsidyKey' => 'chaspioneer',
    ];
@endphp

<section
    id="{{ $sectionId }}"
    class="bg-canvas py-16"
    x-data="costEstimatorComponent({
        pricing: @js($pricing),
        subsidies: @js($subsidies),
        defaults: @js($defaults),
        analyticsNamespace: 'estimator',
    })"
    x-init="init()"
>
    <div class="mx-auto max-w-section px-6">
        <div class="mb-8 flex flex-col gap-4 text-center sm:text-left">
            <p class="pill-tag mx-auto sm:mx-0">{{ $kicker }}</p>
            <h2 class="text-3xl font-semibold text-trust sm:text-4xl">{{ $headline }}</h2>
            <p class="text-slate">{{ $description }}</p>
        </div>

        <div class="grid gap-8 lg:grid-cols-2">
            <form class="section-card space-y-6" @submit.prevent>
                <div>
                    <label for="days-per-week" class="flex items-center justify-between text-sm font-semibold text-trust">
                        <span>Days per week</span>
                        <span x-text="daysPerWeek + ' day' + (daysPerWeek === 1 ? '' : 's')"></span>
                    </label>
                    <input
                        type="range"
                        id="days-per-week"
                        min="1"
                        max="6"
                        step="1"
                        x-model.number="daysPerWeek"
                        class="w-full"
                        aria-describedby="days-per-week-help"
                    >
                    <p id="days-per-week-help" class="mt-2 text-xs text-slate">Adjust to reflect the number of scheduled program days each week (1–6).</p>
                </div>

                <div class="flex items-center justify-between rounded-2xl border border-slate/10 bg-white px-4 py-3">
                    <div>
                        <p class="text-sm font-semibold text-trust">Daily transport add-on</p>
                        <p class="text-xs text-slate">Wheelchair-friendly pickup and drop-off with trained escorts.</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" class="peer sr-only" x-model="includeTransport">
                        <span class="toggle-switch"></span>
                        <span class="sr-only">Enable transport</span>
                    </label>
                </div>

                <fieldset class="space-y-3">
                    <legend class="text-sm font-semibold text-trust">Add-ons</legend>
                    <p class="text-xs text-slate">Choose enhancements that match your loved one’s preferences.</p>
                    <template x-for="addOn in pricing.addOns" :key="addOn.key">
                        <label class="flex gap-3 rounded-2xl border border-slate/10 bg-white px-4 py-3">
                            <input
                                type="checkbox"
                                class="mt-1 h-4 w-4 rounded border-slate/30 text-trust focus:ring-trust"
                                :value="addOn.key"
                                x-model="selectedAddOns"
                            >
                            <span>
                                <span class="flex items-center justify-between text-sm font-semibold text-trust">
                                    <span x-text="addOn.label"></span>
                                    <span x-text="formatCurrency(addOn.amount)"></span>
                                </span>
                                <span class="mt-1 block text-xs text-slate" x-text="addOn.description"></span>
                            </span>
                        </label>
                    </template>
                </fieldset>

                <div>
                    <label for="subsidy" class="block text-sm font-semibold text-trust">Subsidy scenario</label>
                    <select
                        id="subsidy"
                        x-model="selectedSubsidyKey"
                        class="mt-2 w-full rounded-2xl border border-slate/20 bg-white px-4 py-3 text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold"
                    >
                        <template x-for="subsidy in subsidies" :key="subsidy.key">
                            <option :value="subsidy.key" x-text="subsidy.label"></option>
                        </template>
                    </select>
                    <template x-if="selectedSubsidy">
                        <p class="mt-2 text-xs text-slate" x-text="selectedSubsidy.description"></p>
                    </template>
                </div>

                <button
                    type="button"
                    class="cta-button w-full justify-center"
                    @click="toggleDetails()"
                    data-analytics-id="estimator-toggle-details"
                >
                    {{ __('See calculation details') }}
                </button>
            </form>

            <div class="section-card space-y-6">
                <div class="space-y-2">
                    <p class="pill-tag inline-flex bg-gold text-trust">Projected monthly total</p>
                    <h3 class="text-4xl font-semibold text-trust" x-text="formatCurrency(totalMonthly)"></h3>
                    <p class="text-sm text-slate">Based on <span x-text="daysPerWeek"></span> day(s) per week across {{ $pricing['weeksPerMonth'] }} weeks/month.</p>
                </div>

                <div class="grid gap-3 text-sm text-slate">
                    <div class="flex items-center justify-between">
                        <span>Program fees</span>
                        <span x-text="formatCurrency(baseMonthlyCost)"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Transport</span>
                        <span x-text="formatCurrency(transportMonthlyCost)"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Add-ons</span>
                        <span x-text="formatCurrency(addOnsMonthlyCost)"></span>
                    </div>
                    <div class="flex items-center justify-between text-trust">
                        <span>Subsidy savings</span>
                        <span class="font-semibold" x-text="'-' + formatCurrency(subsidySavings)"></span>
                    </div>
                </div>

                <div class="rounded-2xl bg-canvas p-4 text-sm text-slate">
                    <p class="font-semibold text-trust">Effective daily rate</p>
                    <p class="mt-1" x-text="formatCurrency(effectiveDailyRate)"></p>
                    <p class="mt-2 text-xs">Final invoiced amount depends on personalised care plan, assessments, and verified subsidy amounts.</p>
                </div>

                <template x-if="showDetails">
                    <div class="rounded-2xl border border-slate/10 bg-white p-4 text-xs text-slate">
                        <p class="font-semibold text-trust">Calculation details</p>
                        <ul class="mt-2 space-y-1">
                            <li><strong>Base</strong>: <span x-text="daysPerWeek"></span> day(s) × {{ $pricing['weeksPerMonth'] }} weeks × <span x-text="formatCurrency(pricing.dailyRate)"></span> daily rate</li>
                            <li><strong>Transport</strong>: <span x-text="includeTransport ? 'Included' : 'Not included'"></span> (<span x-text="formatCurrency(pricing.transportFee)"></span> per day)</li>
                            <li><strong>Add-ons</strong>: <span x-text="selectedAddOnsSummary"></span></li>
                            <li><strong>Subsidy</strong>: <span x-text="selectedSubsidy ? selectedSubsidy.label : 'None'"></span></li>
                        </ul>
                    </div>
                </template>

                <a href="#booking" class="cta-button inline-flex" data-analytics-id="estimator-booking-cta">Book a consultation</a>
            </div>
        </div>
    </div>
</section>
