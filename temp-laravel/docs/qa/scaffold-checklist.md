# Scaffold QA Checklist

## Environment Verification
- [x] `php artisan migrate --seed` completes without errors. *(2025-09-30 via Sail)*
- [ ] `npm run dev` starts Vite dev server without warnings. *(Pending — production build verified via `npm run build`)*
- [ ] Homepage renders seeded programs and testimonials in browser.

## Accessibility & UX
- [ ] Navigation toggle works with keyboard and screen readers.
- [ ] Hero video provides captions fallback (verify poster file present).
- [ ] Testimonials carousel initializes without console errors when Embla assets load.
- [ ] Footer newsletter form includes accessible labels and aria-live messaging.

## Performance
- [ ] Lighthouse desktop score ≥ 85 (baseline before optimization).
- [x] Verify Tailwind purge reducing CSS bundle size in production build (`npm run build`). *(2025-09-30 — build succeeded)*

## Data Integrity
- [ ] Programs seeded with correct highlights and display order.
- [ ] Testimonials seeded with author metadata and featured flag.
- [ ] Newsletter form logs fallback when Mailchimp credentials absent.

## Logging & Services
- [ ] `storage/logs/laravel.log` captures booking CTA events via `BookingService`.
- [ ] Mailchimp service logs success/failure details with redaction of sensitive data.

## Follow-up Automation
- [ ] Add axe-core automated audit to CI pipeline.
- [ ] Configure Lighthouse CI GitHub Action.
- [ ] Document manual QA results in this checklist after each run.

### Automation Plan Notes
- **axe-core**: Integrate via `npm run lint:accessibility` script using `@axe-core/cli`; plan to run in CI post-build. Evaluate Headless Chrome vs Playwright runner.
- **Lighthouse CI**: Use `@lhci/cli` with `lhci autorun` in GitHub Actions; target scores ≥90 for Performance/Accessibility/PWA. Store config in `lighthouserc.json`.
- **Reporting**: Publish CI artifacts and surface summary in PR comments. Add failing thresholds to block merges when scores drop.
