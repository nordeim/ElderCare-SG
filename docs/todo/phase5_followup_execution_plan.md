# Phase 5 Follow-up Execution Plan
_Date: 2025-10-02_

## Goals
- [ ] **Preserve roadmap alignment**: Address Phase 5 outstanding documentation and UI tasks while fixing newly identified component regressions.
- [ ] **Maintain UX polish**: Ensure design-system refinements continue to meet accessibility and performance benchmarks.
- [ ] **Document for continuity**: Produce reference materials that future contributors can rely on without rediscovery.

## Task Sequence

### A. Resolve Component Regressions (Blocker)
- [ ] **Fix `cost-estimator` select markup**
  - Replace the `{{ ... }}` placeholder with the intended `<select>` attributes (likely `aria-label` or `data` hooks).
  - Confirm Alpine bindings (`x-model`, `x-for`) remain intact.
- [ ] **Repair `faq` template structure**
  - Close the inner `<template x-for>` loop correctly and ensure matching `</article>`, `</div>`, and `</section>` tags.
  - Replace the JSON-LD `{{ ... }}` placeholder with `@json([...])` output mirroring existing schema logic.
  - Verify `data-analytics-id` attributes still fire `faq.expand` events.
- [ ] **Smoke test views**
  - Run `php artisan view:clear` and load the FAQ and estimator sections locally to confirm no Blade compilation errors.
  - Run `npm run build` (or `npm run dev`) to confirm Tailwind compiles.

### B. Complete Phase 5 Workstream 3 — Component Catalog Documentation
- [ ] **Outline `docs/components.md`**
  - Create intro describing document purpose and how it maps to `docs/plans/master_todo_roadmap.md`.
  - Define sections for Hero, Assessment, Availability Badge, Cost Estimator, FAQ, Resource Hub, Assessment Prompts, Virtual Tour.
- [ ] **Document component contracts**
  - For each component, enumerate props/slots, expected data sources (e.g., `AvailabilityService`), analytics events, and accessibility considerations.
  - Include Blade usage snippets reflecting semantic colors and fluid typography classes.
- [ ] **Cross-reference QA and testing artifacts**
  - Link to `docs/qa/scaffold-checklist.md`, relevant runbooks, and testing commands (`php artisan test`, `npm run test:js`, `npm run test:playwright:serve`).
- [ ] **Peer/self review**
  - Proofread for clarity and alignment with UX guidelines; ensure terminology matches existing docs.

### C. (Optional) Phase 5 Workstream 4 — `/ui-kit` Playground
- [ ] **Decision checkpoint**
  - Confirm with stakeholders whether the optional playground delivers sufficient ROI for current sprint.
- [x] **Decision outcome (2025-10-02)**
  - Deferred to backlog. Current focus is on performance remediation surfaced by Lighthouse (`first-contentful-paint`, `largest-contentful-paint`, `mainthread-work-breakdown`). Revisit after perf targets trend toward ≥0.9.
- [ ] **Implement if approved**
  - Add local-only route guarded by `app()->environment('local')` in `routes/web.php`.
  - Create `UIPlaygroundController` rendering curated component states and handle analytics stubbing.
  - Build `resources/views/ui/playground.blade.php` with sections linking to documentation and showcasing variant states.
  - Update `docs/components.md` with playground usage notes.

### D. Validation & Regression Safety
- [ ] **Automated checks**
  - `npm run build`
  - `npm run test:js`
  - `npm run test:playwright:serve`
  - `php artisan test`
- [ ] **Manual QA**
  - Follow the typography/color regression steps and update `docs/qa/scaffold-checklist.md` if new checks are required.
  - Capture visual diffs for hero, FAQ, and estimator sections (screenshots or notes).
- [ ] **Performance & Accessibility spot checks**
  - Run targeted Lighthouse audits on homepage.
  - Use axe browser extension or `npm run lint:accessibility` to confirm no new violations.

### E. Completion Handoff
- [ ] **Update roadmap artifacts**
  - Annotate `docs/plans/master_todo_roadmap.md` Phase 5 status once documentation/UI tasks land.
  - Record progress in `phase5_status_analysis_assessment.md` with before/after notes.
- [ ] **Communicate outcomes**
  - Share summary in project channel highlighting component fixes, documentation availability, and QA results.
  - Flag any backlog items spun off (e.g., future enhancements for `/ui-kit`).

## Dependencies & Risks
- [ ] **Content accuracy**: Ensure component documentation reflects latest props/events; coordinate with design/PM for confirmation.
- [ ] **Timeboxing optional work**: If `/ui-kit` is deferred, capture rationale and move to backlog.
- [ ] **Regression mitigation**: Use feature branches and progressive commits to isolate risk.

## Done Definition
- [ ] **Phase 5 outstanding tasks closed**: `docs/components.md` merged; optional playground either delivered or formally deferred.
- [ ] **No Blade/Tailwind errors**: Builds and runtime views render cleanly post fixes.
- [ ] **Documentation disseminated**: Team notified, roadmap and status assessments updated with new references.
