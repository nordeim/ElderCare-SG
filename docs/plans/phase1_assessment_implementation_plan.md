# Phase 1 Implementation Plan — Guided Needs Assessment
_Date: 2025-10-01_

## 1. Overview
Deliver the interactive Guided Needs Assessment with personalized outcomes, analytics, and localization scaffolding while preserving existing MVC + service architecture.

## 2. Targeted Code Changes
- **`resources/views/home.blade.php`**
  - Insert `<x-assessment />` component entry point near hero/program sections.
  - Add conditionals to render personalized program/testimonial recommendations and CTA text based on assessment results.
  - Ensure progressive enhancement fallback when JS disabled.
- **`resources/views/components/assessment.blade.php` (new)**
  - Blade component rendering multi-step questionnaire modal/inline panel.
  - Slots for header/cta, default copy pulled via localization.
  - Emits Alpine events and exposes analytics hooks.
- **`resources/js/modules/assessment.js` (new)**
  - Alpine store managing answers, step progression, scoring, and CTA personalization payload.
  - Integrates with analytics dispatcher and optional POST to `/assessment-insights`.
  - Handles localization of question copy via data attributes passed from Blade.
- **`resources/js/app.js`**
  - Import the new `assessment.js` module and register store on `window` if needed.
- **`app/Services/AssessmentService.php` (new)**
  - Methods: `determineSegment(array $answers)`, `recommendPrograms()`, `ctaLabel()`, `logOutcome()`.
  - Utilize dependency injection for logger/analytics, rely on `Program`/`Testimonial` scopes.
- **`app/Providers/AppServiceProvider.php`** (if needed)
  - Share assessment segments with views or register component alias.
- **`routes/web.php`**
  - Add POST route `/assessment-insights` handled by new controller storing optional analytics/logging.
- **`app/Http/Controllers/AssessmentController.php` (new)**
  - Validates incoming payload, invokes `AssessmentService::logOutcome`, returns JSON success.
- **`app/Http/Requests/AssessmentSubmissionRequest.php` (new)**
  - Validates answers/segment to protect backend endpoint.
- **`resources/lang/en/assessment.php` & `resources/lang/zh/assessment.php` (new)**
  - Provide question copy, button labels, error messages.
- **`resources/views/partials/nav.blade.php` + `components/hero.blade.php`**
  - Update CTAs (`Take assessment`) and integrate dynamic copy from localization.
- **`public/assets`**
  - Placeholder icons/illustrations for assessment steps (optional, ensure licensing).

## 3. Supporting Assets & Docs
- **`docs/ux/assessment.md`**: canonical UX spec (already created).
- **`tests/Feature/AssessmentSubmissionTest.php`**: verify endpoint validation/logging.
- **`tests/Unit/AssessmentServiceTest.php`**: ensure persona mapping logic remains predictable.

## 4. Work Sequence
1. Scaffold localization files and Blade component skeleton with static sample data.
2. Build Alpine store & front-end flow; integrate with Embla styling/theme.
3. Implement backend service/controller for logging analytics results.
4. Wire personalization outputs into `home.blade.php` (CTA labels, recommended sections).
5. Add tests and run `npm run build` / `phpunit` to verify integration.
6. Conduct accessibility audit on assessment experience.

## 5. Dependencies & Considerations
- Confirm final copy from Phase 0 deliverables before localization strings freeze.
- Determine whether to persist assessment outcomes (currently logging only).
- Ensure new route protected against spam/bots (consider rate limiting, honeypot).
- Feature flag assessment component for gradual rollout (config-driven toggle).

## 6. Definition of Done
- Assessment renders and operates smoothly across desktop/mobile.
- Persona segmentation updates on-page recommendations and CTA labels.
- Analytics events firing correctly and logging captured.
- Localization functional for EN/ZH.
- Accessibility (axe CLI) passes with no critical issues.
