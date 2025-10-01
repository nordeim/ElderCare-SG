# ElderCare SG Master ToDo Roadmap
_Date: 2025-10-01_

## Context & Validated Gaps
- **Guided Needs Assessment Missing**: `resources/views/home.blade.php` renders static sections without any Alpine-driven questionnaire or CTA personalization logic described in `Understanding_Project_Requirements.md`.
- **Hero Enhancements Incomplete**: `resources/views/components/hero.blade.php` provides dual CTAs and autoplay video, but lacks real-time availability data and a multilingual toggle noted in `Codebase_Analysis_and_Alignment_Assessment_Report.md`.
- **Virtual Tour & Media Storytelling Gap**: `resources/views/home.blade.php` hosts a static placeholder in the `#tour` section; no interactive lightbox, hotspot logic, or transcripts exist.
- **Decision Support Utilities Absent**: No cost estimator, FAQ accordion, or downloadable guides are present within `resources/views`.
- **Design System Enhancements Pending**: `tailwind.config.js` defines palette values but omits semantic tokens and fluid typography clamps. Blade components lack usage documentation.
- **QA Automation Not Wired**: `package.json` exposes `lint:accessibility` and `lighthouse` scripts, yet no GitHub Actions workflow orchestrates them.

## Guiding Principles
- **User-Centric Functionality First**: Deliver interactive flows (assessment, availability, tour, support tools) before backend optimizations.
- **Accessibility & Performance**: Preserve WCAG 2.1 AA targets and maintain Lighthouse >90 during every phase.
- **Composable Architecture**: Implement new logic via Blade components, Alpine stores, and Services, aligning with `CLAUDE.md` guidance.
- **Instrumentation & Feedback**: Introduce analytics hooks and logging that illuminate user journeys and conversion funnels.
- **Documentation & Handoff**: Capture component usage, content dependencies, and QA steps for future contributors.

## Phase 0 – Alignment & Research (Week 0)
- **Objectives**
  - Confirm personas, success metrics, and content inventory required for interactive experiences.
  - Define analytics and booking data sources for availability snapshots.
- **Key Tasks**
  - **Stakeholder Interviews**: Validate questionnaire topics, decision-support needs, and multilingual priorities.
  - **Copy & Asset Audit**: Inventory video, transcript, FAQ, and pricing assets; flag gaps for content team.
  - **Data Source Mapping**: Identify APIs or CMS endpoints for availability, pricing, and testimonials.
- **Success Criteria**
  - Approved UX brief for assessment, tour, and support utilities.
  - Documented data availability plan for real-time content.
  - Updated backlog issues representing each feature track.

## Phase 1 – Guided Needs Assessment & CTA Personalization (Weeks 1–2)
- **Objectives**
  - Launch an Alpine.js-powered assessment that tailors program recommendations and CTA language.
  - Capture analytics events across the assessment funnel.
- **Implementation Steps**
  - **Information Architecture**: Design question flow and scoring matrix; document in `docs/ux/assessment.md` (new file).
  - **Component Build**: Create `resources/views/components/assessment.blade.php` with modular steps, progress indicator, and ARIA support.
  - **State Management**: Implement Alpine store in `resources/js/modules/assessment.js` handling answers, validation, and computed recommendations.
  - **Personalized Rendering**: Update `home.blade.php` to inject recommended programs/testimonials and adjust CTA labels via Blade conditionals.
  - **Service Layer**: Extend `App/Services/BookingService` or new `RecommendationService` for CTA copy variants and logging.
  - **Analytics Hooks**: Fire events (`assessment.start`, `assessment.complete`, `assessment.exit`) via a dedicated Alpine dispatcher.
- **Success Criteria**
  - Assessment UX accessible (keyboard, screen reader, reduced motion).
  - Results alter visible content and `Booking` CTA text based on personas.
  - Analytics events verified in staging logs.

## Phase 2 – Hero Enhancements & Availability UX (Weeks 2–3)
- **Objectives**
  - Surface real-time availability and empower language switching in the hero/nav.
