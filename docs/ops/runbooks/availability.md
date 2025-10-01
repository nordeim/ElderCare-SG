# Availability Service Runbook
_Date: 2025-10-01_

## Overview
The availability service fetches upcoming visit slots from the booking provider (mocked in development) and exposes them via `/api/availability`. Front-end modules consume this endpoint to display badge status and emit analytics events. This runbook documents day-to-day operations and incident response procedures.

## Architecture
- **Provider**: `App\Services\Providers\AvailabilityProvider` with default `MockAvailabilityProvider`. Swap to real adapter by binding in `AppServiceProvider`.
- **Service**: `App\Services\AvailabilityService` handles caching, staleness detection, and fallback messaging.
- **Endpoint**: `App\Http\Controllers\AvailabilityController` (`GET /api/availability`, throttled 60/min per IP).
- **Frontend**: `resources/js/modules/availability.js` polls endpoint every 3 minutes and emits `availability.loaded` / `availability.error` events.

## Configuration
| Key | Location | Default | Notes |
| --- | --- | --- | --- |
| `AVAILABILITY_DRIVER` | `.env` | `mock` | Set to `api` when real provider adapter is implemented. |
| `AVAILABILITY_CACHE_TTL` | `.env` | `300` (seconds) | Cache lifetime for successful payloads. |
| `AVAILABILITY_STALE_AFTER` | `.env` | `900` (seconds) | Threshold for marking cached data stale. |
| `AVAILABILITY_FALLBACK_MESSAGE` | `.env` | “We will confirm availability within 24 hours.” | Displayed when provider unavailable. |
| `AVAILABILITY_MOCK_WINDOW_DAYS` | `.env` | `7` | Mock generator range. |
| `AVAILABILITY_MOCK_WEEKLY_SLOTS` | `.env` | `18` | Total slots distributed across window. |

## Regular Operations
- **Daily checks**
  - Review Plausible dashboard for `availability.loaded` vs `availability.error` trends.
  - Confirm cache freshness via `php artisan tinker`:
    ```php
    app(App\Services\AvailabilityService::class)->getAvailability();
    ```
  - Validate QA script items in `docs/qa/scaffold-checklist.md` after deployments.
- **Credential rotation**
  - Once real provider adapter exists, store API keys in `.env` and rotate quarterly.
  - Update `.env.example` after rotation for reference (without secrets).

## Incident Response
1. **Badge shows fallback or empty slots**
   - Check logs: `storage/logs/laravel.log` for warnings `Availability provider error`.
   - Confirm upstream API health; if down, set `AVAILABILITY_DRIVER=mock` temporarily and redeploy.
   - Communicate fallback messaging to marketing/staff.
2. **High `availability.error` rate**
   - Inspect network tab or server logs for status codes (captured by analytics payload).
   - Validate rate limiter not blocking legitimate traffic; adjust limit in `AppServiceProvider` if necessary.
3. **Cache corruption**
   - Clear cache entry: `php artisan cache:forget availability.slots`.
   - Trigger manual refresh: `app(App\Services\AvailabilityService::class)->refresh();`.

## Alerting & Metrics
- Configure Plausible alert when `availability.error` exceeds 10 events in 5 minutes.
- Add server monitor to ensure `/api/availability` responds `<500ms` P95.

## Deployment Checklist
- Confirm `.env` includes availability keys and TTL overrides.
- Run `npm run build` to ensure availability module bundled.
- Execute `composer phpunit` to verify unit and feature tests (`AvailabilityServiceTest`, `AvailabilityEndpointTest`).
- Run `npm run lint:accessibility` and `npm run lighthouse` per QA instructions.

## Contact
- **Primary Owner**: Engineering Team (Jason Tan)
- **Escalation**: #ops-alerts Slack channel
- **Business Stakeholder**: Care Operations Lead (Mei Ling)
