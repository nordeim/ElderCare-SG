# Understanding Project Requirements

## Executive Summary
- **Project Identity**: `Project Requirements Document.md` frames ElderCare SG as a warm, credible portal connecting Singapore families with compassionate daycare services, reinforced by the mission language in `README.md`.
- **Strategic Goals**: Conversion uplift (30% booking increase), bounce rate <40%, Lighthouse >90, and deep engagement metrics drive the definition of success per `Project Requirements Document.md`.
- **Experience Promise**: Blend emotional reassurance with clinical trust, ensuring accessibility, responsiveness, and measurable user journeys throughout the Laravel/Tailwind stack.

## Audience & Empathy Map
- **Adult Children (30–55)**: Seek reliable daytime care, assurance of safety, transparent pricing, and swift booking pathways while juggling careers and eldercare responsibilities.
- **Domestic Caregivers**: Need program clarity, transportation info, dietary accommodations, and ongoing communication touchpoints to coordinate daily routines.
- **Healthcare Professionals**: Require accreditation visibility, care philosophy articulation, and straightforward referral workflows to recommend trusted partners.

## Experience Blueprint
### Landing Page Information Architecture
- **Hero & Quick Actions**: Autoplay hero video with accessible gradient overlay, contextual copy, dual CTAs (`Book Visit`, `Watch Tour`), real-time availability snapshot, and multilingual toggle.
- **Guided Needs Assessment**: Alpine.js questionnaire capturing care priorities; personalizes recommended programs, testimonials, and CTA language based on responses.
- **Programs & Daily Rhythm**: Timeline rendering of a typical day, hover-enabled cards for “Day Programs,” “Wellness,” and “Family Support,” with accessible tooltips and alternating palettes.
- **Hybrid Media Showcase**: Lazy-loaded 360° virtual tour, staff spotlight carousel, transcripted videos, and badge overlays highlighting safety, medical supervision, and community engagement.
- **Care Outcomes Dashboard**: Data cards for satisfaction scores, staff-to-senior ratio, certifications, and years of service using prefers-reduced-motion aware micro-interactions.
- **Community & Resource Hub**: Educational articles, downloadable caregiver guides, FAQ drawers with search, and newsletter subscription entry point.
- **Conversion Footer**: Consolidated contact details, virtual tour CTA, trust badges, and regulatory statements aligned with Singapore eldercare compliance.

## Design System Enhancements
- **Color Tokens**: Extend Tailwind config with semantic tokens (`--color-trust`, `--color-warmth`, etc.) linked to palette defined in `Project Requirements Document.md`.
- **Typography Scale**: Implement fluid clamp utilities for Playfair Display headings and Inter body text with `font-display: swap`, preserving readability across breakpoints.
- **Motion Guidelines**: Centralize easing curves, durations, and prefers-reduced-motion fallbacks in utility classes; ensure all interactive elements degrade gracefully to static states.
- **Component Library**: Document shadcn-inspired Blade components (hero, cards, carousel, steps, CTA modal) in `/resources/views/components`, each with accessibility notes and variant props.

## Feature Specification
- **Personalized Booking Flow**: Prefetch `/booking`, capture visitor intent via modal steps, integrate tracking events (click, completion, drop-off) for analytics observability.
- **Virtual Tour Layer**: Provide video with hotspots, image fallback for low bandwidth, captions, transcripts, and keyboard navigation for overlays.
- **Testimonials System**: Blade-powered carousel and full-length testimonial page with schema.org `Review` markup, CMS-ready data source for dynamic content updates.
- **Decision Support Utilities**: Cost estimator widget, interactive FAQ accordion, downloadable checklists, and contextual prompts nudging newsletter signup or scheduling.
- **Newsletter Integration**: Laravel controller bridging to Mailchimp API with validation, double opt-in, and success/error toasts while logging events for future campaigns.

## Technical Implementation Plan
- **Phase 1 – Foundations**: Validate Laravel architecture, configure Tailwind theme tokens, scaffold base layout in `resources/views/layouts`, stand up Docker-based environment per `README.md`.
- **Phase 2 – Content Modules**: Build modular Blade components for hero, assessments, program cards, testimonial carousel, trust hub, and CTA sections with slot-based customization.
- **Phase 3 – Interactive Layer**: Introduce Alpine.js stores for navigation, assessments, lightboxes, and analytics dispatch; ensure keyboard support and focus management.
- **Phase 4 – Data & CMS Hooks**: Seed MariaDB tables for programs/testimonials, craft services for Mailchimp and scheduler integrations, expose admin-friendly content management pathways.
- **Phase 5 – Quality Automation**: Embed Lighthouse CI, axe-core, and browser matrix tests into GitHub Actions; document manual QA checklist for accessibility, performance, and cross-device validation.

## Roadmap & Milestones
- **Sprint 0 (Week 1)**: Discovery validation, sitemap finalization, Tailwind tokenization, base layout scaffolding.
- **Sprint 1 (Weeks 2–3)**: Landing page core sections, responsive breakpoints, hero + program modules, initial analytics instrumentation.
- **Sprint 2 (Weeks 4–5)**: Interactive questionnaire, testimonials system, virtual tour experience, decision-support tools.
- **Sprint 3 (Weeks 6–7)**: Newsletter integration, booking flow refinement, CMS seeding, accessibility and performance hardening.
- **Sprint 4 (Week 8)**: Comprehensive QA, stakeholder review, localization polish, launch readiness checklist completion.

## Risk & Mitigation
- **Content Readiness**: Mitigate by parallel copywriting track with subject-matter experts, using placeholder content flagged via TODOs in Blade partials.
- **Media Licensing**: Enforce asset audit workflow ensuring all imagery/video carry documented licenses before deployment.
- **Performance Regressions**: Guard with automated Lighthouse thresholds in CI and Tailwind purge configuration to eliminate unused styles.
- **Accessibility Compliance**: Schedule iterative audits, maintain aria-label standards, and include screen reader scripts in QA checklist from `Project Requirements Document.md`.
- **Integration Dependencies**: Mock Mailchimp/scheduler endpoints locally, adopt feature flags so external integrations do not block core UX.

## Appendices
- **Source Documents**: `Project Requirements Document.md`, `README.md`.
- **Supporting References**: Planned `docs/design-system.md`, `docs/architecture.md`, `docs/accessibility.md` for future alignment once authored.
