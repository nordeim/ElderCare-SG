# Phase 2 ToDo Plan — Hero Enhancements & Availability UX
_Date: 2025-10-01_
_Status: Draft for execution_

## Readiness Summary
- **Phase 0**: Personas, data mapping, and asset inventory documented (`docs/plans/phase0_alignment_research_brief.md`, `docs/plans/asset_inventory.md`). Pending stakeholder workshops and booking API discovery handed to Ops.
- **Phase 1**: Assessment personalization, analytics hooks, and accessibility audits complete (`docs/plans/phase1_assessment_implementation_plan.md`). No engineering blockers.
- **Decision**: Proceed with Phase 2 execution while coordinating Ops on API credentials/SLA.

## Track A — Availability Data Layer
- **Objective**: Serve real-time visit availability with caching and fallbacks.
- **Files**
  - `app/Services/AvailabilityService.php` *(new)*
  - `app/Services/Providers/MockAvailabilityProvider.php` *(new)*
  - `app/Http/Controllers/AvailabilityController.php` *(new)*
  - `routes/api.php`, `routes/web.php`
  - `config/services.php`, `.env.example`
  - `tests/Unit/AvailabilityServiceTest.php` *(new)*
  - `tests/Feature/AvailabilityEndpointTest.php` *(new)*
- **Checklist**
  - [ ] Implement provider abstraction with mock adapter + TODO for real API.
  - [ ] Add caching (configurable TTL) and stale detection logic.
  - [ ] Expose `/api/availability` JSON endpoint with rate limiting + logging.
  - [ ] Feature flag via `AVAILABILITY_DRIVER` env variable.
  - [ ] Unit + feature tests cover success, fallback, cache hit, and error paths.
  - [ ] Update `docs/ops/runbooks/availability.md` with API rotation steps.

## Track B — Hero UI Integration
- **Objective**: Display availability badge and fallback messaging in hero.
- **Files**
  - `resources/views/components/hero.blade.php`
  - `resources/js/modules/availability.js` *(new)*
  - `resources/js/app.js`
  - `resources/css/app.css`
  - `resources/views/home.blade.php`
- **Checklist**
  - [ ] Inject availability badge, last-updated timestamp, and fallback text.
  - [ ] Implement Alpine store polling `/api/availability` with exponential backoff.
  - [ ] Broadcast analytics events (`availability.loaded`, `availability.error`).
  - [ ] Ensure aria-live and keyboard visibility compliance.
  - [ ] Axe audit hero after integration.
  - [ ] Manual QA scenario for stale data fallback.

## Track C — Localization Toggle
- **Objective**: Enable EN/中文 switch across nav + hero copy.
- **Files**
  - `resources/views/partials/nav.blade.php`
  - `app/Http/Middleware/SetLocale.php` *(new)*
  - `routes/web.php`
  - `resources/lang/en/hero.php`, `resources/lang/zh/hero.php` *(new)*
  - `config/app.php`
  - `tests/Feature/LocaleSwitchTest.php` *(new)*
- **Checklist**
  - [ ] Add locale switcher with accessible button states and focus trapping for dropdown.
  - [ ] Persist locale preference via middleware (session + optional cookie).
  - [ ] Load hero/nav copy from localization files; fallback to English.
  - [ ] Feature test ensures locale persists between requests.
  - [ ] Manual QA: screen reader announces active locale; contrast verified.

## Track D — Analytics & Observability
- **Objective**: Instrument availability + locale interactions.
- **Files**
  - `resources/js/modules/analytics.js` (extend)
  - `docs/analytics.md` *(new)*
  - `docs/qa/scaffold-checklist.md`
- **Checklist**
  - [ ] Emit `availability.loaded`, `availability.error`, `locale.changed` with context payloads.
  - [ ] Document events in analytics guide + update Plausible dashboard requirements.
  - [ ] Add hero availability QA steps to scaffold checklist.
  - [ ] Verify `AvailabilityService` logs warnings on API failures.

## Track E — Documentation & Ops Alignment
- **Objective**: Provide operational clarity and track dependencies.
- **Files**
  - `docs/ops/runbooks/availability.md` *(new)*
  - `docs/plans/phase0_alignment_research_brief.md` (update checklist once workshops/API discovery scheduled)
  - `docs/plans/master_todo_roadmap.md` (progress notes after each track)
- **Checklist**
  - [ ] Document credential rotation, cache flush, alert thresholds.
  - [ ] Link outstanding Ops actions (workshops, API discovery) with owners + due dates.
  - [ ] Record Track completions in roadmap for stakeholder visibility.

## Risks & Mitigations
- **API Access Delay**: Mitigate via mock provider; add TODO to swap to live adapter once credentials arrive.
- **Performance Impact**: Throttle polling and reuse cached response to limit load.
- **Localization Gap**: Coordinate with language vendor; fallback copy in English until translations approved.

## Communication Plan
- Weekly sync to demo Track progress.
- Update master roadmap with status per track.
- Notify Ops when availability endpoint ready for staging test.
