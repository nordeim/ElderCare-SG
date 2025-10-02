# Phase 6 Validation Checklist
_Date: 2025-10-02_

## 1. Purpose
Provides a repeatable script for engineers and operations to validate Phase 6 data, integrations, and analytics updates.

## 2. Preflight
- Ensure `.env` configured with Mailchimp/Plausible keys (see `.env.example`).
- Run `php artisan config:clear` to refresh configuration cache.
- Confirm storage links and logs directory are writable (`php artisan storage:link`).

## 3. Automated Commands
1. `php artisan migrate:fresh --seed`  
   - Expect seeded programs, staff, testimonials, resources, FAQs to populate without errors.
2. `composer phpunit -- --group=phase6`  
   - Executes Track C/D/E coverage (newsletter, booking, resource download tests).  
   - If failures occur, inspect `storage/logs/laravel.log` and `storage/logs/analytics.log`.
3. `npm run lint:accessibility`  
   - Pass indicates no axe CLI violations on landing page. Document any warnings.
4. `npm run lighthouse`  
   - Capture report at `storage/app/lighthouse`. Performance warnings logged as backlog items.

## 4. Manual QA
- Launch dev server (`./launch_laravel_dev_server.sh`) and open http://localhost:8000.
- **Content spot check**: verify programs, testimonials, resources reflect latest seed data.
- **Newsletter form**: submit test email; observe success/failure banner and confirm analytics event in browser console (`mailchimp.success` or `mailchimp.failure`).
- **Booking CTA**: click hero/estimator CTA; confirm analytics event `booking.click` appears in console and `storage/logs/analytics.log`.
- **Resource hub**: validate at least one local download works and an external-only resource links out without disabled state.
- **Console errors**: ensure no unhandled errors appear aside from known Lighthouse performance warnings.

## 5. Analytics & Logging Review
- Tail `storage/logs/analytics.log` to confirm structured entries for newsletter and booking interactions.
- Check Plausible dashboard (shared link in `resources/views/layouts/app.blade.php` or `docs/ops/analytics_dashboard.md`) for goal increments.

## 6. Troubleshooting
- **Seeder failure**: consult `docs/todo/phase6_track_a_seed_fix_plan.md`; run migrations individually to isolate faulty table.
- **Mailchimp API errors**: verify endpoint/list ID, network connectivity; review retry logs in analytics channel.
- **Analytics queue missing**: ensure session not regenerated between request/response and layout script block present.

## 7. Sign-off
- Capture command outputs and audit results in project tracker.
- Flag outstanding issues (performance, accessibility, integration errors) for remediation or backlog triage.
