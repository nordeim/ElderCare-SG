import { test, expect, Page } from '@playwright/test';

declare global {
    interface Window {
        eldercareAnalytics: {
            emit?: (...args: unknown[]) => unknown;
        };
        __analyticsCalls: { ts: number; args: unknown[] }[];
    }
}

const waitForAnalyticsCalls = async (page: Page) => {
    return page.waitForFunction(() => Array.isArray((window as any).__analyticsCalls) && (window as any).__analyticsCalls.length > 0, null, {
        timeout: 5_000,
    });
};

test.describe('eldercare analytics smoke', () => {
    test('emits estimator, FAQ, resource, and prompt events', async ({ page }) => {
        await page.addInitScript(() => {
            (window as any).__analyticsCalls = [];
            window.eldercareAnalytics = window.eldercareAnalytics || {};
            const originalEmit = window.eldercareAnalytics.emit;
            window.eldercareAnalytics.emit = function (...args: unknown[]) {
                (window as any).__analyticsCalls.push({ ts: Date.now(), args });
                if (typeof originalEmit === 'function') {
                    return originalEmit.apply(this, args as any);
                }
                return undefined;
            };
        });

        await page.goto('/', { waitUntil: 'networkidle' });

        await page.locator('#estimator').scrollIntoViewIfNeeded();
        const slider = page.getByLabel('Days per week');
        await slider.evaluate((input: HTMLInputElement) => {
            input.value = '4';
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });

        const faqTrigger = page.getByRole('button', { name: /what makes eldercare sg different/i });
        await faqTrigger.click();

        const resourceCta = page.locator('a[data-analytics-id="resource-download"]').first();
        await resourceCta.click({ modifiers: ['Control'] });

        const promptCta = page.locator('a[data-analytics-id="assessment-prompt"]').first();
        await promptCta.click({ modifiers: ['Control'] });

        await waitForAnalyticsCalls(page);

        const calls = await page.evaluate(() => (window as any).__analyticsCalls);
        expect(Array.isArray(calls)).toBeTruthy();

        const findEvent = (name: string) => calls.find((call: any) => call.args?.[0] === name);
        expect(findEvent('estimator.update')).toBeTruthy();
        expect(findEvent('faq.expand')).toBeTruthy();
        expect(findEvent('resource.download')).toBeTruthy();
        expect(findEvent('assessment.prompt_click')).toBeTruthy();
    });
});
