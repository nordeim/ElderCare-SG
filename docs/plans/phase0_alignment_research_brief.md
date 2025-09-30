# Phase 0 Alignment & Research Brief
_Date: 2025-10-01_

## 1. Purpose & Scope
- **Align** stakeholders on interactive experience goals before Phase 1 feature delivery.
- **Clarify** data, content, and analytics requirements supporting personalization, availability, and decision-support tools.
- **De-risk** downstream phases by surfacing assumptions, dependencies, and open questions early.

## 2. Primary Personas & Journey Anchors
- **Adult Children (30–55)**
  - Goals: Reliable daytime care, transparent pricing, quick booking confirmations.
  - Pain Points: Time scarcity, uncertainty about medical oversight.
  - Success Signals: Personalized program recommendations, assurance of safety credentials, smooth booking.
- **Domestic Caregivers**
  - Goals: Daily schedules, dietary accommodations, transport logistics.
  - Pain Points: Communication gaps, lack of flexible drop-off windows.
  - Success Signals: Resource hub, FAQ coverage, post-visit follow-up.
- **Healthcare Professionals**
  - Goals: Accreditation visibility, referral workflows, care outcome reporting.
  - Pain Points: Limited insight into program differentiation, data handoff friction.
  - Success Signals: Outcome dashboard, referral contact path, downloadable briefs.

## 3. Stakeholder Discovery Checklist
- **Assessment Flow**
  - Confirm priority questions (mobility, cognition, medical needs, schedule).
  - Define scoring logic mapping to `Program` categories and CTA variants.
  - Capture language tone (reassuring vs. clinical) for persona-specific copy.
- **Virtual Tour Content**
  - Inventory available video/360 assets; identify missing rooms or experiences.
  - Determine narration languages, caption standards, and transcript ownership.
- **Decision Support Utilities**
  - Align on pricing model inputs (full-day vs. half-day, transport add-ons).
  - Validate FAQ themes; gather SME answers and regulatory citations.
  - Identify caregiver guides/checklists requiring PDF design.
- **Localization**
  - Prioritize languages (Mandarin first) and confirm translation vendor/process.
  - Decide on localized multimedia (captions, transcripts) timelines.
- **Analytics & KPIs**
  - Reconfirm metrics: assessment completion, tour engagement, estimator usage, booking conversions, bounce targets.
  - Define event naming conventions and dashboard requirements.

## 4. Data & Integration Mapping
- **Booking Availability**
  - Source: Calendly or internal scheduling API.
  - Needs: Endpoint for slot summaries, SLA for refresh, fallback message for downtime.
- **Program & Testimonial Content**
  - Source: Existing `Program`/`Testimonial` tables; plan for CMS or admin upload.
  - Needs: Fields supporting personalization tags (e.g., mobility-friendly, dementia care).
- **Cost Estimator Inputs**
  - Source: Finance team spreadsheets; convert to machine-readable JSON/DB entries.
  - Needs: Validation rules, subsidy logic, disclaimers.
- **FAQ & Resource Library**
  - Source: Caregiver support team documentation; determine update cadence.
  - Needs: Metadata (category, persona, language) for search/filter.
- **Analytics & Logging**
  - Tooling: Plausible (per `config/analytics.php`), log channels in `storage/logs`.
  - Needs: Event schema, consent prompts, PDPA alignment.

## 5. Content & Asset Inventory Tracker
| Asset Type | Existing | Gap | Owner | Action |
| --- | --- | --- | --- | --- |
| Hero availability copy | Placeholder | Needs real-time messaging variants | Product | Draft localized copy post data integration |
| Assessment questions | Not authored | Requires SME workshop | Clinical Lead | Schedule discovery session |
| Virtual tour media | Static placeholder | Need high-res video, hotspots | Creative | Curate footage, confirm licenses |
| Staff spotlight bios | Partial (internal doc) | Requires public-ready copy/photos | HR | Secure permissions, editing |
| FAQ entries | Legacy FAQ deck | Needs update for new services | Care Ops | Provide revised answers, regulatory citations |
| Cost/pricing tables | Finance sheet | Map to estimator inputs | Finance | Share structured data extract |
| Translations (ZH) | None | Full content localization | Localization Vendor | Scope translation timeline |

## 6. Risks & Mitigations
- **Content Bottlenecks**: Critical assets (tour media, pricing) delay feature dev.
  - *Mitigation*: Introduce redline placeholders with clear TODO flags; escalate via weekly standups.
- **Integration Uncertainty**: Booking API stability or rate limits unclear.
  - *Mitigation*: Prototype with mocked responses; negotiate SLA early; add caching via Laravel cache.
- **Scope Drift**: Desire for additional languages/features mid-phase.
  - *Mitigation*: Enforce change control; log backlog items for post-Phase 0 review.
- **Accessibility Compliance**: Late addition of transcripts/captions risks launch slip.
  - *Mitigation*: Embed accessibility acceptance criteria into asset delivery; partner with compliance expert.

## 7. Deliverables & Success Criteria
- **Deliverables**
  - Approved assessment flowchart and question bank (stored in `docs/ux/assessment.md`).
  - Asset inventory spreadsheet with owners and due dates (to be shared via PM tool, reference in `docs/plans/asset_inventory.md`).
  - Data integration brief summarizing endpoints, auth, and caching strategy (`docs/architecture/data-integrations.md`).
  - Updated backlog issues/story cards referencing this brief (GitHub project).
- **Success Criteria**
  - Stakeholder sign-off on personas, data sources, and content responsibilities.
  - No critical unknowns blocking Phase 1 development kickoff.
  - Risks documented with owners and mitigation plans.

## 8. Timeline & Cadence
- **Week 0 Day 1**: Kickoff workshop; capture outstanding questions.
- **Week 0 Day 3**: Asset/data owners confirmed; deliverables assigned.
- **Week 0 Day 5**: Review session; finalize assessment flow and data plans.
- **Ongoing**: Weekly sync to monitor asset collection and dependency status.

## 9. Next Steps Checklist
- [ ] Schedule stakeholder workshops (Product, Clinical, Care Ops, Creative, Localization, Engineering).
- [ ] Compile existing assets into shared drive; link references here.
- [ ] Draft initial assessment questionnaire outline for review.
- [ ] Initiate booking API discovery with provider (auth, rate limits, formats).
- [ ] Document analytics event taxonomy draft for approval.

---
**Reference**: Aligns with `docs/plans/master_todo_roadmap.md` Phase 0 objectives and prepares workstreams for Phase 1 execution.
