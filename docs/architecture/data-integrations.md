# Data Integration Brief — ElderCare SG Guided Experiences
_Date: 2025-10-01_

## Overview
This brief inventories the data sources, access patterns, and fallback strategies required across Guided Assessment, Availability surfacing, Virtual Tour storytelling, and Decision Support utilities. It supports Phase 0 deliverables and informs downstream engineering tasks for Phases 1–4 in `docs/plans/master_todo_roadmap.md`.

## 1. Booking Availability Snapshot
- **Source**: Primary booking provider API (Calendly placeholder or internal scheduling service).
- **Authentication**: API key via environment variable `BOOKING_API_TOKEN` (store in `.env`, `.env.testing` using Laravel config).
- **Endpoints**:
  - `GET /v1/locations/:id/availability?range=7d` — returns slot summaries for 7-day window.
  - `GET /v1/locations/:id/status` — health endpoint for uptime monitoring.
- **Refresh cadence**: Every 15 minutes via scheduled job (future Phase 2); cached for 20 minutes using Redis tagged cache `availability`.
- **Fallback**: If API unreachable, display “We’ll confirm availability within 24 hours” message and log warning to `availability` channel.
- **Open questions**: Confirm rate limits, timezone handling, and appointment types needed.

## 2. Programs & Testimonials Content
- **Source**: Local MariaDB tables `programs`, `testimonials` (seeded via Laravel seeders).
- **Access**: Eloquent scopes `Program::active()` and `Testimonial::active()` already used in `App\Http\Controllers\HomeController`.
- **Personalization Tags**: Add boolean/JSON columns (`segments`) to link programs/testimonials to assessment segments.
- **Admin workflow**: Phase 6 will define CMS or manual process; interim updates via seeded data.
- **Caching**: Future optimization—cache active collections for 30 minutes using `cache.remember`.

## 3. Assessment Logging Endpoint
- **Route**: `POST /assessment-insights` (see `routes/web.php`).
- **Controller**: `App\Http\Controllers\AssessmentController` validates payload using `AssessmentSubmissionRequest` and delegates to `App\Services\AssessmentService::logOutcome`.
- **Storage**: Currently logging to default log channel; consider dedicated channel `assessment` in `config/logging.php` for structured analytics.
- **Payload**:
  ```json
  {
      "segment_key": "active_day",
      "answers": {"mobility": "independent", ...},
      "meta": {"source": "hero_modal"}
  }
  ```
- **Analytics Integration**: `resources/js/modules/assessment.js` emits browser events consumed by Plausible (client-side). Future server-side pipeline could push to queue/warehouse.

## 4. Analytics & Telemetry
- **Tooling**: Plausible via script snippet (configured in Blade layout). Event namespace `assessment.*` defined in localization config.
- **Event Catalog** (initial): `assessment.open`, `assessment.start`, `assessment.step_submit`, `assessment.complete`, `assessment.submitted`, `assessment.submit_error`, `assessment.skip`, `assessment.restart`.
- **Dashboard**: Create Plausible custom events dashboard; document tracked goals in upcoming `docs/analytics.md`.
- **Fallback**: If Plausible script missing, events fail silently but remain logged via Laravel logger when backend endpoint invoked.

## 5. Availability of Media Assets
- **Virtual Tour**: High-res MP4/WebM + VTT captions stored in `public/media/tour/`. Provide JSON manifest for hotspots consumed by future `tour.js` module.
- **Transcripts**: Markdown or HTML files stored in `resources/markdown/transcripts/` with localization variants.
- **Access control**: Public CDN accessible; ensure caching headers configured via `public/.htaccess` or CDN rules.

## 6. Cost Estimator & FAQ Data
- **Cost Estimator**: Convert finance spreadsheet to structured JSON stored in `database/data/estimator.json` (Phase 4). Migration to persist tariffs keyed by segment/transport add-ons.
- **FAQs**: Use database table `faqs` with fields `question`, `answer`, `category`, `persona`, `language`. Provide seeder and admin editing guidance.
- **Downloads**: Caregiver PDFs stored in `storage/app/public/resources`; ensure symbolic link via `php artisan storage:link`.

## 7. Security & Compliance Considerations
- Ensure `.env.testing` mirrors production-sensitive keys with non-production tokens.
- Log redaction: `AssessmentService::logOutcome` should avoid storing personally identifiable information; only segment and aggregated answers.
- PDPA compliance: Document consent for analytics events; surface notice on assessment modal start.

## 8. Next Steps
- Confirm API credentials and rate limits with booking provider (Phase 0 follow-up).
- Extend database schema with segment tags for programs/testimonials in Phase 1.1.
- Add monitoring for availability job (Phase 2) and structured logging channel `assessment` / `availability`.
- Revise `docs/plans/phase0_alignment_research_brief.md` checklist with links to this brief.
