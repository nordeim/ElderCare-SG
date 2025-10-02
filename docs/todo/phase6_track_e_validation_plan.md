# Phase 6 Track E — Validation & Test Coverage Plan
_Date: 2025-10-02_

## Objectives
- Document validation procedures for Phase 6 data/integration changes so ops & QA can reproduce checks.
- Expand automated coverage targeting high-risk flows introduced in Tracks A–D (seed integrity, analytics queue, integrations).

## Scope
1. **Documentation Enhancements**
   - Summarize validation commands, manual QA, and monitoring steps into a consolidated checklist.
   - Reference relevant docs (`admin_readiness.md`, analytics dashboard) and add delta notes where procedures changed.
   - Provide troubleshooting playbook for common failure modes (seeding errors, Mailchimp API, analytics queue mismatches).

2. **Automated Tests**
   - Add feature tests for:
     - Resource download CTA fallback (local vs external link) to ensure Blade logic matches Track A updates.
     - Analytics queue serialization/dispatch from session (covers Mailchimp + booking events).
   - Consider Laravel Pest or PHPUnit for integration tests hitting `NewsletterController` & homepage view.

3. **Tooling Updates**
   - Introduce `php artisan test --group=phase6` group to bundle new tests.
   - Evaluate GitHub Actions matrix adjustments (if necessary) to include analytics log tail step.

4. **Validation Runbook**
   - Create or update `docs/ops/validation_checklist.md` capturing:
     - Commands: `php artisan migrate:fresh --seed`, `composer phpunit -- --group=phase6`, `npm run lint:accessibility`, `npm run lighthouse`.
     - Manual QA script (newsletter submit, booking CTA click, resource download for external item, estimator flow).
     - Log and dashboard review points.

## Deliverables
- Updated documentation files: `docs/ops/admin_readiness.md`, new `docs/ops/validation_checklist.md`, references in `docs/plans/master_todo_roadmap.md`.
- New/expanded tests under `tests/Feature/` and potential `tests/Unit/` with clear naming.
- Config updates (if required) to tag tests or expose env toggles.

## Success Criteria
- All Track E tests pass locally via new group command.
- Validation docs provide end-to-end guidance without relying on prior session context.
- QA tooling pipeline (lint, Lighthouse) remains green with performance warnings acknowledged as backlog.
