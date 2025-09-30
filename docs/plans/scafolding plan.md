# ElderCare SG Scaffolding Plan

## 1. Objectives & Alignment
- **Ensure** Laravel 12 + TailwindCSS + Alpine.js baseline adheres to requirements in `Project Requirements Document.md` and operational steps in `project scaffolding guide.md`.
- **Enable** design-first delivery by pre-configuring typography, color tokens, and motion utilities matching the UI system.
- **Support** performance and accessibility goals (Lighthouse >90, WCAG 2.1 AA) through thoughtful build tooling and QA automation from day one.
- **Prepare** integration pathways for Mailchimp, scheduling, analytics, and CMS-like data handling without blocking initial UX delivery.

## 2. Prerequisites Checklist
- **Environment**: PHP 8.2+, Composer, Node.js ≥ 18, Git. Optional Docker with Laravel Sail per `README.md` and `project scaffolding guide.md`.
- **Accounts & Keys**: Mailchimp sandbox API key, scheduler (Calendly/internal) credentials, analytics platform (e.g., Plausible or GA4) for later phases.
- **Assets**: Placeholder media complying with licensing guidelines; fonts Playfair Display and Inter hosted via Google Fonts.

## 3. Phase Breakdown
### Phase 1 – Repository & Environment Foundations
- **Initialize Laravel** with `composer create-project laravel/laravel` and set up Git repository.
- **Configure Sail** for Dockerized workflow (`php artisan sail:install`) and ensure `./vendor/bin/sail up -d` runs cleanly.
- **Commit Baseline** including `.editorconfig`, `.gitattributes`, and `.env.example` updates with queue/database defaults (Redis, MariaDB).

### Phase 2 – Frontend Build Pipeline
- **Install Node Stack** following `project scaffolding guide.md` (Tailwind, PostCSS, Autoprefixer, Vite, laravel-vite-plugin).
- **Tailwind Configuration**: Extend `tailwind.config.js` with semantic color tokens (`trust`, `gold`, `amber`, `wellness`, `slate`) and font families (`heading`, `body`).
- **Entry Points**: Create `resources/css/app.css` with `@tailwind` directives and reusable component layers; initialize `resources/js/app.js` registering Alpine globally.
- **Vite Setup**: Update `vite.config.js` for Laravel refresh and ensure `@vite` blade directive is wired in `resources/views/layouts/app.blade.php` along with Google Fonts preload tags.
- **Scripts**: Add npm scripts (`dev`, `build`, `watch`) and specify Node engine ≥ 18 in `package.json`.

### Phase 3 – UX Component Scaffolding
- **Layout Shells**: Scaffold Blade layout (`layouts/app.blade.php`) and partials for header/footer referencing Tailwind classes matching navigation and footer specs in `Project Requirements Document.md`.
- **Atomic Components**: Create Blade components for CTA buttons, cards, testimonials carousel, assessment form shells. Utilize Tailwind + shadcn-inspired patterns (class-variance-authority, tailwind-merge).
- **Interactivity**: Prepare Alpine stores for navigation (mobile overlay, sticky header), hero lightbox toggles, questionnaire state, and Embla carousel initialization (imported via dedicated JS module).

### Phase 4 – Data & CMS Preparation
- **Models & Seeders**: Define program, testimonial, staff highlight models with seeders to feed Blade components via controller/view composers.
- **Configurable Content**: Organize `config/ui.php` or similar for global copy (taglines, CTA labels) enabling easy updates without touching Blade markup.
- **Integration Abstractions**: Stub services for Mailchimp (`app/Services/MailchimpService.php`) and booking scheduler to plug into controllers later, ensuring graceful fallbacks when credentials absent.

### Phase 5 – Developer Experience & Quality Gates
- **Linting & Standards**: Introduce PHP CS Fixer, Laravel Pint, ESLint (optional), and Prettier for JS/Blade formatting consistency.
- **Testing Harness**: Set up Pest or PHPUnit baseline with smoke test ensuring hero route renders; add Playwright or Laravel Dusk placeholder for later UI testing.
- **Automation**: Configure GitHub Actions to run composer install, npm build, Pint linting, Pest tests, and Lighthouse CI (using `lighthouse-ci` npm package) against built assets.

