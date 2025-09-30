# Scaffold QA Checklist

## Environment Verification
- [ ] `php artisan migrate --seed` completes without errors.
- [ ] `npm run dev` starts Vite dev server without warnings.
- [ ] Homepage renders seeded programs and testimonials in browser.

## Accessibility & UX
- [ ] Navigation toggle works with keyboard and screen readers.
- [ ] Hero video provides captions fallback (verify poster file present).
- [ ] Testimonials carousel initializes without console errors when Embla assets load.
- [ ] Footer newsletter form includes accessible labels and aria-live messaging.

## Performance
- [ ] Lighthouse desktop score ≥ 85 (baseline before optimization).
- [ ] Verify Tailwind purge reducing CSS bundle size in production build (`npm run build`).

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
