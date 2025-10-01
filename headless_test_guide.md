### Headless analytics event testing overview

You want a deterministic, headless test that drives real user interactions and asserts that `window.eldercareAnalytics.emit` fires with the exact payloads you expect. Below are battle-tested approaches for Playwright, Cypress, and a Python alternative, plus CI guardrails to prevent silent failures. Playwright runs headless by default; Cypress can be configured to run without a visible UI in CI.

---

### Tooling comparison for this use case

| Option | Best for | How to spy on emit | Headless defaults | CI ergonomics |
|---|---|---|---|---|
| Playwright (TS/JS) | Fast, robust E2E, multi-browser | Override `emit` via `addInitScript` to capture calls | Headless by default; `--headed` to show UI | First-class CI support; configurable reporters |
| Cypress (JS) | Dev-friendly debugging, strong command chain | `cy.stub(win.eldercareAnalytics, 'emit')` with assertions | Runs headless in CI `cypress run` | Popular CI integrations; video/screenshots out-of-box |
| Playwright (Python) | Scripted validation or ops harness | Same override via `add_init_script` | Headless by default | Simple to orchestrate from Python pipelines |

> Sources: 

---

### Playwright setup and event capture

#### Install and config
- **Install:** `npm i -D @playwright/test` and `npx playwright install`.
- **Headless:** Playwright runs tests headless by default; enable UI with `--headed`. In CI, keep headless and disable HTML report auto-open to avoid hangs.

```ts
// playwright.config.ts
import { defineConfig } from '@playwright/test';

export default defineConfig({
  use: {
    headless: true, // default; explicit for clarity
  },
  reporter: [['html', { open: 'never' }]], // avoid UI prompts in CI
});
```

This ensures deterministic headless execution and a CI-friendly reporter configuration.

#### Capture and assert emit calls
- **Strategy:** Before any app code runs, wrap `window.eldercareAnalytics.emit` to push call records into a global array. Then drive interactions and assert exact payloads.
- **Init script:** Guarantees our spy is attached before the page loads, preventing race conditions.

```ts
// tests/analytics.spec.ts
import { test, expect } from '@playwright/test';

test('eldercareAnalytics.emit fires with expected payloads', async ({ page }) => {
  await page.addInitScript(() => {
    // Create call sink
    (window as any).__analyticsCalls = [];
    const safeGet = (obj: any, path: string) => path.split('.').reduce((o, k) => (o ? o[k] : undefined), obj);

    // Ensure the analytics namespace exists (fail fast if not)
    Object.defineProperty(window, 'eldercareAnalytics', {
      configurable: true,
      get() {
        return (window as any).__eldercareAnalytics || ((window as any).__eldercareAnalytics = {});
      }
    });

    const originalEmit = safeGet(window, 'eldercareAnalytics.emit');
    // Wrap or define emit
    (window as any).eldercareAnalytics.emit = function (...args: any[]) {
      (window as any).__analyticsCalls.push({ ts: Date.now(), args });
      if (typeof originalEmit === 'function') return originalEmit.apply(this, args);
    };
  });

  // Navigate and interact
  await page.goto('http://localhost:5173/');
  await page.getByRole('button', { name: 'Book appointment' }).click();

  // Wait for analytics flush (debounce/async)
  await page.waitForTimeout(50);

  // Pull captured calls
  const calls = await page.evaluate(() => (window as any).__analyticsCalls);
  expect(Array.isArray(calls)).toBeTruthy();

  // Assert a specific event
  const submitEvent = calls.find(c => c.args?.[0] === 'appointment_submit');
  expect(submitEvent).toBeTruthy();

  // Deep payload assertions
  const payload = submitEvent.args[1];
  expect(payload).toMatchObject({
    userId: expect.any(String),
    careType: 'eldercare',
    metadata: expect.objectContaining({ source: 'ui' }),
  });
});
```

#### Optional: JSON Schema validation
- **Why:** Prevents brittle, shallow checks. Validates the event contract.
- **How:** Add Ajv to test deps and compile a schema; assert each `emit` payload passes.

```ts
// schema.ts
export const appointmentSubmitSchema = {
  type: 'object',
  required: ['userId', 'careType', 'metadata'],
  properties: {
    userId: { type: 'string', minLength: 1 },
    careType: { type: 'string', enum: ['eldercare'] },
    metadata: { type: 'object', required: ['source'], properties: { source: { type: 'string' } } },
  },
};
```

```ts
// validation in test
import Ajv from 'ajv';
import { appointmentSubmitSchema } from './schema';

const ajv = new Ajv({ allErrors: true, strict: true });
const validate = ajv.compile(appointmentSubmitSchema);
expect(validate(payload)).toBe(true);
```

Playwright’s default headless mode and config flags make this suited for CI pipelines where no UI should open.

---

### Cypress setup and event capture

#### Config and run
- **Headless in CI:** Use `cypress run` (not `cypress open`). Configure your CI to install browsers and run non-interactively.

#### Spy on emit and assert
- **Strategy:** Use `cy.window()` and `cy.stub` to wrap `emit`. Then validate payloads.

