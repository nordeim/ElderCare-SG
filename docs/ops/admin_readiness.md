# ElderCare SG Admin Readiness Guide
_Date: 2025-10-02_

## 1. Purpose
This guide equips non-technical stakeholders (operations, care concierge, marketing) with a clear playbook for updating ElderCare SG content while engineering continues Phase 6 hardening. It documents roles, tooling, validation steps, and escalation paths.

## 2. Key Roles & Responsibilities
- **Care Concierge Lead (Ops)**: Owns programs/testimonials updates, triggers seeder refresh requests, reviews copy accuracy.
- **Marketing Partner**: Oversees resource hub assets, webinar links, and preview imagery.
- **Engineering On-Call**: Applies seeder updates, runs migrations, and monitors logs for failures.
- **Design System Steward**: Ensures visual fidelity when assets change (preview images, downloadable guides).

## 3. Content Update Workflow
1. **Draft Changes**
   - Capture new program details, testimonials, or FAQ responses in the shared Content Brief template.
   - Include source attribution, pricing changes, and target publish date.
2. **Submit Request**
   - Open a ticket in the content board tagged `phase6-admin`. Attach Content Brief.
   - Assign to Engineering On-Call with desired production date.
3. **Engineering Execution**
   - Create feature branch, update appropriate seeder(s) under `database/seeders/`.
   - Run validation commands (see §6) and attach logs to ticket.
4. **Stakeholder Review**
   - Care Concierge and Marketing validate staging build, confirming copy, pricing, and imagery.
   - Design System Steward signs off on visuals.
5. **Deploy & Announce**
   - Merge branch, deploy per release checklist.
   - Post summary in project channel noting data refresh and impacted components.

## 4. Interim Editing Options
While CMS tooling is evaluated, the following interim methods are supported:
- **Seeder Refresh**: Primary method for canonical content. Ensures reproducibility and QA coverage.
- **`main_landing_page.html` Snapshot**: Use as reference only; do not edit for live changes.
- **Hotfix Patch**: For critical production corrections, Engineering On-Call may patch Blade templates directly; ensure seeder parity is restored within one business day.

## 5. Content Matrices
| Area | Source File(s) | Stakeholder Owner | Notes |
|---|---|---|---|
| Programs | `database/seeders/ProgramSeeder.php` | Care Concierge | Pricing, capacity, availability status |
| Staff Profiles | `database/seeders/StaffSeeder.php` | Ops | Credentials, languages, bios |
| Testimonials | `database/seeders/TestimonialSeeder.php` | Marketing | Keep persona diversity; requires consent checkbox |
| FAQs | `database/seeders/FaqSeeder.php` | Ops + Marketing | Tagging affects search filters |
| Resource Hub | `database/seeders/ResourceSeeder.php` | Marketing | External URLs must be vetted; preview images stored under `public/assets/resources/` |

## 6. Validation Checklist (Engineering)
- `php artisan migrate:fresh --seed`
- `composer phpunit -- --filter=ResourceTest` *(when added)*
- `npm run build`
- Manual spot check: `php artisan serve` → http://127.0.0.1:8000, verify updated sections
- Log review:
  - `tail -n 200 storage/logs/laravel.log`
  - `tail -n 200 storage/logs/analytics.log`
- Analytics signals: confirm Plausible goals `mailchimp-success`, `booking-click`, `resource-download`, `estimator-submit` are incrementing (see [Analytics Dashboard](analytics_dashboard.md)).

## 7. Escalation
- **Broken Build / Seeder Failure**: Notify Engineering On-Call in Slack `#eng-urgent`; attach console output.
- **Content Accuracy Concerns**: Route to Care Concierge Lead; hold deployment until resolved.
- **Asset Hosting Issues**: Contact Marketing Partner to confirm CDN links; inform Design System Steward for fallback imagery.

## 8. Backlog & Future Automation
- Evaluate Laravel Nova vs Filament prototype by 2025-11-01 (assigned: Engineering On-Call).
- Investigate CMS ingestion pipeline (Notion → seeder) for programs and FAQs.
- Add GitHub Actions workflow to run seeding validation on PRs touching `database/seeders/`.

## 9. Document Change Log
| Date | Author | Description |
|---|---|---|
| 2025-10-02 | Cascade AI | Initial admin readiness guide drafted as Phase 6 Track B deliverable |
