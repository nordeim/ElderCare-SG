# Phase 7 — QA Automation & Launch Readiness Sub-Plan
_Date: 2025-10-02_

## 1. Readiness Confirmation
- Phase 6 validation suite (`composer phpunit`, `npm run lint:accessibility`, `npm run lighthouse`) executed successfully on 2025-10-02.
- Analytics logging verified via `storage/logs/analytics.log`; resource hub tests confirm CTA resiliency.
- Performance warnings acknowledged and deferred per stakeholder instruction. No blocking regressions detected.

## 2. Objectives
1. Automate core QA checks (accessibility, Lighthouse, Laravel tests) within CI.
2. Establish visual regression baseline for hero, assessment, tour, estimator flows.
3. Document manual QA script updates reflecting new automation coverage.
4. Define performance budgets and launch checklist for stakeholder sign-off.

## 3. Workstreams & File Checklists

### 3.1 GitHub Actions CI Pipeline
- **Files**: `/.github/workflows/qa-ci.yml` *(new)*, `package.json` *(update if new scripts needed)*, `composer.json` *(ensure test group scripts available)*.
- **Tasks**:
  - Author workflow running on `push` + `pull_request` with matrix (PHP 8.3, Node 20).
  - Steps: install dependencies (`composer install --prefer-dist --no-progress`, `npm ci`), run `php artisan test --group=phase6`, `npm run lint:accessibility`, `npm run lighthouse -- --preset=ci` (ensure non-blocking on performance via `continue-on-error` if required).
  - Cache composer/npm directories for speed; upload Lighthouse report artifact.
- **Checklist**:
  - [ ] Workflow syntax validated via `act` or `gh workflow lint`.
  - [ ] README badge updated if applicable (optional).

### 3.2 Visual Regression Suite
- **Files**: `package.json`, `pnpm-lock.yaml` *(if using Playwright)*, `tests/Browser/playwright.config.ts` *(new)*, `tests/Browser/specs/*.spec.ts` *(new)*, `.github/workflows/qa-ci.yml` *(extend to run visual tests nightly)*.
- **Tasks**:
  - Introduce Playwright with UI mode disabled for CI.
  - Capture baselines for `/`, assessment modal, virtual tour, estimator summary.
  - Store snapshots under `tests/Browser/__screenshots__/` committed to repo.
  - Integrate to workflow on separate job triggered nightly or on demand.
- **Checklist**:
  - [ ] `npx playwright install --with-deps` executed locally.
  - [ ] Snapshots generated and version-controlled.
  - [ ] CI uploads diff artifacts on failure.

### 3.3 Manual QA Script Refresh
- **Files**: `docs/qa/scaffold-checklist.md`, `docs/qa/launch-checklist.md`, `docs/ops/validation_checklist.md` *(cross-link updates)*.
- **Tasks**:
  - Align manual steps with automated coverage; focus on qualitative checks (content accuracy, localization, assistive tech spot tests).
  - Add section referencing CI workflow status and how to re-run jobs.
  - Update launch checklist with CI completion gate and analytics verification tasks.
- **Checklist**:
  - [ ] Manual QA doc reviewed by Ops & QA stakeholders.
  - [ ] Launch checklist includes performance waiver note if thresholds unmet.

### 3.4 Performance Budgets & Lighthouse Config
- **Files**: `lighthouserc.json` *(update thresholds)*, `package.json` *(script flags)*, `.github/workflows/qa-ci.yml` *(use budgets)*, `docs/notes/performance-2025-10-01.md` *(append decisions)*.
- **Tasks**:
  - Set realistic budgets (e.g., FCP ≤ 2.0s, LCP ≤ 2.5s) while acknowledging current metrics.<br>  - Configure Lighthouse to warn vs. fail for interim thresholds; document backlog actions.
  - Add CLI flag `--budget-path=./lighthouse.budgets.json` *(new file)*.
- **Checklist**:
  - [ ] Budget file validated via local `npm run lighthouse -- --budget-path=...`.
  - [ ] Performance rationale documented for leadership sign-off.

### 3.5 Launch Readiness Ops
- **Files**: `docs/qa/launch-checklist.md`, `docs/ops/admin_readiness.md` *(append CI references)*, `README.md` *(optional badge/instructions)*.
- **Tasks**:
  - Include analytics dashboard review, log tail instructions, backup strategy, and rollback plan.
  - Document feature flag strategy (assessment, tour, estimator) to support staged rollout.
- **Checklist**:
  - [ ] Launch checklist reviewed with Ops/Marketing.
  - [ ] Rollback steps verified (database backup path, feature flag toggles).

## 4. Validation Strategy
- Local dry-run: execute workflow steps manually (`composer install`, `npm ci`, `php artisan test --group=phase6`, `npm run lint:accessibility`, `npm run lighthouse -- --preset=ci`).
- Visual regression: confirm Playwright tests pass locally; review snapshot diffs.
- CI verification: confirm workflow passes on feature branch PR before merging.

## 5. Risks & Mitigations
- **CI duration**: Use dependency caching and parallel jobs to keep under 8 minutes.
- **Playwright flakiness**: Utilize deterministic seeds and `retry` configuration; disable animations via CSS toggles.
- **Performance budgets**: If thresholds fail, capture ticket references and use `continue-on-error` while backlog tasks remain open.

## 6. Sign-Off Criteria
- CI workflow green on main branch with required checks enforced.
- Visual regression suite baselines approved by design/QA leads.
- QA documentation updated and acknowledged by Ops.
- Launch checklist completed with rollback and analytics steps verified.
