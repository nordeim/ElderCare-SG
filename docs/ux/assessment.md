# Guided Needs Assessment Specification
_Date: 2025-10-01_

## 1. Experience Overview
- **Goal**: Personalize landing page content, CTAs, and follow-up prompts based on family needs discovered via a short interactive questionnaire.
- **Entry Points**: Primary CTA button in hero (`Take a quick assessment`), nav link, and inline prompts within the Programs section.
- **Exit States**: Tailored recommendations (program bundle, testimonial highlights, CTA label), option to schedule consultation, and email capture for follow-up.

## 2. Question Flow & Logic
| Step | Topic | Question Copy | Input Type | Answer Options | Notes |
| --- | --- | --- | --- | --- | --- |
| 1 | Care Context | "Who are you seeking care for?" | Button group | `Mom`, `Dad`, `Relative`, `Other` | Sets personalization tone |
| 2 | Support Frequency | "How often do you need daytime support?" | Button group | `Weekdays`, `Selected days`, `Occasional`, `Not sure` | Maps to scheduling recommendations |
| 3 | Mobility | "How much support does your loved one need with movement?" | Likert (3 choices) | `Independent`, `Needs some assistance`, `Requires full support` | Influences program suggestions |
| 4 | Cognitive Support | "How would you describe memory support needs?" | Likert (3 choices) | `Engaged & social`, `Mild memory changes`, `Specialised dementia care` | Flags dementia-friendly programming |
| 5 | Health Considerations | "Any specific health considerations we should prepare for?" | Multi-select checkboxes | `Diabetes`, `Cardiac`, `Stroke recovery`, `Mobility aids`, `Other` | Drives staff readiness checklist |
| 6 | Transportation | "Do you need door-to-door transport?" | Button group | `Yes`, `Sometimes`, `No` | Impacts cost estimator defaults |
| 7 | Caregiver Goals | "What do you hope this program provides for your family?" | Multi-select | `Daytime engagement`, `Clinical oversight`, `Respite for caregivers`, `Therapy services`, `Community connection` | Copy personalization |
| 8 | Contact Preference | "How would you like us to follow up?" | Button group | `Book consultation now`, `Email me details`, `Just browsing` | Determines CTA behavior |

## 3. Scoring & Persona Mapping
| Segment | Criteria | Recommended Programs | CTA Label | Messaging Emphasis |
| --- | --- | --- | --- | --- |
| **Active Day Engagement** | Mobility `Independent` AND Cognitive `Engaged` | `Day Programs`, `Community Circles` | `Schedule a discovery visit` | Highlight social calendar, enrichment activities |
| **Supportive Care** | Mobility `Needs some assistance` OR Health includes `Mobility aids` | `Wellness Support`, `Therapy & Rehab` | `Plan a tailored care visit` | Stress licensed therapists, custom plans |
| **Memory Care Focus** | Cognitive `Specialised dementia care` | `Memories & Moments`, `Family Coaching` | `Book a memory care consultation` | Underline dementia-trained staff, safe spaces |
| **Respite & Logistics** | Transportation `Yes` OR Caregiver Goals include `Respite for caregivers` | `Flexible Care Pass`, `Door-to-Door Support` | `Build your flexible schedule` | Emphasize transport, flexible bookings |
| **Exploration Mode** | Contact Preference `Just browsing` | Newsletter, testimonials, resource guides | `Send me caregiver resources` | Provide educational content before sales |

## 6. Integration Touchpoints
- **Blade Integration**: Insert `<x-assessment />` component within `home.blade.php`, passing initial data such as programs and testimonials.
- **Localization**: Add `lang/en/assessment.php` & `lang/zh/assessment.php` with question copy and CTAs.
- **Database (Future)**: Consider storing aggregated assessment stats in `assessment_insights` table (optional Phase 6).

## 7. Dependencies & Open Questions
- Confirm final question wording with clinical team.
- Validate legal/compliance review for health-related question phrasing.
- Determine whether to capture contact info within assessment or via follow-up modal.
- Identify data retention policy for submitted assessment logs (PDPA compliance).

## 8. Implementation Checklist
- [ ] Wireframe review & approval.
- [ ] Copywriting sign-off in both languages.
- [x] Alpine store & component scaffolding created. *(2025-10-01)*
- [x] Persona mapping unit tests for `AssessmentService`. *(2025-10-01)*
- [x] Backend logging endpoint wired via `/assessment-insights`. *(2025-10-01)*
- [x] Hero CTA & programs section personalize via recommendation store. *(2025-10-01)*
- [ ] Accessibility audit (axe CLI) on assessment modal.
- [ ] Analytics event QA with Plausible.

## 9. Timeline
- **Week 1 Day 1–2**: Finalize flow & copy; build components.
- **Week 1 Day 3**: Integrate personalization logic & analytics.
- **Week 1 Day 4**: QA (accessibility, localization, responsiveness).
- **Week 1 Day 5**: Stakeholder review & iterate.

---
**Reference**: Aligns with `docs/plans/master_todo_roadmap.md` Phase 1 objectives and `docs/plans/phase0_alignment_research_brief.md` discovery outputs.
