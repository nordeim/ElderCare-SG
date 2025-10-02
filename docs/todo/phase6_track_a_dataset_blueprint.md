# Phase 6 Track A — Dataset Blueprint
_Date: 2025-10-02_

This blueprint enumerates the schema refinements and content expansions required before updating Phase 6 seeders. It will guide migration updates, model fillable arrays, and seeder payloads.

## 1. Programs (`programs` table)
- **Schema adjustments**
  - Add nullable decimal `monthly_rate` (precision 8, scale 2).
  - Add nullable decimal `transport_fee` (precision 6, scale 2).
  - Add nullable unsigned small integer `capacity_daily`.
  - Add enum/string `availability_status` (`available`, `limited`, `waitlist`).
  - Add json `languages_supported` (defaults to `[]`).
  - Add string `analytics_tag` for event instrumentation.
- **Model updates**: Append new attributes to `Program::$fillable` and `Program::$casts`.
- **Seed entries** (ensure `display_order` ascending):
  1. **`day-programs`** — Anchored weekday program, `monthly_rate` 1850, `transport_fee` 120, capacity 60, status `available`, languages `["en", "zh"]`, analytics tag `program.day`.
  2. **`memory-care-mornings`** — Dementia-focused half-day, `monthly_rate` 1650, `transport_fee` 140, capacity 24, status `limited`, languages `["en", "zh", "ms"]`, analytics tag `program.memory`.
  3. **`weekend-respite`** — Saturday/Sunday coverage, `monthly_rate` 950, `transport_fee` 100, capacity 18, status `limited`, languages `["en"]`, analytics tag `program.respite`.
  4. **`night-owl-support`** — Overnight respite, `monthly_rate` 2100, `transport_fee` null, capacity 12, status `waitlist`, languages `["en", "ta"]`, analytics tag `program.overnight`.
  5. **`caregiver-masterclasses`** — Evening education series, `monthly_rate` 420, `transport_fee` null, capacity 40, status `available`, languages `["en", "zh"]`, analytics tag `program.masterclass`.

## 2. Staff (`staff` table)
- **Schema adjustments**
  - Add string `credentials`.
  - Add json `languages_spoken` (default `[]`).
  - Add unsigned tiny integer `years_experience`.
  - Add boolean `on_call` (default false).
- **Model updates**: Extend `Staff::$fillable` and casts.
- **Seed roster** (include `hotspot_tag` alignment with virtual tour):
  - Clinical Director (Amelia Tan) — credentials "RN, MSc Gerontology", languages `["en", "zh"]`, 14 years, on-call true.
  - Physiotherapy Lead (Marcus Lee) — credentials "PT (SingHealth)", languages `["en", "zh"]`, 11 years, on-call true.
  - Clinical Wellness Partner (Dr. Priya Nair) — credentials "MBBS, Geriatrics", languages `["en", "ta"]`, 16 years, on-call true.
  - Hospitality Manager (Nurul Afiqah) — credentials "Dip Hosp Mgmt", languages `["en", "ms"]`, 9 years, on-call false.
  - Dementia Care Coach (Grace Wong) — new entry, hotspot `memory_studio`, languages `["en", "zh"]`, 7 years, on-call false.
  - Music Therapist (Samuel Ho) — hotspot `creative_suite`, languages `["en"]`, 6 years, on-call false.
  - Nutritionist (Faridah Salleh) — hotspot `culinary_lab`, languages `["en", "ms"]`, 12 years, on-call true.
  - Social Worker (Theresa Lim) — hotspot `family_hub`, languages `["en", "zh"]`, 10 years, on-call true.

## 3. Testimonials (`testimonials` table)
- **Schema adjustments**
  - Add nullable string `program_slug`.
  - Add nullable string `language` (ISO codes: `en`, `zh`, `ms`, `ta`).
  - Add nullable timestamp `submitted_at`.
  - Ensure `is_active` present with default true (add column if missing).
- **Model updates**: Extend `fillable` and casts for new columns.
- **Seed personas**
  1. Daughter (existing) — tie to `day-programs`, language `en`, include `submitted_at` (recent date).
  2. Son — map to `wellness-therapy`, language `en`.
  3. Primary caregiver — map to `memory-care-mornings`, language `ms`.
  4. New senior testimonial — `weekend-respite`, language `zh`.
  5. Partner geriatrician testimonial — `night-owl-support`, language `en`.

## 4. FAQs (`faqs` table)
- **Schema adjustments**
  - Add json `tags` (default `[]`).
  - Add string `audience` (e.g., `caregiver`, `senior`, `healthcare_partner`).
  - Add boolean `featured` (default false).
- **Model updates**: Include new attributes in `fillable`/casts; consider `Faq::query()->truncate()` with categories maintained.
- **Seed groups**: Provide at least 10 entries covering subsidies, transportation, medical onboarding, cultural programming, staffing ratios, respite scheduling, trial visit flow, nutrition, language support, and emergency protocols. Each entry should include `tags` representing search keywords, `audience`, and `featured` flag for top questions.

## 5. Resources (`resources` table)
- **Schema adjustments**
  - Add enum/string `resource_type` (`pdf`, `checklist`, `webinar`, `audio`).
  - Add boolean `requires_login` (default false).
  - Add nullable string `external_url` to support CDN-hosted assets.
  - Add nullable string `preview_image`.
- **Model updates**: Expand `fillable`/casts accordingly.
- **Seed catalog**: minimum six resources — caregiver orientation PDF, nutrition toolkit PDF, transport checklist, subsidy explainer webinar (external URL, requires login), cultural programming audio spotlight, caregiver self-care checklist (download).

## 6. Database Seeder (`database/seeders/DatabaseSeeder.php`)
- Ensure truncation strategy remains safe (consider `Model::unguard()` usage).
- Enforce deterministic seeder order: programs → staff → testimonials → resources → FAQs (or order aligned with dependencies).
- Add inline docblock summarizing Phase 6 enhancements and pointing to QA validation steps.

## 7. Validation Workflow
- After schema updates, run migrations (`php artisan migrate`) and seeding (`php artisan db:seed`).
- Document commands in README/admin readiness doc.
- Plan to add feature tests verifying record counts and key attributes (e.g., `TestimonialTest` ensures language+program associations).
