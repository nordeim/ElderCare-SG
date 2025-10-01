# Phase 5 Sub-Plan — Design System & Component Documentation
_Date: 2025-10-02_

## Objective
Strengthen the design system by introducing semantic Tailwind tokens, fluid typography utilities, comprehensive component documentation, and (optionally) a local UI playground for rapid QA.

## Workstreams & Checklists

### 1. Semantic Color Tokens
- **Description** Refactor Tailwind theme to reference CSS variables for core brand tones.
- **Files** `tailwind.config.js`, `resources/css/app.css`, key Blade components using color utilities.
- **Checklist**
  - [ ] Define `:root` CSS variables for palette (`--color-trust`, `--color-gold`, etc.), plus optional dark-mode overrides.
  - [ ] Map `theme.extend.colors` to reference CSS variables via `rgb(var(--color-*) / <alpha-value>)` helpers.
  - [ ] Update critical components (`resources/views/components/hero.blade.php`, `.../cost-estimator.blade.php`, `.../faq.blade.php`, `.../virtual-tour.blade.php`) to use semantic classes, verifying visual parity.
  - [ ] Run `npm run build` (or `npm run dev`) to ensure Tailwind compiles and inspect output for regressions.

### 2. Fluid Typography Utilities
- **Description** Provide `clamp()` driven type scale to improve readability across devices.
- **Files** `tailwind.config.js`, `resources/css/app.css`, targeted Blade components.
- **Checklist**
  - [ ] Introduce Tailwind plugin or `theme.extend.fontSize` entries using `clamp()` for headings/body.
  - [ ] Document usage via utility comments in `resources/css/app.css`.
  - [ ] Apply new classes to priority components (hero headline, section headers, estimator/FAQ headings).
  - [ ] Validate in browser and via `npm run test:playwright:serve` screenshot/visual observation if feasible.

### 3. Component Catalog Documentation
- **Description** Author `docs/components.md` capturing component props, slots, analytics events, and accessibility considerations.
- **Files** `docs/components.md` (new), cross-reference existing docs.
- **Checklist**
  - [ ] Outline sections: Hero, Assessment, Availability, Tour, Cost Estimator, FAQ, Resource Hub, Prompts.
  - [ ] For each, document parameters, data requirements, analytics hooks, and accessibility notes.
  - [ ] Provide example Blade snippets demonstrating usage with semantic typography/colors.
  - [ ] Link to relevant QA checklist items and testing commands (`php artisan test`, `npm run test:js`, `npm run test:playwright:serve`).

### 4. Optional UI Playground (`/ui-kit`)
- **Description** Local-only route showcasing components for QA and design review.
- **Files** `routes/web.php`, `app/Http/Controllers/UIPlaygroundController.php`, `resources/views/ui/playground.blade.php`.
- **Checklist**
  - [ ] Add route guarded by `app()->environment('local')` or config flag.
  - [ ] Create controller rendering curated component states (light/dark, variants).
  - [ ] Build Blade view with guidelines and links to documentation.
  - [ ] Ensure analytics disabled or stubbed to avoid noise during QA.

## Validation & Testing
- After semantic/color changes, run:
  - `npm run build`
  - `npm run test:js`
  - `npm run test:playwright:serve`
  - `php artisan test`
- Update `docs/qa/scaffold-checklist.md` with typography/color regression steps.
- Capture screenshots or notes if visual diffs observed.

## Risks & Mitigations
- **Color Regression**: Use incremental commits, review in multiple browsers; keep fallback colors.
- **Typography Impact on Layout**: Monitor LCP/CLS metrics; adjust clamp bounds if performance regresses.
- **Documentation Drift**: Schedule reviews with design leads post-update.

## Exit Criteria
- Tailwind builds cleanly with semantic tokens and fluid typography.
- Priority components adopt new utilities without visual regressions.
- `docs/components.md` published and referenced in roadmap.
- (Optional) `/ui-kit` playground accessible in local env and documented.