- **Implementation Steps**
  - **Availability Service**: Implement `App/Services/AvailabilityService` integrating the booking provider (API or mocked JSON) with caching.
  - **UI Integration**: Update `hero.blade.php` to display availability badges and fallback messaging when data is stale.
  - **Multilingual Toggle**: Introduce locale switcher (EN/中文) in `partials/nav.blade.php` backed by Laravel localization files.
  - **Content Internationalization**: Create `lang/en/*.php` and `lang/zh/*.php` resources for hero copy and nav labels; ensure `app/Http/Middleware/SetLocale` handles request locale.
  - **Accessibility Review**: Confirm toggle uses ARIA attributes and respects `prefers-reduced-motion`.
- **Success Criteria**
  - Availability badge updates at configurable intervals and logs failures.
  - Language toggle persists user preference (session/cookie) and refreshes translations without full-page reload issues.
- **Status Update (2025-10-01)**
  - Track A (Data Layer): ✅ Implemented mock provider, caching, rate limiting, and runbook. Next: swap to live adapter when Ops delivers credentials.
  - Track B (Hero UI): ✅ Badge renders with aria-live, analytics, and styling. Follow-up: consider exponential backoff for polling and run manual stale-data QA.
  - Track C (Localization): ✅ Locale dropdown, middleware persistence, hero translations. Follow-up: add `LocaleSwitchTest` feature coverage and complete screen-reader QA notes.
  - Track D (Analytics/Observability): ✅ `availability.*` + `locale.changed` events emit via `resources/js/modules/analytics.js`; documented in `docs/analytics.md`.
  - Track E (Docs/Ops): ✅ Availability runbook authored. Follow-ups assigned to Ops (Mei Ling) to schedule booking API discovery and stakeholder workshop by 2025-10-08; Engineering (Jason Tan) to backfill owners/dates in `docs/plans/phase0_alignment_research_brief.md`.

-## Phase 3 – Immersive Virtual Tour & Media Storytelling (Weeks 3–4)
- **Objectives**
  - Replace static tour placeholder with interactive media experience featuring transcripts and staff spotlights.
- **Implementation Steps**
  - **Media Player Component**: ✅ `resources/views/components/virtual-tour.blade.php` delivers modal lightbox with video/image support, focus management, and transcript links.
  - **Hotspot Overlay**: ✅ `resources/js/modules/tour.js` consumes `resources/data/tour_hotspots.json`, supports keyboard navigation, and emits analytics events.
  - **Staff Carousel**: ✅ `resources/views/components/staff-carousel.blade.php` renders featured staff seeded via `StaffSeeder` with alt text and hotspot tags.
  - **Accessibility Assets**: ✅ Transcript stored at `/public/media/transcripts/tour-main.md`; captions wired for video hotspot. Alt text provided in data layer.
  - **Analytics**: ✅ `tour.open`, `tour.hotspot`, `tour.complete` documented in `docs/analytics.md` and dispatched through Alpine store emitter.
- **Success Criteria**
  - Tour meets accessibility (captions, transcripts, fallback image). *(Manual QA scheduled — see checklist updates.)*
  - Staff carousel responsive and screen-reader friendly.
  - Performance budget maintained (<2.5s LCP on mobile per Lighthouse).
- **Status Update (2025-10-01)**
  - ✅ All Phase 3 workstreams implemented. Pending manual QA on modal accessibility and performance retest after deploying rich media. Coordinate with Ops to validate final media assets (captions, translations) before launch.

## Phase 4 – Decision Support Utilities & Community Resources (Weeks 4–5)
- **Objectives**
  - Deliver tools helping families evaluate fit: cost estimator, FAQ accordion, resource downloads, newsletter prompts.
- **Implementation Steps**
  - **Cost Estimator**: ✅ `components/cost-estimator.blade.php` and `resources/js/modules/cost-estimator.js` live with analytics + UX doc (`docs/ux/cost_estimator.md`). Follow-up: consider moving pricing config to service.
  - **FAQ Accordion**: ✅ `components/faq.blade.php` ships with search, ARIA wiring, schema markup, and events `faq.expand`.
  - **Download Hub**: ✅ `components/resource-hub.blade.php` renders seeded guides; placeholder PDFs added under `storage/app/public/resources/` with `storage:link` enabled.
  - **Contextual Prompts**: ✅ `components/assessment-prompts.blade.php` tied to assessment store; emits `assessment.prompt_show` / `assessment.prompt_click` and drives estimator/resources CTAs.
  - **Schema Markup**: ✅ Included in FAQ component; Rich Results validation pending QA.
  - **Performance Remediation**: In progress — hero lazy loading + dynamic imports landed, but Lighthouse score at 0.79; remaining layout/caching tasks tracked in `docs/notes/performance-2025-10-01.md`.