### Phase 6 – Validation & Documentation
- **Manual Checklist**: Mirror the validation list from `Project Requirements Document.md` (accessibility, performance, responsiveness, functional interactions, content tone).
- **DX Docs**: Document development flows in `docs/architecture.md`, `docs/design-system.md`, and update `README.md` after scaffolding to reflect commands and scripts.
- **Stakeholder Handoff**: Provide Loom/screenshots demonstrating dev environment, hot reload, and sample component usage to align with design partners.

## 4. Detailed Task Flow (Gantt-style Narrative)
- **Week 1**: Complete Phases 1–2, confirm hot reload, commit baseline. Deliverable: running Laravel app with Tailwind tokens ready.
- **Week 2**: Execute Phase 3 component scaffolds with placeholder content, verifying responsive behavior. Deliverable: interactive skeleton aligning with landing page blueprint.
- **Week 3**: Implement Phase 4 data hooks and services; integrate sample data via seeders. Deliverable: dynamic content powering components.
- **Week 4**: Phase 5 DX tooling and CI pipeline operational. Deliverable: passing automated checks, documented scripts.
- **Week 5**: Phase 6 validation, finalize docs, prep for full feature builds.

## 5. Validation Matrix
| Area | Success Criteria | Verification Method |
|------|------------------|---------------------|
| Build Pipeline | `npm run dev`/`build` succeed, HMR operational | Local run + CI job |
| Styling | Tailwind tokens render correct palette/font stack | Visual check + unit snapshot |
| JS Framework | Alpine components initialize without console errors | Browser dev tools |
| Carousel | Embla integrates with Alpine, is keyboard-focusable | Manual QA + axe |
| Accessibility | prefers-reduced-motion respected, aria tags present | axe-core audit |
| Performance | Initial Lighthouse ≥ 85 (pre-optimization), target >90 | Lighthouse CI |
| DX | Pint, Pest tests, CI pipeline pass | GitHub Actions status |

## 6. Risk Mitigation
- **Dependency Drift**: Lock versions via `package-lock.json`/`composer.lock`, schedule monthly dependency reviews.
- **Performance Debt**: Enforce strict bundle analysis early with Vite visualizer and continue lazy loading media.
- **Accessibility Oversights**: Incorporate Storybook-like previews (optional) plus automated axe tests in CI before feature merges.
- **Integration Delays**: Use feature flags and mock services so Mailchimp/scheduler gaps do not stall scaffolding progress.

## 6.1 Tooling Notes & QA Follow-up
- **Tailwind Editor Lints**: IDE warnings for `@tailwind` and `@apply` in `resources/css/app.css` stem from raw Tailwind directives pre-compilation. Safe to ignore or suppress after verifying Vite build success.
- **Next QA Actions**: Schedule `php artisan migrate --seed` followed by `npm run dev` smoke test. Add axe + Lighthouse runs once layout stabilizes. Document outcomes in `docs/qa/scaffold-checklist.md` (to create).

## 7. Deliverables & Artifacts
- **Source Control**: Branch `chore/scaffold-base` merged via PR with reviewer checklist referencing this plan.
- **Documentation**: Updated `README.md`, new `docs/design-system.md` tokens, `docs/architecture.md` overview.
- **Templates**: Blade layout, navigation/footer partials, CTA button component, Embla carousel stub.
- **Automation**: GitHub Actions workflow file, Lighthouse CI config, Pint/Pest configuration files.

## 8. Review & Validation Steps
1. **Peer Review**: Walk through branch with checklist verifying every Phase requirement met; reference `project scaffolding guide.md` to confirm parity.
2. **Design Sync**: Demo component library to design stakeholders ensuring adherence to color/typography guidelines from `Project Requirements Document.md`.
3. **QA Dry Run**: Execute validation matrix tasks, log outcomes in `docs/qa/scaffold-checklist.md` (to be created).
4. **Sign-off**: Document approval in project tracker noting readiness for feature implementation sprints.

---
Prepared with direct reference to `project scaffolding guide.md` and aligned to the UX, accessibility, and technical objectives of ElderCare SG.
