# Launch Checklist — ElderCare SG
_Date: 2025-10-02_

## 1. Pre-Launch Approvals
- [ ] Product owner approves scope freeze for Phase 6/7 deliverables.
- [ ] Marketing/Ops review latest landing snapshot (`main_landing_page.html`).
- [ ] Analytics/GDPR lead confirms Plausible goals and data retention compliance.

## 2. CI & Automation Gate
- [ ] `qa-ci.yml` workflow green on main branch (Laravel tests, axe audit, Lighthouse CI).
- [ ] `npm run test:playwright:ci` baseline reviewed; no unexpected diffs.
- [ ] Artifacts archived: `storage/app/lighthouse/*`, `storage/app/playwright-report/*`.

## 3. Data & Content Validation
- [ ] `php artisan migrate:fresh --seed` run in staging; data spot-checked (programs, testimonials, resources, FAQs).
- [ ] Resource hub confirms external downloads and local assets work.
- [ ] Newsletter form tested with valid + invalid emails; success/failure copy localized.

## 4. Analytics & Logging Review
- [ ] Tail `storage/logs/analytics.log` for `mailchimp.success`, `mailchimp.failure`, `booking.click` entries.
- [ ] Verify Plausible dashboard goals (`mailchimp-success`, `booking-click`, `resource-download`, `estimator-submit`).
- [ ] Confirm browser console free of unhandled errors on homepage.

## 5. Performance & Accessibility
- [ ] Latest Lighthouse report attached; deviations from budget (<0.75 performance) acknowledged in release notes.
- [ ] Axe CLI output archived; outstanding violations triaged.
- [ ] Record manual assistive technology scan (screen reader or keyboard-only pass).

## 6. Infrastructure & Backups
- [ ] Database backup captured prior to deploy (timestamp/link recorded).
- [ ] Feature flags documented for assessment, tour, estimator components with toggle procedure.
- [ ] CDN/cache invalidation plan prepared (hashed asset headers in place).

## 7. Rollback Plan
- [ ] Git tag created for release candidate (e.g., `v0.7.0-rc1`).
- [ ] `artisan down`/`up` procedure rehearsed; maintenance page copy verified.
- [ ] Rollback steps documented: restore DB backup, redeploy previous tag, invalidate CDN caches.

## 8. Post-Launch Monitoring
- [ ] Schedule first-week analytics review meeting (Ops + Marketing).
- [ ] Enable temporary log level `info` for analytics channel to monitor spikes.
- [ ] Prepare incident contact list (engineering, ops, marketing) with escalation SLAs.

## 9. Sign-off
- [ ] Product owner sign-off
- [ ] Engineering lead sign-off
- [ ] Operations lead sign-off
