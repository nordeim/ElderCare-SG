# Phase 6 Sub-Plan — Data & Integration Hardening
_Date: 2025-10-02_

## Goals
- Ensure dynamic content seed data remains realistic and auditable.
- Harden external integrations (Mailchimp, booking) with retries, logging, and guardrails.
- Provide observability and analytics dashboards for engagement flows.
- Document admin readiness steps and interim processes for non-technical stakeholders.

## Task Sequence

### A. Database Seeding Modernization
- **Objective**: Expand seed coverage for programs, testimonials, staff, FAQs, resources with production-like fidelity.
- **Files**:
  - `database/seeders/ProgramSeeder.php`
  - `database/seeders/StaffSeeder.php`
  - `database/seeders/TestimonialSeeder.php`
  - `database/seeders/FaqSeeder.php`
  - `database/seeders/ResourceSeeder.php`
  - `database/seeders/DatabaseSeeder.php`
- **Checklist**:
  - Update each seeder with expanded datasets, covering edge cases (e.g., missing hotspots, varied pricing).
  - Annotate seeder entries with source attribution comments for audit trail.
  - Ensure `DatabaseSeeder` invokes new seeders in deterministic order.
  - Add per-seeder tests or artisan command snippet to validate record counts.

### B. Content Admin Readiness
- **Objective**: Document and scaffold interim CMS/editor workflow; optionally add Laravel Nova/Filament stub for future adoption.
- **Files**:
  - `docs/ops/admin_readiness.md` (new)
  - `docs/plans/master_todo_roadmap.md` (Phase 6 status updates)
  - `README.md` (admin process summary)
- **Checklist**:
  - Author admin readiness doc outlining manual content update steps and roles.
  - Capture backlog items for choosing a CMS; cross-link to roadmap Phase 6 entry.
  - Update `README.md` with quick-start instructions for content editors (e.g., seeder refresh workflow).

### C. External Integrations Hardening
- **Objective**: Strengthen Mailchimp and booking instrumentation.
- **Files**:
  - `app/Services/MailchimpService.php`
  - `app/Services/BookingService.php`
  - `config/services.php`
  - `routes/web.php` (if adding webhook endpoints)
  - `resources/js/modules/analytics.js`
  - `tests/Feature/NewsletterSubscriptionTest.php` (new) / update existing tests
- **Checklist**:
  - Add retries/backoff and structured logging to Mailchimp service.
  - Capture failures in analytics (`analytics.js`) via `mailchimp.failure` event.
  - Extend booking service logging to include request context and fallbacks.
  - Create feature test ensuring newsletter signup handles API errors gracefully.
  - Document configuration expectations in `.env.example`.

### D. Analytics Dashboard & Observability
- **Objective**: Configure Plausible/custom dashboards and log surfacing.
- **Files**:
  - `docs/analytics.md`
  - `config/analytics.php`
  - `resources/js/modules/analytics.js`
  - `docs/ops/logging.md` (new)
- **Checklist**:
  - Update analytics config with goal definitions (assessment completion, tour engagement, estimator usage).
  - Add event emission guardrails to prevent noisy logging.
  - Draft logging runbook capturing how to monitor `storage/logs/laravel.log` and external dashboards.

### E. Validation & QA
- **Objective**: Verify data integrity and integration robustness.
- **Files**:
  - `docs/qa/scaffold-checklist.md`
  - `package.json` (scripts for seeding/testing)
  - `tests/Feature/*` (add new coverage)
- **Checklist**:
  - Update QA checklist with new data/integration validation steps.
  - Add `npm`/`artisan` scripts for refreshing seeds and running integration smoke tests.
  - Execute `php artisan migrate:fresh --seed` in CI or local QA to confirm seeding success.

## Risks & Mitigations
- **API Rate Limits**: Use exponential backoff and circuit breakers in Mailchimp calls. Mock APIs in tests.
- **Data Drift**: Establish scheduled seed reviews and document change log in `docs/ops/admin_readiness.md`.
- **Observability Gaps**: Prioritize logging/monitoring enhancements before enabling new integrations.

## Success Criteria
- Seeded data matches documented scenarios; QA checklist includes verification steps.
- Mailchimp and booking services handle failures gracefully with actionable logs.
- Analytics goals documented and traceable; logging runbook published.
- Stakeholders have a reference for updating content without code deploys.
