# Phase 6 Track D — Analytics & Logging Enhancements Plan
_Date: 2025-10-02_

## Objectives
- Strengthen analytics instrumentation for key caregiver actions (newsletter signup, booking clicks, resource downloads, estimator usage).
- Ensure client-side console errors and backend failures are observable.
- Configure dashboard/reporting touchpoints for ops and marketing review.

## Scope
1. **Plausible Analytics Goals**
   - Define goals for `mailchimp.success`, `booking.click`, `resource.download`, `estimator.submit`.
   - Update `config/analytics.php` or environment variables to surface Plausible domain + script configuration.
   - Document goal setup steps for marketing operations.

2. **Client-Side Telemetry**
   - Extend `resources/js/modules/analytics.js` to capture window `error`/`unhandledrejection` events and emit `app.error` with stack/context.
   - Add throttling to avoid flooding analytics pipelines.
   - Ensure analytics queue respects duplicate suppression per session.

3. **Server-Side Logging**
   - Introduce `app/Logging/AnalyticsLogFormatter.php` (if needed) to format integration logs consistently.
   - Add dedicated channel/tag for analytics events in `config/logging.php`.
   - Ensure `MailchimpService` and `BookingService` adopt structured context (already partially complete) and align with new formatter.

4. **Dashboard / Reporting Readiness**
   - Update `docs/ops/admin_readiness.md` with Plausible goal URLs and instructions for weekly reviews.
   - Create quick reference dashboard link (Plausible share URL or placeholder) in `docs/ops/analytics_dashboard.md`.

5. **Validation & QA**
   - Run `php artisan test --filter=NewsletterSubscriptionTest --filter=BookingLoggingTest` (already covered; rerun after changes).
   - Execute `npm run lint:accessibility` and `npm run lighthouse` to ensure no frontend regressions.
   - Manually trigger console error to confirm analytics capture without breaking UX (document steps).

## File Checklist
- `config/analytics.php`
- `.env.example`
- `resources/js/modules/analytics.js`
- `resources/js/bootstrap.js` (if event listeners required)
- `app/Services/MailchimpService.php` (log alignment)
- `app/Services/BookingService.php`
- `config/logging.php`
- `docs/ops/admin_readiness.md`
- `docs/ops/analytics_dashboard.md` (new)

## Risks
- Over-reporting due to repeated session flashes; mitigate with deduping in JS.
- Plausible goal misconfiguration leading to incomplete data; document steps thoroughly.
- Console error listener may surface third-party noise; implement allowlist/ignore patterns.

## Success Criteria
- Analytics module emits goals for targeted interactions and captures console errors with metadata.
- Logging configuration provides clear audit trail for integration events.
- Documentation enables non-engineering teams to review analytics without developer intervention.
- Validation commands run cleanly post-update.
