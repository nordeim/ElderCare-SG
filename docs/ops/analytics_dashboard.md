# ElderCare SG Analytics Dashboard Guide
_Date: 2025-10-02_

## 1. Overview
This guide summarizes where to review high-level engagement metrics after Phase 6 instrumentation updates.

## 2. Accessing Plausible
- Dashboard URL: `{{ config('analytics.plausible.shared_dashboard') ?? 'https://plausible.io/eldercare-sg' }}` *(replace with actual share link)*.
- Required permissions: Marketing partner owns Plausible workspace; provide view-only invite to operations leads.

## 3. Key Goals
| Event | Goal ID | Description |
|---|---|---|
| Newsletter Confirmed | `mailchimp-success` | Triggered on successful newsletter subscription (`mailchimp.success`). |
| Newsletter Failure | `mailchimp-failure` | Tracks API errors or validation issues to monitor friction. |
| Booking CTA Click | `booking-click` | Fires when booking buttons are engaged across hero, estimator, and prompts. |
| Resource Download | `resource-download` | Captures downloads or external resource access from the resource hub. |
| Estimator Submission | `estimator-submit` | Records projected cost estimates to understand planning intent. |

## 4. Weekly Review Checklist
- Filter Plausible dashboard by `mailchimp-failure` to watch for spikes (>5% of successes).
- Segment `booking-click` by referrer to identify top acquisition paths.
- Compare `resource-download` counts with new content releases to measure adoption.
- Export CSV monthly for historical tracking (`Export → CSV`).

## 5. Alerting & Follow-Up
- If `mailchimp-failure` exceeds 10 events/day, escalate to Engineering On-Call with `storage/logs/analytics.log` sample lines.
- Log anomalies in the shared Phase 6 QA tracker with timestamp and hypothesized cause.

## 6. Backlog Enhancements
- Integrate Plausible API with Slack notifications for weekly summaries (target date: 2025-11-15).
- Evaluate connecting Plausible goals to CRM to map conversions (Phase 7 consideration).