```js
// cypress/e2e/analytics.cy.js
describe('eldercareAnalytics emit', () => {
  it('fires with expected payloads', () => {
    cy.visit('http://localhost:5173/', {
      onBeforeLoad(win) {
        if (!win.eldercareAnalytics) win.eldercareAnalytics = {};
        const original = win.eldercareAnalytics.emit;
        win.eldercareAnalytics.emit = function (...args) {
          return original ? original.apply(this, args) : undefined;
        };
      }
    });

    cy.window().then((win) => {
      cy.stub(win.eldercareAnalytics, 'emit').as('emit');
    });

    cy.findByRole('button', { name: /book appointment/i }).click();
    cy.wait(50);

    cy.get('@emit').should('be.called');
    cy.get('@emit').should((stub) => {
      const submitCall = stub.getCalls().find(c => c.args?.[0] === 'appointment_submit');
      expect(submitCall, 'appointment_submit call').to.exist;
      const payload = submitCall.args[1];
      expect(payload).to.include({ careType: 'eldercare' });
      expect(payload.userId).to.be.a('string').and.not.empty;
    });
  });
});
```

This approach cleanly stubs and inspects calls in a headless CI run.

---

### Python alternative (Playwright for Python)

If you prefer a Python ops harness or MCP-like orchestration, use Playwright for Python to run headless Chromium, inject the same spy, and assert payloads.

```python
# analytics_test.py
from playwright.sync_api import sync_playwright

def test_eldercare_analytics_emit():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context()
        page = context.new_page()

        page.add_init_script("""
          window.__analyticsCalls = [];
          window.eldercareAnalytics = window.eldercareAnalytics || {};
          const original = window.eldercareAnalytics.emit;
          window.eldercareAnalytics.emit = function() {
            window.__analyticsCalls.push({ ts: Date.now(), args: Array.from(arguments) });
            if (typeof original === 'function') return original.apply(this, arguments);
          };
        """)

        page.goto("http://localhost:5173/")
        page.get_by_role("button", name="Book appointment").click()
        page.wait_for_timeout(50)

        calls = page.evaluate("window.__analyticsCalls")
        assert isinstance(calls, list)
        submit = next((c for c in calls if c["args"][0] == "appointment_submit"), None)
        assert submit is not None, "appointment_submit not emitted"
        payload = submit["args"][1]
        assert payload["careType"] == "eldercare"
        assert isinstance(payload["userId"], str) and payload["userId"]
        browser.close()

if __name__ == "__main__":
    test_eldercare_analytics_emit()
```

This is excellent for a deterministic, scriptable validator you can wire into any CI or ops pipeline.

---

### CI guardrails, determinism, and operational hygiene

- **Explicit headless:** Playwright tests run headless by default; keep it explicit in config and avoid opening the HTML report in CI to prevent prompts.
- **Startup gates:**
  - **Port readiness:** Add a wait step to ensure the app is listening before tests (e.g., curl health endpoint).
  - **Network isolation:** Mock external analytics transports (fetch/XHR) to avoid flakiness and network dependency.
- **Fail-fast hooks:**
  - **Global precondition:** In a `beforeAll`, assert `window.eldercareAnalytics` is present; otherwise, fail with a clear message.
  - **Schema contracts:** Validate every emitted payload via JSON Schema; fail on extra/missing fields.
- **Artifacts:** Save `__analyticsCalls` as JSON on failure for postmortem:
  - Playwright: attach as test info or write to `test-results/artifacts/analytics.json`.
  - Cypress: write via `cy.task('writeFile', ...)`.
- **Parallelism:** Run serial for analytics tests to avoid event pollution unless your analytics layer is per-session isolated.
- **Dockerized runs:** Use a Compose service for the app and a service for the test runner. Healthcheck the app container; gate the test service on `healthy` to prevent race conditions.
- **Cross-browser sanity:** Run Chromium minimum; optionally add WebKit/Firefox projects to catch API differences, but keep analytics suite focused to avoid noise.

Playwright’s CLI and config are built for headless parallel runs and CI; you can switch to headed via `--headed` locally when debugging. Ensure the reporter doesn’t auto-serve HTML in CI to avoid hangs. Headless testing improves speed and reliability in pipelines.

---

### Troubleshooting and edge cases

- **Emit never called:** Verify interaction actually triggers the code path; add `page.route` to intercept network calls if analytics emits via transport, or instrument the UI states.
- **Debounce/async:** Use small waits or explicit signals (e.g., intercept the transport request) to avoid race conditions.
- **Multiple events:** Filter calls by event name; assert counts to prevent duplicates.
- **Missing namespace:** Add a pre-test assertion that `window.eldercareAnalytics` exists; if your app lazy-loads it, await the loader.
- **Dynamic payload fields:** For volatile fields (timestamps, IDs), validate type/shape and stable fields only.
- **PII protection:** Never snapshot full payloads to logs; redact sensitive fields in artifacts.

https://copilot.microsoft.com/shares/pm6GYCYhqcrPRe6srT9ok
