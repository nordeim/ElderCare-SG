# Enhanced Agent Context: ElderCare SG Platform

## Project Overview

**Purpose:** ElderCare SG is a compassionate, modern web platform connecting Singapore families with trusted elderly daycare services. The platform balances emotional reassurance with clinical trustworthiness, targeting adult children (30-55) seeking reliable care for aging parents.

**Mission:** "Where compassionate care meets modern comfort for every family."

**Strategic Goals:**
- 30% increase in booking conversions
- Bounce rate <40%
- Lighthouse score >90 on mobile and desktop
- WCAG 2.1 AA accessibility compliance

**Target Audiences:**
- Adult Children (30-55): Seeking reliable daytime care with transparent pricing
- Domestic Caregivers: Need program clarity and coordination tools
- Healthcare Professionals: Require accreditation visibility and referral workflows

## Technical Stack

| Component | Technology | Version | Confirmation Source |
|-----------|------------|---------|---------------------|
| Backend Framework | Laravel | `~12.0` | `composer.json` |
| Language | PHP | `8.3` | `Dockerfile` |
| Database | MariaDB | `10.11` | `docker-compose.yml` |
| Caching/Queues | Redis | `7.4` | `docker-compose.yml` |
| Frontend Stack | Blade + TailwindCSS + Alpine.js | - | `package.json` |
| Build Tool | Vite | - | `vite.config.js` |
| Dev Environment | Docker | - | `docker-compose.yml`, `Makefile` |

## Architecture

**Pattern:** MVC with Service Layer
- Controllers (`app/Http/Controllers`) handle HTTP requests
- Models (`app/Models`) interact with database via Eloquent
- Services (`app/Services`) contain business logic and integrations
- Form Requests (`app/Http/Requests`) validate incoming data

**Key Directories:**
```
app/
├── Http/
│   ├── Controllers/     # Request handling
│   └── Requests/        # Form validation
├── Models/              # Eloquent models
├── Services/            # Business logic
└── Providers/           # Service providers

resources/
├── views/               # Blade templates
│   ├── components/      # Reusable components
│   └── layouts/         # Page layouts
└── js/                  # Alpine.js modules

database/
├── migrations/          # Schema definitions
└── seeders/             # Sample data
```

## Development Workflow

### Primary Commands
```bash
# Start all services (builds if necessary)
make up

# Stop and remove all containers
make down

# View application logs
make logs

# Open shell in app container
make bash

# Run database migrations
make migrate

# Reset and reseed database (deletes all data)
make migrate-fresh

# Run tests
make test

# Build frontend assets
make npm-build

# Start frontend dev server
make npm-dev
```

### Application URLs
- Main Application: `http://localhost:8000`
- Mailhog (email testing): `http://localhost:8025`

## Data Models & Relationships

| Model | Table | Purpose | Key Relationships |
|-------|-------|---------|-------------------|
| User | users | Application users | - |
| Program | programs | Daycare program details | - |
| Testimonial | testimonials | Customer reviews | - |
| Staff | staff | Staff member information | - |
| Faq | faqs | FAQ items | - |
| Resource | resources | Downloadable guides | - |

## Service Layer & Integrations

| Service | Purpose | Implementation Status |
|---------|---------|----------------------|
| MailchimpService | Newsletter subscription | ✅ Implemented |
| AvailabilityService | Real-time availability data | ✅ Implemented |
| BookingService | Booking CTA interactions | ✅ Partially implemented |
| AssessmentService | Needs assessment logic | 🔄 Planned |

## Frontend Architecture

**Component Structure:**
- Blade components in `resources/views/components/`
- Alpine.js modules in `resources/js/modules/`
- TailwindCSS for styling with custom theme tokens

**Key Components:**
- Hero section with CTAs
- Program showcase cards
- Testimonial carousel
- Virtual tour experience
- Newsletter subscription form
- FAQ accordion

## Design System Requirements

**Color Palette:**
- Deep blues (trust)
- Warm ambers (warmth)
- Calming greens (reassurance)

**Typography:**
- Playfair Display (serif) for headings
- Inter (sans-serif) for body text
- Fluid clamp utilities for responsive scaling

**Motion Guidelines:**
- Micro-interactions with hover effects
- Fade-in animations
- Respects `prefers-reduced-motion`
- Centralized easing curves and durations

## Accessibility Standards

**WCAG 2.1 AA Compliance:**
- ✅ Color contrast checked
- ✅ Keyboard navigability for all components
- ✅ Screen reader accessibility
- ✅ Reduced motion support
- ✅ Focus management

**Lighthouse Targets:**
- Performance >90
- Accessibility >90
- Best Practices >90
- SEO >90

## Core Features Implementation

