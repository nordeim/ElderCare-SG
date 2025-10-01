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
            <h2 class="font-semibold text-heading-xl text-trust">{{ $headline }}</h2>
            <p class="text-body-md text-slate">{{ $description }}</p>
        </div>

        <div class="grid gap-8 lg:grid-cols-2">
            <form class="section-card space-y-6" @submit.prevent>
                <div>
                    <label for="days-per-week" class="flex items-center justify-between text-body-sm font-semibold text-trust">
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
                    <p id="days-per-week-help" class="mt-2 text-body-xs text-slate">Adjust to reflect the number of scheduled program days each week (1–6).</p>

                <div class="flex items-center justify-between rounded-2xl border border-slate/10 bg-white px-4 py-3">
                    <div>
                        <p class="text-body-sm font-semibold text-trust">Daily transport add-on</p>
                        <p class="text-body-xs text-slate">Wheelchair-friendly pickup and drop-off with trained escorts.</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" class="peer sr-only" x-model="includeTransport">
                        <span class="toggle-switch"></span>
                        <span class="sr-only">Enable transport</span>
                    </label>
{{ ... }}
                >
                    {{ __('See calculation details') }}
                </button>
            </form>

            <div class="section-card space-y-6">
                <div class="space-y-2">
                    <p class="pill-tag inline-flex bg-gold text-trust">Projected monthly total</p>
                    <h3 class="font-semibold text-heading-lg text-trust" x-text="formatCurrency(totalMonthly)"></h3>
                    <p class="text-body-sm text-slate">Based on <span x-text="daysPerWeek"></span> day(s) per week across {{ $pricing['weeksPerMonth'] }} weeks/month.</p>
                </div>

                <div class="grid gap-3 text-body-sm text-slate">
                    <div class="flex items-center justify-between">
                        <span>Program fees</span>
                        <span x-text="formatCurrency(baseMonthlyCost)"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Transport</span>
                        <span x-text="formatCurrency(transportMonthlyCost)"></span>
{{ ... }}
                    <div class="flex items-center justify-between">
                        <span>Add-ons</span>
                        <span x-text="formatCurrency(addOnsMonthlyCost)"></span>
                    </div>
                    <div class="flex items-center justify-between text-trust">
                        <span>Subsidy savings</span>
                        <span class="font-semibold" x-text="'-' + formatCurrency(subsidySavings)"></span>
                    </div>
                </div>

                <div class="rounded-2xl bg-canvas p-4 text-body-sm text-slate">
                    <p class="font-semibold text-trust">Effective daily rate</p>
                    <p class="mt-1 text-body-md" x-text="formatCurrency(effectiveDailyRate)"></p>
                    <p class="mt-2 text-body-xs">Final invoiced amount depends on personalised care plan, assessments, and verified subsidy amounts.</p>
                </div>

                <template x-if="showDetails">
                    <div class="rounded-2xl border border-slate/10 bg-white p-4 text-body-xs text-slate">
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
