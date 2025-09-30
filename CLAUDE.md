# AI Agent Context: Elderly Daycare Platform

**Purpose:** This document is the source of truth for AI agents interacting with this codebase. It provides a concise, factual overview of the project's architecture, key files, and development conventions.

---

## Technical vitals
| Attribute | Value | Location / Confirmation |
|-----------|-------|-------------------------|
| Backend Framework | Laravel 12 | composer.json |
| Language/Runtime | PHP 8.3 | Dockerfile |
| Primary Database | MariaDB 10.11 | docker-compose.yml |
| Caching / Queues | Redis | .env.docker, config/queue.php, config/cache.php |
| Frontend Stack | Blade + TailwindCSS + Alpine.js | package.json |
| Dev Environment | Docker | docker-compose.yml, Makefile |
| CI/CD | Not yet implemented | N/A |

> Sources: `composer.json`, `Dockerfile`, `docker-compose.yml`, `package.json`

---

## Architecture overview

### Intended architecture (if applicable)
There are no separate planning documents. The current implementation is the intended architecture.

### Current implemented architecture
- **Pattern:** MVC with a Service layer
- **Directory structure:** `app/Http/Controllers`, `app/Models`, `app/Services`
- **Frontend:** Blade templates with TailwindCSS and Alpine.js, built with Vite.
- **Background processing:** Redis is configured for queues, but no jobs are implemented yet.
- **Metrics & monitoring:** No dedicated metrics or monitoring system is in place.

### Guidance for AI agent
> - Adhere to the CURRENT architecture.
> - **DO NOT** create files or frameworks not present in the codebase (e.g., `app/Domain`, Livewire) unless explicitly instructed.
> - Place new business logic in Service classes (`app/Services`).
> - Place new controllers in `app/Http/Controllers`.

> Sources: `app/` directory structure

---

## Building and running the application

### Standard workflow (recommended)
```sh
# Build and start all services in the background.
# The entrypoint script handles key generation, migrations, and caches.
make up
```
The application will be available at `http://localhost:8000`.

### Manual setup steps (for understanding or debugging)
```sh
# These commands are run inside the app container (eldersg-app)
# Install PHP dependencies
make artisan ARGS="composer install"

# Install JS dependencies
make artisan ARGS="npm install"

# Generate application key
make artisan ARGS="key:generate"

# Run database migrations
make migrate

# Build frontend assets
make npm-build

# Start development server
make npm-dev
```

### Running tests
```sh
# Run the full PHPUnit test suite
make test
```

> Sources: `Makefile`, `docker-compose.yml`, `docker/entrypoint.sh`

---

## Key directories & files
| Path | Description |
|------|-------------|
| `app/Http/Controllers/` | Handles incoming HTTP requests. |
| `app/Http/Requests/` | Contains form request validation classes. |
| `app/Models/` | Eloquent models for database interaction. |
| `app/Services/` | Contains business logic and third-party integrations. |
| `config/` | Application configuration files. |
| `database/migrations/` | Database schema definitions. |
| `resources/views/` | Blade templates for the UI. |
| `routes/web.php` | Web routes for the application. |
| `tests/` | Application tests. |

> Sources: Project file structure.

---

## Core logic walkthrough: Newsletter Subscription
- **Route definition:** `POST /newsletter` in `routes/web.php`
- **Controller entrypoint:** `App\Http\Controllers\NewsletterController`
- **Validation:** `App\Http\Requests\NewsletterSubscriptionRequest`
- **Core business logic:** `App\Services\MailchimpService` handles the subscription attempt.
- **Database interaction:** No database interaction in this flow.
- **Post-event side-effects:** None.

> Sources: `routes/web.php`, `app/Http/Controllers/NewsletterController.php`, `app/Services/MailchimpService.php`

---

## Key data structures (models)
| Model | Table | Purpose |
|-------|-------|---------|
| `User` | `users` | Represents an application user. |
| `Program` | `programs` | Stores details about daycare programs. |
| `Testimonial` | `testimonials` | Stores customer testimonials. |

> Sources: `app/Models/`

---

## Background processing & job system
The application is configured to use Redis for queuing, but there are currently no background jobs implemented.

| Job Class | Purpose | Queue |
|-----------|---------|-------|
| N/A | N/A | N/A |

> Sources: `config/queue.php`

---

## Metrics & monitoring system
There is no dedicated metrics or monitoring system implemented in the application.

| Metrics Class | Purpose | Key Metrics |
|---------------|---------|-------------|
| N/A | N/A | N/A |

---

## Integrations (payments, notifications, etc.)
| Component | Purpose |
|-----------|---------|
| `App\Services\MailchimpService` | Subscribes users to a Mailchimp newsletter list. |
| `App\Services\BookingService` | (Not fully implemented) Intended to handle service bookings. |

> Sources: `app/Services/`
