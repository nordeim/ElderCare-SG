# ElderCare SG Component Catalog
_Date: 2025-10-02_

## Purpose
This catalog documents the primary Blade components powering the ElderCare SG experience. It captures props, data dependencies, analytics hooks, accessibility considerations, and usage examples to accelerate onboarding and ensure design-system consistency.

## Table of Contents
- [Hero](#hero)
- [Availability Badge](#availability-badge)
- [Assessment](#assessment)
- [Assessment Prompts](#assessment-prompts)
- [Cost Estimator](#cost-estimator)
- [FAQ Accordion](#faq-accordion)
- [Resource Hub](#resource-hub)
- [Virtual Tour](#virtual-tour)

---

## Hero
- **View**: `resources/views/components/hero.blade.php`
- **Props**: `headline`, `subheadline`, `primaryCta`, `secondaryCta`
- **Data Dependencies**:
  - `Alpine.store('availability')` hydrated by `AvailabilityService`
  - `Alpine.store('assessmentRecommendation')` (future assessment integration)
- **Analytics Hooks**: Availability refresh button triggers `availability.refresh` via Alpine store
- **Accessibility**: `aria-live` status badge, reduced-motion respected via Tailwind utilities
- **Usage**:
  ```blade
  <x-hero :headline="$copy['headline']" />
  ```

## Availability Badge
- **View**: Inline within `hero.blade.php`
- **Props**: n/a (reads Alpine store state)
- **Data Dependencies**: `window.eldercare.availabilityMessages`, `AvailabilityService`
- **Analytics Hooks**: `availability.status` events dispatched from `resources/js/modules/analytics.js`
- **Accessibility**: Screen-reader friendly with `role="status"`, `aria-live="polite"`

## Assessment
- **View**: `resources/views/components/assessment.blade.php`
- **Props**: `sectionId`, `questions`, `resultSlots`
- **Data Dependencies**: `resources/js/modules/assessment.js`, `AssessmentService` (planned)
- **Analytics Hooks**: `assessment.start`, `assessment.complete`, `assessment.exit`
- **Accessibility**: `aria-describedby` on progress indicators, keyboard navigable form flow

## Assessment Prompts
- **View**: `resources/views/components/assessment-prompts.blade.php`
- **Props**: `prompts`, `context`
- **Data Dependencies**: Shares state with assessment Alpine store
- **Analytics Hooks**: `assessment.prompt_show`, `assessment.prompt_click`
- **Accessibility**: Focus ring utilities, cards are keyboard accessible

## Cost Estimator
- **View**: `resources/views/components/cost-estimator.blade.php`
- **Props**: `sectionId`, `kicker`, `headline`, `description`
- **Data Dependencies**: `resources/js/modules/cost-estimator.js`
- **Analytics Hooks**: `estimator.toggle-details`, `estimator.booking-cta`
- **Accessibility**: Uses semantic form controls with descriptive labels, toggle switch accessible via `peer`
- **Usage**:
  ```blade
  <x-cost-estimator :section-id="'estimator'" />
  ```

## FAQ Accordion
- **View**: `resources/views/components/faq.blade.php`
- **Props**: `faqs`, `sectionId`, `headline`, `kicker`, `description`
- **Data Dependencies**: `faqAccordion` Alpine store defined in component script
- **Analytics Hooks**: Emits `faq.expand` with `faqId`
- **Accessibility**: Buttons expose `aria-expanded`, panels use `role="region"`
- **Structured Data**: Outputs FAQPage schema via JSON-LD script

## Resource Hub
- **View**: `resources/views/components/resource-hub.blade.php`
- **Props**: `resources`, `headline`, `description`
- **Data Dependencies**: Eloquent `Resource` model seeded in Phase 4
- **Analytics Hooks**: `resource.download` (defined in component)
- **Accessibility**: Inline text alternatives, `data-disabled="true"` states for unavailable downloads

## Virtual Tour
- **View**: `resources/views/components/virtual-tour.blade.php`
- **Props**: `tour`, `staff`
- **Data Dependencies**: `resources/data/tour_hotspots.json`, `Staff` model, `tour.js`
- **Analytics Hooks**: `tour.open`, `tour.hotspot`, `tour.complete`
- **Accessibility**: Focus trapping in modal, transcripts at `public/media/transcripts/tour-main.md`

---

## QA & Testing References
- **Automated**: `npm run build`, `npm run test:js`, `npm run test:playwright:serve`, `php artisan test`
- **Manual**: `docs/qa/scaffold-checklist.md` (update when components change)
- **Runbooks**: `docs/ops/runbooks/availability.md` for availability service, `docs/notes/performance-2025-10-01.md` for performance follow-ups

---

## Maintenance Notes
- Keep props synced with component signatures when refactoring.
- Ensure analytics event names align with `docs/analytics.md` taxonomy.
- When adding components, append to this catalog and cross-link from `docs/plans/master_todo_roadmap.md` as needed.
