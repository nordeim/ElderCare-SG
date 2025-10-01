# Phase 4 Sub-Plan — Decision Support Utilities & Community Resources
_Date: 2025-10-01_

## Objectives
- Deliver interactive tools helping families evaluate fit (cost estimator, FAQs, resource hub).
- Surface contextual prompts and downloads aligned with assessment outcomes while maintaining accessibility and analytics coverage.

## Workstreams & Tasks

### 1. Data & Seed Content
- **FAQs**
  - [x] `database/migrations/2025_10_01_060000_create_faqs_table.php`: Define schema (question, answer, category, order, is_active).
  - [x] `app/Models/Faq.php`: Add fillable fields, casts, scopeActive.
  - [x] `database/seeders/FaqSeeder.php`: Seed FAQs across key categories.
- **Resources**
  - [x] `database/seeders/ResourceSeeder.php`: Seed caregiver guide metadata (title, description, file path, persona tags).
  - [x] `database/seeders/DatabaseSeeder.php`: Register new seeders.
  - [x] Ensure downloadable assets live in `storage/app/resources/` (placeholder PDFs OK for now).

### 2. Cost Estimator
- **UI Component**
  - [x] `resources/views/components/cost-estimator.blade.php`: Build accessible form with sliders/toggles, result summary, contextual messaging.
  - [x] `resources/js/modules/cost-estimator.js`: Alpine store for pricing calculations, emits `estimator.update` events.
  - [x] `resources/js/app.js`: Import cost estimator module.
  - [ ] *(Optional)* `app/Services/CostEstimatorService.php`: Centralize pricing/subsidy mappings injected into Blade. *(Backlog — consider when pricing matrix finalised)*

### 3. FAQ Accordion
  - [x] `resources/views/components/faq.blade.php`: Accessible accordion with search/filter, keyboard nav, ARIA wiring, JSON-LD schema output.
  - [ ] `app/Http/Controllers/FaqController.php` (or extend `HomeController`): Provide FAQ data; optional JSON endpoint for future use. *(Currently handled inline in `HomeController`; separate controller optional.)*
  - [ ] `resources/lang/en/*.php` & `resources/lang/zh/*.php`: Add FAQ strings and categories. *(EN copy lives in DB; zh translation pending content team.)*

### 4. Resource Hub & Prompts
  - [x] `resources/views/components/resource-hub.blade.php`: Card grid linking to downloads with file metadata.
  - [x] `resources/views/home.blade.php`: Integrate new components (estimator, FAQ, resource hub) and contextual prompts triggered by assessment results.
  - [x] `resources/js/modules/assessment-recommendation.js`: Expose flags/events to show targeted prompts.

### 5. Styling & Utilities
  - [x] `resources/css/app.css`: Add classes for estimator controls, accordion panels, resource cards, reduced-motion handling.

### 6. Analytics & Logging
  - [x] `docs/analytics.md`: Document `estimator.open`, `estimator.update`, `faq.expand`, `resource.download` events.
  - [x] `resources/js/modules/analytics.js`: Ensure emitter handles new events if additional helpers needed.

### 7. Testing & QA
  - [x] `tests/Feature/CostEstimatorTest.php`: Verify estimator renders with pricing data.
  - [x] `tests/Feature/FaqEndpointTest.php`: Confirm FAQs load on homepage/endpoint.
  - [ ] *(Optional)* `resources/js/tests/cost-estimator.spec.js`: Unit test Alpine store calculations. *(Backlog idea.)*
  - [x] `docs/qa/scaffold-checklist.md`: Add manual scenarios for estimator, FAQ keyboard nav, resource downloads, analytics verification.

### 8. Documentation & Handoff
  - [x] `docs/ux/cost_estimator.md`: Capture pricing assumptions, subsidy logic, edge cases.
  - [x] `docs/plans/master_todo_roadmap.md`: Update Phase 4 status once implementation progresses.

## Dependencies & Risks
- Await pricing/subsidy confirmation from Ops/Finance.
- Secure caregiver guide PDFs and translations before launch.
- Monitor Lighthouse performance; consider lazy loading and console error triage from latest report.
## Milestones
1. Schema + seed data in place and reviewed with content team.
2. Estimator, FAQ, resource hub integrated with analytics and styling complete.
3. Tests/docs/QA updated; performance follow-up logged based on Lighthouse results.
