### Vitest and jsdom setup overview

You want a deterministic JS unit test setup that runs headless locally and in CI, using Vitest with a jsdom environment and a test spec at resources/js/tests/cost-estimator.spec.ts. Below is a hardened, step-by-step plan with explicit guardrails, TypeScript alignment, and CI wiring so you avoid silent failures and drift.

---

### Install and baseline configuration

#### Packages
- **Vitest:** test runner aligned with Vite
- **jsdom:** browser-like DOM in Node
- **Testing Library (optional but recommended):** ergonomics for DOM testing
- **jest-dom (optional):** richer assertions for DOM state

```bash
npm i -D vitest jsdom @testing-library/dom @testing-library/jest-dom
```

#### Vitest config with jsdom

Create vitest.config.ts at the project root to make the environment explicit and avoid per-file annotations.

```ts
// vitest.config.ts
import { defineConfig } from 'vitest/config';

export default defineConfig({
  test: {
    environment: 'jsdom',
    globals: true, // enables describe/it/expect globals
    setupFiles: ['resources/js/tests/setupTests.ts'],
    include: ['resources/js/tests/**/*.spec.ts'],
    coverage: {
      reporter: ['text', 'lcov'],
      all: true, // include files without tests to catch dead zones
      thresholds: { // fail CI if coverage regresses
        lines: 80,
        statements: 80,
        branches: 70,
        functions: 80,
      },
    },
    // Ensures reproducible execution order
    sequence: { concurrent: false },
  },
});
```

#### Vitest types in TS config

Add Vitest typings to your tsconfig.json so expect/vi globals typecheck cleanly.

```json
{
  "compilerOptions": {
    "types": ["vitest", "vitest/globals", "@testing-library/jest-dom"]
  }
}
```

If you prefer no globals, set `globals: false` and import from vitest in each spec.

#### Global test setup

Create the setup file to register jest-dom matchers and any required polyfills or globals.

```ts
// resources/js/tests/setupTests.ts
import '@testing-library/jest-dom';

// Optional: stable clocks for deterministic tests
import { vi } from 'vitest';
vi.useFakeTimers();

// Optional: polyfill fetch if your module uses it
// If you prefer node-fetch: `npm i -D node-fetch`
// globalThis.fetch = (await import('node-fetch')).default as any;

// Optional: guardrail to ensure jsdom is active
if (typeof document === 'undefined') {
  throw new Error('jsdom environment not active. Check vitest.config.ts test.environment.');
}
```

---

### NPM scripts and local/CI ergonomics

Add a focused test script and a watch mode:

```json
{
  "scripts": {
    "test:js": "vitest run --reporter=dot",
    "test:js:watch": "vitest"
  }
}
```

- `vitest run` disables watch, ideal for CI.
- `--reporter=dot` keeps logs compact; use `verbose` locally if you need granular output.

---

### Example cost estimator unit test

Assume your cost estimator consumes a pricing config and exposes a pure function. If it also emits analytics or touches DOM, we’ll isolate those via spies.

#### Sample module under test

Example resources/js/cost-estimator.ts:

```ts
export type PricingTier = {
  name: string;
  base: number;        // base monthly price
  perVisit: number;    // incremental per visit
  currency: 'SGD' | 'USD';
};

export type EstimateInput = {
  tier: string;        // tier name
  visitsPerMonth: number;
  discounts?: number;  // percentage 0..100
};

export type EstimateOutput = {
  total: number;
  breakdown: {
    base: number;
    visits: number;
    discount: number;
  };
  currency: string;
};

export function estimateCost(pricing: PricingTier[], input: EstimateInput): EstimateOutput {
  const tier = pricing.find(t => t.name === input.tier);
  if (!tier) throw new Error(`Unknown pricing tier: ${input.tier}`);

  const base = tier.base;
  const visits = tier.perVisit * input.visitsPerMonth;
  const subtotal = base + visits;
  const discountPct = Math.min(Math.max(input.discounts ?? 0, 0), 100);
  const discount = +(subtotal * (discountPct / 100)).toFixed(2);
  const total = +(subtotal - discount).toFixed(2);

  return {
    total,
    breakdown: { base, visits, discount },
    currency: tier.currency,
  };
}
```

#### Unit test spec

Create resources/js/tests/cost-estimator.spec.ts:

```ts
import { describe, it, expect } from 'vitest';
import { estimateCost, type PricingTier } from '../cost-estimator';

describe('cost estimator', () => {
  const pricing: PricingTier[] = [
    { name: 'basic', base: 120, perVisit: 20, currency: 'SGD' },
    { name: 'premium', base: 200, perVisit: 15, currency: 'SGD' },
  ];

  it('computes totals and breakdown for basic tier', () => {
    const out = estimateCost(pricing, { tier: 'basic', visitsPerMonth: 3, discounts: 10 });
    expect(out.currency).toBe('SGD');
    expect(out.breakdown.base).toBe(120);
    expect(out.breakdown.visits).toBe(60);
    expect(out.breakdown.discount).toBe(18); // 10% of 180
    expect(out.total).toBe(162);
  });

  it('handles missing discount and clamps invalid ranges', () => {
    const a = estimateCost(pricing, { tier: 'premium', visitsPerMonth: 2 });
    expect(a.breakdown.discount).toBe(0);
    expect(a.total).toBe(230); // 200 + (2 * 15)

    const b = estimateCost(pricing, { tier: 'premium', visitsPerMonth: 2, discounts: -5 });
    expect(b.breakdown.discount).toBe(0);

    const c = estimateCost(pricing, { tier: 'premium', visitsPerMonth: 2, discounts: 150 });
    // 100% discount max clamp
    expect(c.breakdown.discount).toBe(230);
    expect(c.total).toBe(0);
  });

  it('throws for unknown tier', () => {
    expect(() => estimateCost(pricing, { tier: 'nonexistent', visitsPerMonth: 1 }))
      .toThrow(/Unknown pricing tier/);
  });
});
```

