# Track D Regression — Booking Logging Test Failure Plan
_Date: 2025-10-02_

## 1. Issue Summary
- `Tests\Feature\BookingLoggingTest::test_booking_click_logs_context_and_dispatches_event` now returns HTTP 500 instead of 204 after analytics logging updates.
- Likely root causes:
  - Analytics log channel misconfiguration (formatter class resolution, file permissions).
  - Test setup still uses `Log::spy()` on default channel while production code writes to `Log::channel('analytics')`.

## 2. Investigation Steps
1. Inspect PHPUnit exception details (via `storage/logs/laravel.log` or `analytics.log`) to confirm underlying exception class/message.
2. Validate analytics channel wiring by manually invoking `Log::channel('analytics')->info('ping')` in tinker or a dedicated route/test harness.
3. Review `BookingLoggingTest` to ensure logging spies target the correct channel.

## 3. Remediation Strategy
1. **Channel Spy Adjustment**
   - Update test to spy on the analytics channel (`Log::channel('analytics')->spy()`) or leverage `Log::swap` to capture messages.
2. **Formatter Autoload Guard**
   - Confirm `App\Logging\Formatters\AnalyticsFormatter` autoloads; add unit test or run composer dump if necessary.
   - Handle potential missing shared dashboard config fallback to avoid null issues.
3. **Error Handling**
   - Ensure `BookingService::logClick()` catches logging exceptions (optional) or let them bubble during test to detect misconfig quickly.
4. **Validation**
   - Re-run `php artisan test --filter=BookingLoggingTest`.
   - Re-run whole suite `composer phpunit`.
   - Confirm `storage/logs/analytics.log` captures events without errors.

## 4. Success Criteria
- Booking logging test returns 204 and verifies analytics event as expected.
- No runtime errors when logging to analytics channel in tests or runtime environments.
- Documentation remains accurate (no additional env requirements introduced).
