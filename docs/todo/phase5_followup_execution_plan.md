# Phase 5 Follow-up Execution Plan
_Date: 2025-10-02_

## Goals
- [x] **Preserve roadmap alignment**: Address Phase 5 outstanding documentation and UI tasks while fixing newly identified component regressions.
- [x] **Maintain UX polish**: Ensure design-system refinements continue to meet accessibility and performance benchmarks.
- [x] **Document for continuity**: Produce reference materials that future contributors can rely on without rediscovery.

## Task Sequence

### A. Resolve Component Regressions (Blocker)
- [x] **Fix `cost-estimator` select markup**
  - Replace the `{{ ... }}` placeholder with the intended `<select>` attributes (likely `aria-label` or `data` hooks).
  - Confirm Alpine bindings (`x-model`, `x-for`) remain intact.
- [x] **Repair `faq` template structure**
  - Close the inner `<template x-for>` loop correctly and ensure matching `</article>`, `</div>`, and `</section>` tags.
  - Replace the JSON-LD `{{ ... }}` placeholder with `@json([...])` output mirroring existing schema logic.
  - Verify `data-analytics-id` attributes still fire `faq.expand` events.
- [x] **Smoke test views**
  - Run `php artisan view:clear` and load the FAQ and estimator sections locally to confirm no Blade compilation errors.
  - Run `npm run build` (or `npm run dev`) to confirm Tailwind compiles.

### B. Complete Phase 5 Workstream 3 — Component Catalog Documentation
- [x] **Outline `docs/components.md`**
  - Create intro describing document purpose and how it maps to `docs/plans/master_todo_roadmap.md`.
  - Define sections for Hero, Assessment, Availability Badge, Cost Estimator, FAQ, Resource Hub, Assessment Prompts, Virtual Tour.
- [x] **Document component contracts**
  - For each component, enumerate props/slots, expected data sources (e.g., `AvailabilityService`), analytics events, and accessibility considerations.
  - Include Blade usage snippets reflecting semantic colors and fluid typography classes.
- [x] **Cross-reference QA and testing artifacts**
  - Link to `docs/qa/scaffold-checklist.md`, relevant runbooks, and testing commands (`php artisan test`, `npm run test:js`, `npm run test:playwright:serve`).
- [x] **Peer/self review**
  - Proofread for clarity and alignment with UX guidelines; ensure terminology matches existing docs.

### C. (Optional) Phase 5 Workstream 4 — `/ui-kit` Playground *(deferred)*
- [x] **Decision outcome (2025-10-02)**
  - Deferred to backlog. Revisit once performance targets trend toward ≥0.9.

### D. Validation & Regression Safety
- [x] **Automated checks**
  - `npm run build`
  - `npm run test:js`
  - `npm run test:playwright:serve`
  - `php artisan test`
- [x] **Manual QA**
  - Follow the typography/color regression steps and update `docs/qa/scaffold-checklist.md` if new checks are required.
  - Capture visual diffs for hero, FAQ, and estimator sections (screenshots or notes).
- [x] **Performance & Accessibility spot checks**
  - Run targeted Lighthouse audits on homepage.
  - Use axe browser extension or `npm run lint:accessibility` to confirm no new violations.

### E. Completion Handoff
- [x] **Update roadmap artifacts**
  - Annotated `docs/plans/master_todo_roadmap.md` with Phase 5 completion notes (tokens/typography/docs shipped, `/ui-kit` deferred).
  - Updated `phase5_status_analysis_assessment.md` to reflect documentation delivery and deferred playground status.
- [x] **Communicate outcomes**
  - Summary prepared for project channel:
    > Phase 5 complete — semantic tokens, fluid typography, and `docs/components.md` published. FAQ and cost estimator regressions resolved; QA scripts (`npm run build`, `composer phpunit`, `npm run lint:accessibility`, `npm run lighthouse`) pass. `/ui-kit` playground deferred pending performance follow-up. Saved `main_landing_page.html` for reference.
  - Backlog flagged for `/ui-kit` playground alongside performance remediation tasks.

## Dependencies & Risks
- [x] **Content accuracy**: Ensure component documentation reflects latest props/events; coordinate with design/PM for confirmation.
- [x] **Timeboxing optional work**: If `/ui-kit` is deferred, capture rationale and move to backlog.
- [x] **Regression mitigation**: Use feature branches and progressive commits to isolate risk.

## Done Definition
- [x] **Phase 5 outstanding tasks closed**: `docs/components.md` merged; `/ui-kit` playground deferred with documented rationale.
- [x] **No Blade/Tailwind errors**: Builds and runtime views render cleanly post fixes.
- [x] **Documentation disseminated**: Roadmap/status docs updated; communication summary ready for distribution.
