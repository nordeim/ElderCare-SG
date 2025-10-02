# Performance Optimization Log — 2025-10-01

## Current Metrics
- Performance score: 0.68 (latest Lighthouse run)
- First Contentful Paint: 2.70s
- Largest Contentful Paint: 3.03s
- Time to Interactive: 3.93s
- Speed Index: 3.35s
- Max Potential FID: 20ms
- Main-thread work: Style/Layout 1.81s, Script Evaluation 0.53s

## Optimizations Completed
- Added `content-visibility-auto` to `#programs`, `#philosophy`, `#testimonials`, and `#booking` sections in `resources/views/home.blade.php` to defer layout/paint for offscreen content.
- Implemented `resources/js/modules/hero.js` to lazy-load and pause hero video based on viewport intersection.
- Added hero fallbacks to avoid missing asset console errors in `resources/views/components/hero.blade.php` and `resources/views/components/virtual-tour.blade.php`.
- Lazy-loaded Embla carousel via dynamic import in `resources/js/modules/carousel.js`, shrinking main bundle and emitting separate `embla-carousel.esm-*.js` chunk (≈18.5 kB raw, 7.7 kB gzip).
- Converted axios to load on demand via `window.loadAxios` helper (`resources/js/bootstrap.js`) and dynamic usage in `resources/js/modules/assessment.js`. Latest build produces `app-BAdcxmYn.js` (≈63 kB raw, 22.0 kB gzip) plus an async axios chunk `index-B0yp3bM1.js` (≈36 kB raw, 14.6 kB gzip).

## Outstanding Work
- Investigate residual Style/Layout cost ~1.8s (possible DOM complexity in `#programs` grid and cost estimator modal).
- Defer non-critical Alpine stores or convert to `requestIdleCallback` for analytics module.
- Configure server caching and compression to eliminate `uses-long-cache-ttl` and `uses-text-compression` warnings.
- Profile MPFID outliers to ensure no long tasks >50ms remain.

## Caching & Compression Recommendations
- Configure `public/build/` assets with immutable cache headers, e.g. `Cache-Control: public, max-age=31536000, immutable` via Nginx/Apache or Laravel middleware since filenames are content-hashed (`app-Dpjo-Fa5.js`).
- Enable gzip or brotli compression for HTML, CSS, and JS responses (Laravel `
Illuminate\Foundation\Http\Middleware\CompressResponse` or reverse proxy config) to satisfy `uses-text-compression`.
- Consider HTTP/2 server push replacement using `<link rel="preload">` hints in Blade layouts for `app.css` and `app.js` once compression is active.

### Implementation Steps
1. Update web server or Laravel `app/Http/Middleware` to set immutable cache headers for `public/build/*` while keeping short TTL for HTML responses.
2. Enable gzip/brotli at the reverse proxy (e.g., Nginx `gzip on;` / `brotli on;`) and verify via `curl -I --compressed` that compression is served.
3. After compression is live, add `<link rel="preload">` hints for the hashed CSS/JS bundles in `resources/views/layouts/app.blade.php` to maintain performance budgets.
4. If middleware-based, create `app/Http/Middleware/CacheImmutableAssets.php` to detect hashed filenames (regex `/build\/.*\.[a-f0-9]{8}\.(css|js)$/`) and apply headers.
5. Register middleware alias in `app/Http/Kernel.php` (e.g., `'cache.assets'`) and attach to `routes/web.php` via group wrapping the asset-serving route in production.
6. For Laravel Vapor/Forge setups, ensure `public/.htaccess` or server config mirrors the same headers to avoid divergence between local and production.
7. Validate headers locally with `curl -I http://localhost/build/assets/app-*.js` and confirm `Cache-Control` and `content-encoding` responses match expectations.
    - Note: `php artisan serve` bypasses middleware for static files, so use a real web server (Valet/Nginx/Apache) when validating locally.

## Next Performance Experiments
- Evaluate splitting `app.js` bundle by lazy importing Alpine stores (e.g. cost estimator, tour) and ensure globals remain accessible.
- Integrate `rollup-plugin-visualizer` (or `vite-plugin-visualizer`) to generate bundle treemaps since `vite build --analyze` is unavailable. *(Current npm registry mirror rejected `vite-plugin-visualizer`; consider alternative mirrors or manual Rollup config when access permits.)*
- Run `npx source-map-explorer public/build/assets/app-*.js` with `--html` after adjusting Vite sourcemap options; note current attempt fails due to `generated column Infinity` in Vite map.
- Collect Chrome Performance trace focusing on `#programs` section layout to identify expensive selectors or repeated DOM measurements.

### Visualizer Usage
1. Run `npm run build` to regenerate bundles and `stats.html`.
2. Open `stats.html` in a browser to inspect module treemap (gzip/brotli sizes included).
3. For deterministic CI checks, consider adding a script that runs `npm run build` with `visualizer({ open: false })` and verifies the existence/size of `stats.html`; fail the job if the file is missing or empty.

### Layout Profiling Checklist
1. In Chrome DevTools Performance tab, record a trace while loading the homepage and interacting with the `#programs` cards.
2. Filter long tasks >50 ms and inspect `Layout`/`Recalculate Style` events to identify elements with heavy invalidations (expectations: large grid in `#programs`, cost estimator modal).
3. Use the Elements panel `Layout Shift Regions` overlay to visualize reflows when toggling assessment recommendations.
4. Capture Coverage (`Ctrl+Shift+P` → “Coverage”) post-load to measure unused CSS from Tailwind utility combinations.
5. Export the trace (`.json`) and attach findings to the performance backlog for repeatability.
6. Prioritize fixes: (a) simplify `#programs` card DOM (high impact), (b) batch cost estimator Alpine updates (medium), (c) defer testimonial carousel setup until Idle (medium).

## Prioritized Follow-ups
- **Caching**: Complete steps above so CDN/browser warnings clear and bandwidth drops.
- **Bundle splitting**: Prototype lazy imports for cost estimator and tour stores after treemap insight.
- **Layout audit**: Use Chrome trace to refactor `#programs` grid and cost estimator DOM to trim ~1.8 s style/layout cost.
- **Monitoring**: Automate Lighthouse + Axe in CI once performance budget stabilizes.

## 2025-10-02 Lighthouse Snapshot
- Performance score: 0.43 FCP (warn), 0.68 LCP (warn), CLS 0.84 (warn), main-thread work 0.5 (warn).
- Console errors still detected (score 0). Need to re-check missing asset handlers.
- Action: log warnings in backlog but defer remediation per Phase 7 guidance; CI now records reports under `storage/app/lighthouse`.

## Performance Backlog Snapshot (2025-10-01)
- **High**: Implement immutable caching + gzip/brotli in production; run Chrome layout trace and ship DOM refinements for `#programs`.
- **Medium**: Defer cost estimator/tour Alpine stores via dynamic import; integrate source-map analysis once sourcemap config fixed.
- **Low**: Add CI guard ensuring `stats.html` exists, explore analytics idle loading, evaluate Tailwind tree-shaking metrics post refactor.

## Immediate Next Steps
- Capture Chrome Performance trace following the checklist above and log findings (CSS selectors, DOM nodes) in a new ticket before refactoring.
- Draft Nginx/Apache snippet applying immutable headers + gzip/brotli, referencing `CacheImmutableAssets` middleware as fallback.
- Schedule follow-up Lighthouse run post layout tweaks and caching deployment to compare against `localhost-_-2025_10_01_13_00_46.report.json`.
