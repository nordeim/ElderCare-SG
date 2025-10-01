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

## Performance
- [ ] Lighthouse desktop score ≥ 85 (baseline before optimization). *(Pending — script executes `lhci` but requires Lighthouse to be installed; rerun after review)*
- [x] Verify Tailwind purge reducing CSS bundle size in production build (`npm run build`). *(2025-09-30 — build succeeded)*

## Data Integrity
- [ ] Programs seeded with correct highlights and display order.
- [ ] Testimonials seeded with author metadata and featured flag.
- [ ] Newsletter form logs fallback when Mailchimp credentials absent.
- [ ] `/assessment-insights` endpoint logs segment and answers payload without sensitive data exposure.

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
