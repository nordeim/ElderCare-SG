# Asset Inventory — Guided Needs Assessment & Related Experiences
_Date: 2025-10-01_

## Overview
This inventory consolidates core content and media assets needed across the Guided Assessment, Hero availability updates, Virtual Tour, and Decision Support utilities. It aligns with the deliverables called out in `docs/plans/phase0_alignment_research_brief.md` and supports execution of Phase 1 and Phase 2 in `docs/plans/master_todo_roadmap.md`.

## Asset Tracker
| Asset | Current Status | Gap/Action | Owner | Due Date | Notes |
| --- | --- | --- | --- | --- | --- |
| Assessment question copy (EN) | Drafted in `docs/ux/assessment.md` | Await clinical validation | Clinical Lead | 2025-10-03 | Requires bilingual review before localization freeze |
| Assessment question copy (ZH) | Not started | Translate validated EN copy | Localization Vendor | 2025-10-09 | Provide glossary & tone guidance |
| Persona-tailored CTA language | Draft in `resources/lang/en/assessment.php` | Confirm variants for hero/program sections | Product Marketing | 2025-10-04 | Feed into personalization bindings |
| Program testimonials (per segment) | Partial coverage in DB seeders | Collect additional quotes for `memory_care` & `respite_support` | Care Ops | 2025-10-07 | Ensure consent for website usage |
| Hero availability messaging | Placeholder copy only | Draft real-time + fallback messaging variants | Product | 2025-10-05 | Needed for Phase 2 availability module |
| Virtual tour video (EN narration) | In production | Final edit & caption file | Creative | 2025-10-12 | Provide VTT + transcript per accessibility req |
| Virtual tour video (ZH narration) | Not commissioned | Decide if needed for launch | Product & Localization | 2025-10-19 | Optional for Phase 2 scope |
| Hotspot overlay map JSON | Not authored | Produce coordinates + labels | Creative | 2025-10-12 | Used by upcoming `resources/js/modules/tour.js` |
| Cost estimator pricing tables | Finance spreadsheet | Convert to JSON/DB seed format | Finance Analyst | 2025-10-08 | Required for Phase 4 |
| FAQ answers update | Legacy deck | Refresh to cover new services & PDPA | Care Ops + Legal | 2025-10-06 | Required before localization |
| Caregiver resource PDFs | Partially available | Update layout + add branding | Creative | 2025-10-11 | Upload to storage and catalog |
| Assessment illustrations/icons | Placeholder shapes | Commission final illustrations | Creative | 2025-10-10 | Provide light/dark mode variants |
| Analytics event taxonomy | Draft in `docs/ux/assessment.md` | Finalize names + dashboard mapping | Data Analyst | 2025-10-04 | Needed for Plausible config |

## Status Summary
- **In Progress**: Assessment copy validation, hero messaging, analytics taxonomy.
- **At Risk**: Virtual tour assets and hotspot JSON share the same delivery date; confirm creative bandwidth.
- **Pending Owners**: Confirm localization vendor availability for Chinese translation.

## Next Steps
1. Circulate this tracker to stakeholders along with Phase 0 brief.
2. Update `docs/plans/phase0_alignment_research_brief.md` with owner confirmations and dates.
3. Review outstanding assets weekly; adjust due dates as priorities shift.