If the estimator reads from the DOM or window, stub those dependencies. For example:

```ts
// Example: the estimator reads window.__pricing injected by the app
declare global {
  interface Window { __pricing?: unknown; }
}

it('reads pricing from window when not provided', () => {
  window.__pricing = [{ name: 'basic', base: 100, perVisit: 10, currency: 'SGD' }];
  // ... call an overloaded function that reads window.__pricing
});
```

---

### Optional DOM interaction test (jsdom)

If the estimator attaches to a form, use Testing Library to validate DOM behavior:

```ts
import { describe, it, expect } from 'vitest';
import { screen } from '@testing-library/dom';

function renderEstimator() {
  document.body.innerHTML = `
    <form id="estimator">
      <select id="tier">
        <option value="basic">Basic</option>
        <option value="premium">Premium</option>
      </select>
      <input id="visits" type="number" value="3" />
      <button id="calc">Calculate</button>
      <output id="total"></output>
    </form>
  `;
  // attach event listeners that compute and update #total (your module logic)
  const calcBtn = document.getElementById('calc')!;
  calcBtn.addEventListener('click', (e) => {
    e.preventDefault();
    const tier = (document.getElementById('tier') as HTMLSelectElement).value;
    const visits = +(document.getElementById('visits') as HTMLInputElement).value;
    const pricing = [
      { name: 'basic', base: 120, perVisit: 20, currency: 'SGD' },
      { name: 'premium', base: 200, perVisit: 15, currency: 'SGD' },
    ];
    const { total } = (await import('../cost-estimator')).estimateCost(pricing as any, { tier, visitsPerMonth: visits });
    (document.getElementById('total') as HTMLOutputElement).value = String(total);
  });
}

describe('estimator DOM flow', () => {
  it('updates total on calculate', async () => {
    renderEstimator();
    const calc = screen.getByText('Calculate');
    calc.dispatchEvent(new MouseEvent('click', { bubbles: true, cancelable: true }));
    const total = document.getElementById('total') as HTMLOutputElement;
    expect(total.value).toBe('162');
  });
});
```

Note: jsdom is synchronous; if your handlers are async, `await vi.runAllTimers()` can help when using fake timers.

---

### CI integration plan and guardrails

#### Minimal CI job (GitHub Actions example)

```yaml
name: js-tests

on:
  push:
    branches: [ main ]
  pull_request:

jobs:
  vitest:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout
        uses: actions/checkout@v4
      - name: Setup Node
        uses: actions/setup-node@v4
        with:
          node-version: '20'
          cache: 'npm'
      - name: Install
        run: npm ci
      - name: Run unit tests
        run: npm run test:js
      - name: Upload coverage
        if: always()
        uses: actions/upload-artifact@v4
        with:
          name: coverage-lcov
          path: coverage/lcov.info
```

#### Determinism and drift prevention
- **Lockfile:** Commit package-lock.json; use npm ci in CI.
- **Environment pinning:** Node 20+ to align with modern Vite/Vitest.
- **Fast failure:** Coverage thresholds enforced in vitest.config.ts; CI will fail on regression.
- **Isolation:** jsdom environment set globally via config; no per-file hacks.
- **Aliases parity:** If you use Vite aliases, ensure vitest picks them up via vite.config.ts or add resolve.alias in vitest.config.ts for parity.

---

### Sanity checks and troubleshooting

- **document is not defined:** Ensure test.environment is jsdom and setupTests asserts it. Avoid invoking Vitest directly with custom runners that ignore your config.
- **Module path errors:** If resources/js isn’t under your TS root, add it to tsconfig include and Vitest include in vitest.config.ts.
- **Flaky timers:** Use vi.useFakeTimers() and vi.runAllTimers() where async debounce logic exists; otherwise prefer explicit awaits on promises.
- **Global state leaks:** Reset DOM between tests:
  ```ts
  import { afterEach } from 'vitest';
  afterEach(() => {
    document.body.innerHTML = '';
  });
  ```
- **Fetch/XHR:** Polyfill fetch in setupTests if your code calls it. For network isolation, mock with vi.spyOn(globalThis, 'fetch') and return deterministic responses.

---

### Decision checkpoints before CI rollout

- Confirm the estimator API and any side effects (analytics, network) are mocked or isolated in unit tests.
- Verify TypeScript build passes with Vitest types present.
- Run npm run test:js locally and ensure coverage artifacts generate as expected.
- Add the job to CI gated with coverage thresholds; adjust numbers once initial baseline is established.


---

https://copilot.microsoft.com/shares/aVP3Ux2XBMFVurZtyoXDW
