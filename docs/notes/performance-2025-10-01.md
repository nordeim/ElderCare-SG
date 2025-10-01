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

## Next Performance Experiments
- Evaluate splitting `app.js` bundle by lazy importing Alpine stores (e.g. cost estimator, tour) and ensure globals remain accessible.
- Integrate `rollup-plugin-visualizer` (or `vite-plugin-visualizer`) to generate bundle treemaps since `vite build --analyze` is unavailable.
- Run `npx source-map-explorer public/build/assets/app-*.js` with `--html` after adjusting Vite sourcemap options; note current attempt fails due to `generated column Infinity` in Vite map.
- Collect Chrome Performance trace focusing on `#programs` section layout to identify expensive selectors or repeated DOM measurements.

## Prioritized Follow-ups
- **Caching**: Complete steps above so CDN/browser warnings clear and bandwidth drops.
- **Bundle splitting**: Prototype lazy imports for cost estimator and tour stores after treemap insight.
- **Layout audit**: Use Chrome trace to refactor `#programs` grid and cost estimator DOM to trim ~1.8 s style/layout cost.
- **Monitoring**: Automate Lighthouse + Axe in CI once performance budget stabilizes.
