# Phase 6 Track C — External Integrations Hardening Plan
_Date: 2025-10-02_

## Goals
- Improve resilience of newsletter subscription and booking interactions.
- Provide analytics signals for integration success/failure.
- Document configuration expectations for Mailchimp and booking URLs.

## Scope Overview
1. **Mailchimp Service Enhancements**
   - Add retry/backoff logic for transient HTTP failures.
   - Ensure structured logging with correlation IDs.
   - Emit analytics events for success (`mailchimp.success`) and failure (`mailchimp.failure`).
   - Update `.env.example` with Mailchimp keys guidance.
2. **Booking Service Logging**
   - Extend `BookingService::logClick()` to capture request context (referer, user agent).
   - Emit analytics event `booking.click` with context payload.
   - Provide guard for missing configuration.
3. **Routes / Webhooks (Optional)**
   - Evaluate need for Mailchimp webhook route to confirm double opt-in (defer if not required for Phase 6).
4. **Testing**
   - Add feature test covering newsletter signup success & failure states using HTTP fake.
   - Add unit/feature test verifying booking logging call with enriched context.

## File Checklist
- `app/Services/MailchimpService.php`
- `app/Services/BookingService.php`
- `config/services.php`
- `resources/js/modules/analytics.js`
- `resources/js/bootstrap.js` *(confirm dispatch wiring)*
- `resources/views/components/newsletter.blade.php` *(ensure analytics hooks wired)*
- `.env.example`
- `tests/Feature/NewsletterSubscriptionTest.php` (new)
- `tests/Feature/BookingLoggingTest.php` (new or extend existing)

## Command Validation
- `php artisan test --filter=NewsletterSubscriptionTest`
- `php artisan test --filter=BookingLoggingTest`
- `npm run build`

## Risks
- External API rate limiting; use exponential backoff with cap.
- Logging PII; ensure logs avoid storing raw email addresses beyond necessary context (mask if required).

## Success Criteria
- Newsletter subscription gracefully handles HTTP errors with retries and analytics signals.
- Booking clicks log enriched metadata without errors.
- Tests cover success/failure scenarios.
- Documentation updated for environment configuration.
