import { describe, expect, it, vi, beforeEach } from 'vitest';
import { costEstimatorComponent } from '../modules/cost-estimator';

declare global {
    interface Window {
        eldercareAnalytics?: {
            emit?: (event: string, detail?: Record<string, unknown>) => void;
            createEmitter?: (namespace: string) => (event: string, detail?: Record<string, unknown>) => void;
        };
    }
}

describe('costEstimatorComponent', () => {
    const pricing = {
        dailyRate: 128,
        transportFee: 36,
        weeksPerMonth: 4.3,
        addOns: [
            { key: 'meals', label: 'Meals', amount: 18 },
            { key: 'therapy', label: 'Therapy', amount: 42 },
        ],
    };

    const subsidies = [
        { key: 'none', label: 'None', rate: 0 },
        { key: 'chaspioneer', label: 'CHAS / Pioneer', rate: 0.3 },
    ];

    const defaults = {
        daysPerWeek: 3,
        includeTransport: true,
        selectedAddOns: ['meals'],
        subsidyKey: 'chaspioneer',
    };

    beforeEach(() => {
        window.eldercareAnalytics = {};
    });

    it('initializes state from provided defaults and calculates totals', () => {
        const component = costEstimatorComponent({ pricing, subsidies, defaults });

        expect(component.daysPerWeek).toBe(3);
        expect(component.includeTransport).toBe(true);
        expect(component.selectedAddOns).toEqual(['meals']);
        expect(component.selectedSubsidyKey).toBe('chaspioneer');

        expect(component.baseMonthlyCost).toBeCloseTo(128 * 3 * 4.3, 5);
        expect(component.transportMonthlyCost).toBeCloseTo(36 * 3 * 4.3, 5);
        expect(component.addOnsMonthlyCost).toBeCloseTo(18 * 4.3, 5);

        const gross = component.baseMonthlyCost + component.transportMonthlyCost + component.addOnsMonthlyCost;
        expect(component.grossMonthlyCost).toBeCloseTo(gross, 5);
        expect(component.subsidySavings).toBeCloseTo(gross * 0.3, 5);
        expect(component.totalMonthly).toBeCloseTo(gross * 0.7, 5);
        expect(component.effectiveDailyRate).toBeCloseTo(component.totalMonthly / (defaults.daysPerWeek * pricing.weeksPerMonth), 5);
    });

    it('clamps subsidy savings to gross total and handles missing transport/add-ons', () => {
        const component = costEstimatorComponent({
            pricing,
            subsidies: [{ key: 'super', label: 'Super', rate: 5 }],
            defaults: {
                daysPerWeek: 2,
                includeTransport: false,
                selectedAddOns: [],
                subsidyKey: 'super',
            },
        });

        expect(component.transportMonthlyCost).toBe(0);
        expect(component.addOnsMonthlyCost).toBe(0);

        const gross = component.grossMonthlyCost;
        expect(component.subsidySavings).toBeCloseTo(gross, 5);
        expect(component.totalMonthly).toBe(0);
    });

    it('emits analytics events on init and state changes', () => {
        const emitSpy = vi.fn();
        window.eldercareAnalytics = {
            createEmitter: vi.fn(() => emitSpy),
        };

        const component = costEstimatorComponent({ pricing, subsidies, defaults });

        expect(window.eldercareAnalytics.createEmitter).toHaveBeenCalledWith('estimator');

        component.init.call({
            ...component,
            emitEvent: component.emitEvent,
            $watch: (_prop, callback) => callback(),
        });

        expect(emitSpy).toHaveBeenCalled();
        const events = emitSpy.mock.calls.map(([event]) => event);
        expect(events).toContain('open');
        expect(events).toContain('update');
    });

    it('formatCurrency returns SGD formatted string', () => {
        const component = costEstimatorComponent({ pricing, subsidies, defaults });
        expect(component.formatCurrency(1234.56)).toMatch(/\$\d{1,3}(,\d{3})?/);
    });
});
