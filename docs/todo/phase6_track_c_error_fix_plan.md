# Phase 6 Track C — Error Resolution Plan
_Date: 2025-10-02_

## 1. Context
Following the latest `php artisan migrate:fresh --seed` and `composer phpunit` runs, two issues surfaced:
- PHPUnit could not load `tests/Feature/BookingLoggingTest.php` because of malformed chained call syntax introduced during test authoring.
- Visiting the home page triggers `League\Flysystem\Filesystem::has()` with a null `$location` when seeded `Resource` entries have `file_path = null` (external-only assets). The current Blade view unconditionally probes the storage disk.

## 2. Objectives
- Restore PHPUnit suite stability.
- Ensure `resource-hub` component gracefully handles resources without local files while still supporting download CTAs for existing assets.

## 3. Remediation Steps
1. **Test Syntax Repair**
   - Remove stray diff markers and chain `withHeaders()` correctly in `tests/Feature/BookingLoggingTest.php`.
   - Re-run targeted test (`php artisan test --filter=BookingLoggingTest`).
2. **Resource CTA Logic**
   - Update `resources/views/components/resource-hub.blade.php` to:
     - Check `file_path` before calling `Storage::disk('public')->exists()`.
     - Prefer `Storage::url($file_path)` when available; fall back to `$resource->external_url`.
     - Disable CTA when neither source is available.
   - Ensure analytics `onclick` only fires when link is actionable.
3. **Validation**
   - Re-run `php artisan test --filter=BookingLoggingTest` and `php artisan test --filter=ResourceSeeder` *(if applicable)*.
   - Execute full suite via `composer phpunit`.
   - Manually hit `/` to confirm resource cards render without exception, verifying CTA behavior for both local and external assets.

## 4. Success Criteria
- PHPUnit suite loads without syntax errors; new tests pass.
- Resource hub renders all seeded entries; external-only resources display functional CTA linking to `external_url`.
- No filesystem null reference errors observed in logs.
