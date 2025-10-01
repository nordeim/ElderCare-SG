# Analytics Event Reference — ElderCare SG
_Date: 2025-10-01_

## Overview
This document tracks front-end and back-end analytics events introduced during Phase 1 and Phase 2. Events are emitted via `window.eldercareAnalytics` (front-end) and server-side logging for Plausible integration. All events must include non-PII payloads and follow PDPA compliance requirements.

## Event Catalogue

### Assessment Funnel
- **`assessment.open`** — Triggered when the guided assessment modal opens.
  - Payload: `{ segmentKey: string|null }`
- **`assessment.start`** — User begins answering questions.
  - Payload: `{ step: string }`
- **`assessment.step_submit`** — Question submitted.
  - Payload: `{ step: string, answer: any }`
- **`assessment.complete`** — Assessment finished with persona mapping.
  - Payload: `{ segmentKey: string, summary: object, answers: object }`
- **`assessment.close` / `assessment.restart`** — User exits or restarts flow.

Events dispatch via `assessment.js` emitter and relay to Plausible automatically.

### Estimator
- **`estimator.open`** — Cost estimator section enters viewport.
  - Payload: `{ source: 'auto' | 'cta' }`
- **`estimator.update`** — User changes estimator inputs.
  - Payload: `{ daysPerWeek: number, transport: boolean, addOns: string[], subsidyKey: string }`
- **`estimator.submit`** — Estimator form submitted.
  - Payload: `{ daysPerWeek: number, transport: boolean, addOns: string[], subsidyKey: string }`
- **`estimator.submit_error`** — Submission failed.
  - Payload: `{ daysPerWeek: number, transport: boolean, addOns: string[], subsidyKey: string, error: string }`
  - Use to chart demand trends and detect stale data ratios.

### FAQ Accordion
- **`faq.expand`** — FAQ accordion item opened.
  - Payload: `{ faqId: string }`
  - Emitted from `faqAccordion.toggle()` after expanding.

### Resource Hub
- **`resource.download`** — Resource hub CTA clicked.
  - Payload: `{ slug: string }`
  - Fired by analytics module when `data-analytics-id="resource-download"` elements are activated.

### Locale Switching
- **`locale.changed`** — User chose an alternate language.
  - Payload: `{ locale: 'en'|'zh' }`
  - Track adoption to prioritize content workflows.

### Virtual Tour
- **`tour.open`** — Virtual tour modal opened from hero section.
  - Payload: `{ hotspotId: string|null, totalHotspots: number }`
- **`tour.hotspot`** — User focused or activated a hotspot.
  - Payload: `{ hotspotId: string, totalVisited: number, totalHotspots: number }`
- **`tour.complete`** — User explored all hotspots in the session.
  - Payload: `{ totalHotspots: number }`
- **Goals**: Track engagement funnel (open → hotspots → completion) and prioritize hotspot content updates. Configure Plausible custom goals for each event name.

### Newsletter
- **`newsletter.submit`** — Fired from `NewsletterController` upon POST.
  - Payload: `{ status: 'success'|'fallback', emailDomain: string }`

## Implementation Notes
- Front-end events: ensure `resources/js/modules/analytics.js` loads before modules requiring analytics.
- Server-side events: log via dedicated Laravel channels; redact email/user data before emitting.
- QA should verify events via browser dev tools (CustomEvent listener) and Plausible debug view.

## Dashboards & Metrics
- **Plausible Goals**
  - `assessment.complete` → Conversion to persona recommendations.
  - `availability.loaded` → Node-time series to monitor slot health.
  - `locale.changed` → Localization adoption rate.
  - `tour.open`, `tour.hotspot`, `tour.complete` → Tour engagement pipeline.
- **Alerting**
  - Set alerts for `availability.error` spikes and `assessment.complete` drops >30% week-over-week.

## Next Steps
- Automate Plausible goal creation via API script (Phase 3).
- Add backend job to reconcile availability API latency with provider SLA.