### 1. Homepage
- Hero section with dual CTAs and a "live" availability snapshot.
- Active programs display pulled from the database.
- Testimonials carousel (interactive).
- Newsletter subscription form.

### 2. Virtual Tour
- Interactive modal experience with hotspots.
- Data source: `resources/data/tour_hotspots.json`.
- Dynamic staff carousel populated from the `staff` table.
- Alpine.js for state management and analytics event tracking.

### 3. Decision Support Tools
- ✅ **Cost Estimator:** A fully implemented interactive tool for estimating monthly fees.
- ✅ **FAQ System:** An accessible accordion with live search/filter functionality.
- ✅ **Resource Hub:** A section for users to download caregiver guides.
- 🔄 **Needs Assessment:** (Planned) An interactive questionnaire to provide personalized recommendations.

### 4. Newsletter System
- Mailchimp API integration via `MailchimpService`.
- Server-side validation using a Form Request.
- Session-based success/error flash messaging.

## Testing Strategy

**Test Types:**
- PHPUnit for backend logic (Unit & Feature tests).
- Vitest for JavaScript/Alpine.js components.
- Playwright for end-to-end browser testing.
- Accessibility tests with `axe-core` via an npm script.

**Running Tests:**
```bash
# Full PHPUnit test suite
make test

# Specific test file
make artisan ARGS="test tests/Feature/HomePageTest.php"

# Run JavaScript tests
make npm-test-js
```

## Development Guidelines

**Code Organization:**
- Controllers should be thin and delegate logic to services.
- All significant business logic belongs in `app/Services`.
- Use Form Requests for all incoming data validation.
- Build all reusable UI elements as Blade components.

**Naming Conventions:**
- Models: `Program` (PascalCase)
- Controllers: `HomeController` (PascalCase + "Controller")
- Services: `MailchimpService` (PascalCase + "Service")
- Views: `virtual-tour.blade.php` (kebab-case)

**Database Conventions:**
- Table names: `testimonials` (plural, snake_case)
- Foreign keys: `user_id` (singular + _id)
- Timestamps: `created_at`, `updated_at`

## Common Implementation Patterns

### Creating a New Feature
1.  Create migration: `make artisan ARGS="make:migration create_feature_table"`
2.  Create model & seeder: `make artisan ARGS="make:model Feature -s"`
3.  Create controller: `make artisan ARGS="make:controller FeatureController"`
4.  Create service (if needed): `app/Services/FeatureService.php`
5.  Create views: `resources/views/feature/`
6.  Add routes: `routes/web.php`
7.  Add tests and run `make test`

### Adding a New Blade Component
1.  Create file: `resources/views/components/component-name.blade.php`
2.  Use in templates: `<x-component-name />`
3.  Document props using `@props` in the component file.

### Frontend Interactivity
1.  Create an Alpine.js module in `resources/js/modules/`.
2.  Import and register it in `resources/js/app.js`.
3.  Add the `x-data` directive to your Blade component.
4.  Ensure all interactive elements are keyboard navigable.

## Troubleshooting

**Common Issues:**
- **Container won't start:** Check `docker-compose.yml` and `.env.docker`.
- **Assets not updating:** Run `make npm-build` or `make npm-dev`.
- **Database errors / Missing data:** Run `make migrate-fresh` to reset and seed the database.
- **Permission issues:** Check file ownership inside the container (`ls -la storage`).

**Debug Commands:**
```bash
# Check container logs
make logs

# Enter container for debugging
make bash

# Check Laravel routes
make artisan ARGS="route:list"

# Clear all application caches
make artisan ARGS="cache:clear && config:clear && view:clear && route:clear"
```

## Project Status & Roadmap

**Current Status:**
- ✅ **Phase 2 (Availability), 3 (Virtual Tour), & 4 (Decision Support) are implemented.**
- ✅ **Phase 5 (Design System) technical work is complete.**
- The application has a feature-rich, interactive frontend.
- The core backend services for existing features are in place.

**Upcoming Milestones (in order):**
- **Phase 1 (Guided Needs Assessment):** Implement the interactive questionnaire.
- **Phase 5 (Docs):** Complete the component documentation.
- **Phase 7 (QA Automation):** Implement GitHub Actions for automated testing and linting.
- **Phase 6 (Data Hardening):** Expand seeders and harden integrations.

## Security Considerations

- Input validation is enforced via Form Requests.
- CSRF protection is enabled by default.
- SQL injection is prevented by using Eloquent ORM.
- XSS is prevented by Blade's default escaping.
- Environment variables are managed in `.env` files and are not committed to git.

## Performance Optimization

- Images are optimized and lazy-loaded where appropriate.
- Assets are compiled and minified for production via Vite.
- Database queries are optimized using eager loading.
- Redis is used for caching frequently accessed data like availability.
- CDN integration for static assets is planned.