- **Success Criteria**
  - Tools validated for accuracy (QA scenarios) and accessibility. *(QA checklist updated — execution pending)*
  - Structured data passes Google Rich Results test. *(To run after QA)*
  - Newsletter conversions attributable to prompts tracked via analytics. *(Need follow-up dashboards)*

## Phase 5 – Design System & Component Documentation (Week 6)
- **Objectives**
  - Align Tailwind theme with semantic tokens and document component usage for maintainability.
- **Implementation Steps**
  - **Semantic Tokens**: Refactor `tailwind.config.js` to expose `--color-trust`, `--color-warmth`, etc., and map to CSS variables (`resources/css/app.css`).
  - **Fluid Typography**: Introduce `clamp()` utilities for headings/body text via Tailwind plugin or custom utilities.
  - **Component Catalog**: Author `docs/components.md` describing props, slots, accessibility notes, and example usage (hero, assessment, tour, FAQ, estimator).
  - **Storybook Alternative**: Consider Blade playground route (`/ui-kit`) restricted to local env for rapid QA.
- **Success Criteria**
  - Design tokens used consistently across CSS, enabling quick theme adjustments.
  - Documentation reviewed by design leads and referenced in PR templates.

## Phase 6 – Data & Integration Hardening (Weeks 6–7)
- **Objectives**
  - Ensure dynamic content sources and external integrations are resilient and observable.
- **Implementation Steps**
  - **Database Seeding**: Expand seeders for programs, testimonials, staff, FAQs, resources with realistic data.
  - **Admin Readiness**: Draft backlog item for CMS/editor interface; interim solution via Nova/Filament optional.
  - **External Integrations**: Harden Mailchimp error handling, booking logging (`BookingService::logClick`), and availability fallbacks.
  - **Analytics Dashboard**: Configure Plausible or alternative with custom goals for assessment completion, tour engagement, estimator usage.
- **Success Criteria**
  - Integration failures surfaced via logs/monitoring dashboards.
  - Stakeholders can update core content without code deploys (interim process documented).

## Phase 7 – QA Automation & Launch Readiness (Week 8)
- **Objectives**
  - Automate accessibility and performance checks; finalize launch checklist.
- **Implementation Steps**
  - **GitHub Actions**: Add workflow running `npm run lint:accessibility`, `npm run lighthouse`, and Laravel tests on PRs.
  - **Visual Regression**: Integrate Playwright or Percy snapshot suite for hero, assessment, tour, estimator views.
  - **Manual QA Script**: Update `docs/qa/scaffold-checklist.md` to include new flows with step-by-step test cases.
  - **Performance Budgets**: Configure Lighthouse CI thresholds; enforce budgets in `lighthouserc.json`.
  - **Launch Checklist**: Compile go-live tasks (content freeze, analytics verification, backup strategy) in `docs/qa/launch-checklist.md`.
- **Success Criteria**
  - CI passes for all PRs and blocks regressions.
  - Lighthouse scores ≥90 mobile/desktop; axe CLI free of critical issues.
  - Launch checklist signed off by stakeholders.

## Cross-Phase Enablers
- **Content Collaboration**: Maintain shared Kanban with content team for transcripts, translations, FAQ answers, pricing tables.
- **Analytics & Privacy**: Document data collection in `docs/analytics.md`, ensure compliance with PDPA (consent prompts where needed).
- **Observability**: Set up Log channels for booking and assessment flows; plan future integration with monitoring stack.
- **Risk Mitigation**: Establish feature flags for assessment, tour, estimator to allow staged rollouts.

## Backlog & Future Enhancements
- **Scheduler Integration**: Deep-link to booking provider with pre-filled assessment data.
- **Caregiver Portal**: Explore authenticated area for ongoing communication as a Phase 8 initiative.
- **Localization Expansion**: Add Malay and Tamil translations after Mandarin launch proves stable.
- **CMS Selection**: Evaluate Laravel Nova vs. custom admin for long-term content governance.

## Execution Governance
- **Cadence**: Weekly demos showcasing phase progress; retrospective at the end of each major phase.
- **Definition of Done**: Feature shipped, accessibility reviewed, analytics verified, documentation updated.
- **Tracking**: Convert each bullet into GitHub issues/epics tagged by phase; reference this roadmap in project README.
