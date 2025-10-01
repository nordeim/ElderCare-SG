# Testing & CI Roadmap

_Last updated: 2025-10-01_

## Optional JavaScript Unit Testing
- **Target module**: `resources/js/modules/cost-estimator.js` (Alpine `costEstimatorComponent`).
- **Proposed tooling**:
  - `vitest` with `jsdom` environment for DOM APIs.
  - `@testing-library/dom` helpers for interaction assertions (optional).
- **Setup steps**:
  1. Install dev dependencies: `npm install -D vitest jsdom @testing-library/dom`.
  2. Create `vitest.config.ts` enabling `jsdom`, aliasing to `resources/js` if needed.
  3. Author `resources/js/tests/cost-estimator.spec.ts` covering calculation helpers (e.g., `totalMonthly`, `subsidySavings`) and analytics emission toggles via mocked emitters.
  4. Add npm scripts:
     - `npm run test:js` → `vitest run --reporter=dot`
     - `npm run test:js:watch` → `vitest`
     - `npm run test:playwright:serve` (see below) for one-shot analytics smoke with auto-serve.
- **Open questions**:
  - Confirm appetite for maintaining duplicated logic vs. relying on Playwright + Laravel feature coverage.
  - Determine desired coverage thresholds.

## CI Integration Roadmap
- **Baselines**:
  - Laravel: `php artisan test` (feature/unit suite).
  - Playwright: `npm run test:playwright` (analytics smoke).
  - Accessibility: `npm run lint:accessibility` (axe CLI).
  - Performance: `npm run lighthouse` (LHCI autorun).
- **Recommended pipeline order**:
  1. Install dependencies (`composer install --no-interaction`, `npm ci`). Cache `vendor/`, `node_modules/`, and Playwright browsers.
  2. Run `php artisan test`.
  3. Build assets once (`npm run build`) for downstream steps.
  4. Start Laravel app (e.g., `php artisan serve --host=127.0.0.1 --port=8000`) in background; execute `npm run test:playwright` pointing `PLAYWRIGHT_BASE_URL` to the serve host. Locally or in CI you can use the helper script `npm run test:playwright:serve`, which waits for the server and tears it down automatically.
  5. Execute `npm run lint:accessibility` (reusing the running server) or run in separate job to shorten sequential wall time.
  6. Run `npm run lighthouse` (optional nightly to keep CI fast, or gated via workflow dispatch).
  7. Collect artifacts: Playwright traces (`playwright-report/`), LHCI HTML report (`.lighthouseci/`), axe logs.
- **Enhancements**:
  - Add GitHub Actions job matrix for `test` (PHP + JS) vs `qa` (Playwright, Lighthouse, axe) to parallelize runtimes.
  - Gate merges on PHPUnit + Playwright; treat Lighthouse/Axe as informational until performance backlog is resolved.
  - Surface results via PR comments or status checks for faster triage.

## Recommended Follow-up Actions
- **CI workflow**: Add jobs running `php artisan test`, `npm run test:js`, `npm run test:playwright:serve`, `npm run lint:accessibility`, and scheduled `npm run lighthouse`, publishing artifacts (coverage, traces, LHCI reports).
- **Performance backlog**: Reference `docs/notes/performance-2025-10-01.md` for caching, layout profiling, and Lighthouse regression tracking tasks.
- **Security hygiene**: Run `npm audit` regularly and address low severity advisories when upgrading dependencies.
- **Documentation sync**: Keep `docs/qa/scaffold-checklist.md` and `docs/todo/phase4_decision_support_plan.md` updated after each testing cycle to signal readiness for subsequent phases.
