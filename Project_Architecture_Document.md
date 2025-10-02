# Project Architecture Document

## 1. Overview
- **Application**: ElderCare SG — Laravel 12 web platform delivering compassionate elderly daycare information for Singaporean families.
- **Audience**: Adult children, caregivers, and healthcare professionals seeking trustworthy day-program solutions.
- **Objectives**: Communicate care philosophy, surface programs/testimonials, enable newsletter/booking funnels, and maintain accessibility + performance excellence per `temp-laravel/Project_Requirements_Document.md`.

## 2. High-Level Architecture
```mermaid
graph TD
    A[Client Browser] -->|HTTP| B[Laravel App]
    B -->|Renders Blade| C[Views & Components]
    B -->|Eloquent| D[(MariaDB)]
    B -->|Service Call| E[Mailchimp API]
    B -->|External Link| F[Booking Provider]
    B -->|Analytics Script| G[Plausible / Custom]
    C -->|Vite Assets| H[public/build]
    subgraph Infrastructure
        B
        D
    end

## 3. Technology Stack

- **Backend**: Laravel 12, PHP 8.3 (`Dockerfile`).
- **Frontend**: Blade templates, TailwindCSS 3.4 with custom tokens, Alpine.js, Embla carousel (`package.json`).
- **Database**: MariaDB 10.11 with Redis 7.4 for caching/queues (`docker-compose.yml`).
- **Build Tooling**: Vite 7, PostCSS, Tailwind (`vite.config.js`, `postcss.config.js`).
- **QA Tooling**: PHPUnit (phase6 group), Vitest, manual Playwright smoke script, axe-core CLI, Lighthouse CI (`package.json`, `.github/workflows/qa-ci.yml`).

## 4. Application Structure
### 4.1 HTTP Layer
- **Routes**: `routes/web.php` defines `/` served by `HomeController` (invokable), locale switching (`LocaleController`), newsletter subscription (`NewsletterController`), guided assessment submission (`AssessmentController`), and availability polling (`AvailabilityController`).
- **Controllers**:
  - `App\Http\Controllers\HomeController`: Fetches active `Program`, `Testimonial`, `Staff`, `Faq`, and `Resource` models; loads tour hotspots; injects booking URL from `BookingService` into `home` view.
  - `App\Http\Controllers\NewsletterController`: Validates email via `NewsletterSubscriptionRequest`, invokes `MailchimpService`, logs analytics events, and returns flash messaging.
  - `App\Http\Controllers\AssessmentController`: Validates questionnaire payload, delegates segmentation to `AssessmentService`, logs outcomes, and returns segment metadata.
  - `App\Http\Controllers\AvailabilityController`: Proxies availability data from `AvailabilityService` with optional refresh semantics and throttling.
- **Requests**: `App\Http\Requests\NewsletterSubscriptionRequest` and `AssessmentSubmissionRequest` provide validation and coercion for inbound payloads.

### 4.2 Domain & Data Layer
- **Models**:
  - `App\Models\Program`: Program metadata with casted highlights, pricing fields, and `scopeActive()`.
  - `App\Models\Testimonial`: Review author context, ratings, featured flag, and `scopeActive()`.
- **Migrations**: Schema definitions for programs, testimonials, staff, FAQs, and resources reside under `database/migrations/` and leverage Laravel timestamps/foreign key conventions.
- **Seeders**: `database/seeders/DatabaseSeeder.php` wires dedicated seeders (`ProgramSeeder`, `TestimonialSeeder`, `StaffSeeder`, `FaqSeeder`, `ResourceSeeder`) providing rich demo data.

### 4.3 Services & Integrations
- **MailchimpService (`app/Services/MailchimpService.php`)**: Handles double opt-in subscriptions, retries, analytics logging, and masked diagnostics when config is missing.
- **BookingService (`app/Services/BookingService.php`)**: Supplies configurable booking URL (`config/services.php`) and emits analytics events for CTA engagement.
- **AvailabilityService (`app/Services/AvailabilityService.php`)**: Normalizes provider responses, caches slots, handles fallbacks/staleness, and surfaces user-friendly availability summaries.
- **AssessmentService (`app/Services/AssessmentService.php`)**: Determines personalization segments from user answers, composes summaries, and logs outcomes through PSR logger bindings.
- **Analytics Config (`config/analytics.php`)**: Enables Plausible driver with goal mappings consumed by Blade layout (`resources/views/layouts/app.blade.php`).

### 4.4 Frontend Composition
- **Layout**: `resources/views/layouts/app.blade.php` sets meta tags, fonts, Vite assets, analytics script, and wraps page content between navigation/footer partials.
- **Partials**: `resources/views/partials/nav.blade.php` and `resources/views/partials/footer.blade.php` surface global navigation, contact details, and newsletter CTA with status messaging.
- **Landing Page**: `resources/views/home.blade.php` orchestrates hero, guided assessment modal/prompts, personalized program grid, philosophy metrics, testimonial carousel, virtual tour, staff carousel, cost estimator, FAQ accordion, resource hub, and booking CTA.
- **Components & JS**:
  - Blade components (`resources/views/components/*.blade.php`) cover hero, assessment, prompts, staff carousel, cost estimator, FAQ, resource hub, and virtual tour.
  - `resources/js/app.js` boots Alpine stores (`assessment`, `assessment-recommendation`, `availability`, `tour`, `cost-estimator`, `analytics`, `hero`, `carousel`).
- **Styling**: Tailwind theme tokens live in `tailwind.config.js`; base styles and custom utilities in `resources/css/app.css` ensure consistent typography, palette, and motion preferences.

### 4.5 Asset Pipeline
- **Vite**: Configured in `vite.config.js` with Laravel plugin; `npm run dev` for HMR, `npm run build` to emit hashed assets under `public/build/` with manifest consumed by `@vite` directive.
- **PostCSS**: `postcss.config.js` wires Tailwind and Autoprefixer.
- **Build Outputs**: `public/build/assets/app-*.css/js` used by Blade layout; manifest ensures correct versioning in dev/prod.

## 5. Data Flow Examples
### 5.1 Homepage Request
1. Client requests `/` → `HomeController::__invoke()`.
2. Controller queries `Program::active()->orderBy('display_order')` and `Testimonial::active()->orderBy('display_order')`.
3. `BookingService::bookingUrl()` provides CTA link.
4. View `resources/views/home.blade.php` renders sections with data arrays; Blade includes nav/footer partials.
5. Layout loads compiled CSS/JS via `@vite` and optional analytics script.

### 5.2 Newsletter Submission
1. POST `/newsletter` with email.
2. `NewsletterSubscriptionRequest` validates email.
3. `NewsletterController` calls `MailchimpService::subscribe()` with retry logging.
4. Success → flash `newsletter_status` + analytics event; failure → flash `newsletter_error`, retain input, and log diagnostics to `analytics` channel.
5. Footer partial displays messages in aria-live region; Plausible goals trigger via Blade layout queue.

### 5.3 Guided Assessment Submission
1. POST `/assessment-insights` with question payload and metadata.
2. `AssessmentSubmissionRequest` validates structured answers.
3. `AssessmentController` invokes `AssessmentService::createSummary()` to determine segment.
4. Service logs outcome via PSR logger and returns segment copy/CTAs.
5. Alpine store updates UI recommendations and booking CTA messaging.

### 5.4 Availability Polling
1. Client calls `/api/availability` (optionally `?refresh=1`).
2. `AvailabilityController` delegates to `AvailabilityService::getAvailability()`.
3. Service pulls or refreshes cached slots, decorates payload with staleness flags/fallback messaging.
4. Alpine `availability` store updates hero badge, emits analytics events for load/error states.

## 6. Environment & Deployment
- **Docker Workflow**: `make up` builds/starts containers (PHP-FPM, MariaDB, Redis, Mailhog). `.env.docker` stores container credentials; volumes persist storage and public assets.
- **Local Overrides**: Developers with PHP 8.3 and Node.js 22 can run `composer install`, `npm install`, `npm run build`, and `php artisan serve` outside Docker (ensure MariaDB/Redis credentials align).
- **Database Seeding**: `make migrate-fresh` or `php artisan migrate --seed` loads seed data; optional `initialize_database.sql` exists for manual DB provisioning but is not required in Docker path.
- **Configuration Files**: `config/services.php` houses Mailchimp, booking, and availability settings; `.env` files capture secrets for different environments.
- **Production Compose**: `docker-compose-production.yml` runs PHP-FPM + Nginx with read-only assets and health checks; secrets provided via CI/CD.

## 7. QA & Automation Pipeline
- **GitHub Actions**: `.github/workflows/qa-ci.yml` runs on push/PR to `main`/`develop`: PHP 8.3 setup, Node 22 setup, composer install, `npm ci`, `php artisan test --group=phase6`, `npm run lint:accessibility`, `npm run lighthouse` (CI preset), and artifact upload.
- **Local QA Scripts**: `npm run lint:accessibility`, `npm run lighthouse`, and `npm run test:playwright:ci` support developer validation; `make test` wraps PHPUnit.
- **Manual QA**: Checklists live under `docs/qa/` for accessibility, performance, and launch readiness tracking.
- **Analytics Verification**: Session-flashed analytics events can be inspected via browser devtools; Playwright script remains available for ad-hoc regression.

## 8. Future Enhancements
- **Contrast Remediation**: Update palette tokens to guarantee WCAG AA compliance.
- **Performance Optimization**: Address Lighthouse warnings via caching, bundle splitting, and image optimization.
- **Guided Assessment Enhancements**: Expand segmentation logic, analytics tracking, and CTA variations atop current assessment workflow.
- **Documentation**: Expand `docs/design-system.md`, `docs/architecture.md`, and `docs/accessibility.md` for comprehensive onboarding.
- **Internationalization & CMS**: Plan for multilingual content and editorial tooling (Nova/Filament) aligned with roadmap phases 8–11.

## 9. References
- `README.md`
- `Project_Requirements_Document.md`
- `Understanding_Project_Requirements.md`
- Source directories under `app/`, `resources/`, `config/`, and `database/`
- QA artifacts: `docs/qa/scaffold-checklist.md`, `.github/workflows/qa-ci.yml`, `lighthouserc.json`

---
Prepared 2025-09-30 for onboarding and architectural clarity.
