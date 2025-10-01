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
