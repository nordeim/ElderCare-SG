# Scaffold QA Checklist

## Environment Verification
- [x] `php artisan migrate --seed` completes without errors. *(2025-09-30 via Sail)*
- [x] `php artisan serve` (via script) responds at http://localhost:8000. *(2025-09-30 manual run)*
- [x] Homepage renders seeded programs and testimonials in browser. *(2025-09-30 manual check)*

## Accessibility & UX
- [ ] Navigation toggle works with keyboard and screen readers.
- [ ] Hero video provides captions fallback (verify poster file present).
- [ ] Testimonials carousel initializes without console errors when Embla assets load.
- [ ] Footer newsletter form includes accessible labels and aria-live messaging.
- [ ] Guided assessment modal trap focus, announces progress, and allows Escape closure.
- [ ] Assessment summary reflects persona-driven CTA text and displays submission status messaging.
- [ ] Hero primary CTA updates label/href after completing assessment segment.
- [ ] Programs section shows "Personalized highlights" panel aligned with selected segment.
- [ ] Availability badge announces status via aria-live and refresh button toggles politely.
- [ ] Locale switcher dropdown operates with keyboard, focus management, and announces current language.
- [ ] Virtual tour modal traps focus, supports Escape to close, and restores focus to trigger button.
- [ ] Hotspot list supports keyboard navigation (arrow keys) and announces active state.
- [ ] Each hotspot reveals media with transcript link and captions (verify video track available).
- [ ] Staff carousel cards are focusable, images include alt text, and carousel navigation remains accessible.

## Performance
- [ ] Lighthouse desktop score ≥ 85 (baseline before optimization). *(Pending — script executes `lhci` but requires Lighthouse to be installed; rerun after review)*
- [x] Verify Tailwind purge reducing CSS bundle size in production build (`npm run build`). *(2025-09-30 — build succeeded)*
- [ ] Resolve hero/video asset fallbacks to avoid blocking media. *(2025-10-01 — fallback logic in place; monitor once final assets delivered)*
- [ ] Improve FCP/LCP/TTI from latest run (FCP 3.83s, LCP/TTI 4.39s, MPFID 728ms, score 0.66). Focus areas: lazy-load hero media, defer non-critical Alpine stores, audit CSS layout cost.
- [ ] Investigate main-thread style/layout cost (~1.05s) and script evaluation (~0.5s) highlighted in `localhost-_-2025_10_01_10_05_28.report.html`.
- [ ] Address `max-potential-fid` warning by profiling input listeners and reducing long tasks over 50 ms.

## Data Integrity
- [ ] Programs seeded with correct highlights and display order.
- [ ] Testimonials seeded with author metadata and featured flag.
- [ ] Newsletter form logs fallback when Mailchimp credentials absent.
- [ ] `/assessment-insights` endpoint logs segment and answers payload without sensitive data exposure.
- [ ] Plausible dashboard registers `assessment.complete` and `assessment.submitted` events with expected properties.

## Logging & Services
- [ ] `storage/logs/laravel.log` captures booking CTA events via `BookingService`.
- [ ] Mailchimp service logs success/failure details with redaction of sensitive data.

## Follow-up Automation
- [ ] Add axe-core automated audit to CI pipeline.
- [ ] Configure Lighthouse CI GitHub Action.
- [x] Document manual QA results in this checklist after each run. *(2025-09-30)*

### Automation Plan Notes
- **axe-core**: Integrate via `npm run lint:accessibility` script using `@axe-core/cli`; plan to run in CI post-build. Evaluate Headless Chrome vs Playwright runner.
- **Lighthouse CI**: Use `@lhci/cli` with `lhci autorun` in GitHub Actions; target scores ≥90 for Performance/Accessibility/PWA. Store config in `lighthouserc.json`.
- **Reporting**: Publish CI artifacts and surface summary in PR comments. Add failing thresholds to block merges when scores drop.
