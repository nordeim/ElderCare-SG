# AI Agent Context: Elderly Daycare Platform

**Purpose:**
This document is the source of truth for AI agents interacting with this codebase. It provides a concise, factual overview of the project's architecture, key files, and development conventions.

---

## Project Overview

This project is a web application for an **Elderly Daycare Platform**, designed to provide a seamless and reassuring digital experience for families in Singapore seeking trusted elderly daycare services.

The architecture is a standard **Laravel Model-View-Controller (MVC)** pattern with a dedicated **Service Layer** for business logic. The application is designed to run in a Docker containerized environment for development and production consistency.

### Technical Stack

| Component | Technology | Version / Details | Confirmation File |
| :--- | :--- | :--- | :--- |
| Backend Framework | Laravel | `~12.0` | `composer.json` |
| Language | PHP | `8.3` | `Dockerfile` |
| Database | MariaDB | `10.11` | `docker-compose.yml` |
| Caching & Queues | Redis | `7.4` | `docker-compose.yml` |
| Frontend Stack | Blade, TailwindCSS, Alpine.js | Vite build tool | `package.json`, `vite.config.js` |
| Dev Environment | Docker | Docker Compose | `docker-compose.yml`, `Makefile` |

---

## Building and Running the Application

The project uses a Docker-based development environment managed by Docker Compose and a `Makefile` for convenience. The `docker/entrypoint.sh` script automates setup tasks (e.g., migrations, cache building) on container startup.

### 1. First-Time Setup

The project is ready to run out-of-the-box. The Docker environment is pre-configured using the `.env.docker` file.

```sh
# Build and start all services in the background
make up
```

After running `make up`, the application will be available at `http://localhost:8000` and the Mailhog UI at `http://localhost:8025`.

### 2. Standard Workflow

The `Makefile` provides shortcuts for most common operations.

```sh
# Start all services (builds if necessary)
make up

# Stop and remove all containers and volumes
make down

# Restart the application container
make restart

# View application logs in real-time
make logs

# Open a shell inside the application container
make bash

# Run database migrations
make migrate

# Drop all tables and re-run all migrations and seeds
make migrate-fresh

# Run tests
make test
```

---

## Development Conventions

### Architecture
*   **Adhere to the existing MVC + Service Layer architecture.**
*   Controllers (`app/Http/Controllers`) should be lean and delegate complex business logic to Service classes.
*   Place all new business logic and third-party integrations within the `app/Services` directory.
*   Use Form Requests (found in `app/Http/Requests/`) for validating incoming request data.

### Key Implemented Features
*   **Availability Service:** Provides real-time service availability information via the `AvailabilityService`, which is built with a provider model and includes caching.
*   **Immersive Virtual Tour:** An interactive tour experience driven by a static JSON data source (`resources/data/tour_hotspots.json`) and a dynamic staff carousel populated from the database via the `StaffSeeder`.
*   **Decision Support Utilities:** A suite of tools to help users, including a dynamic FAQ system and a resource hub for downloadable guides, powered by the `Faq` and `Resource` models.

### Core Logic Example: Virtual Tour
*   **Data Source:** Hotspot locations, text, and media are defined in `resources/data/tour_hotspots.json`.
*   **Database Seeding:** The accompanying staff carousel is populated from the `staff` table, which is seeded by `database/seeders/StaffSeeder.php`.
*   **Frontend Logic:** The entire interactive experience (modal, hotspots, state management) is handled on the frontend by an Alpine.js component.

### Key Directories
| Path | Description |
| :--- | :--- |
| `app/Http/Controllers/` | Handles incoming HTTP requests. |
| `app/Models/` | Eloquent models for database interaction (`User`, `Program`, `Staff`, `Faq`, etc.). |
| `app/Services/` | Contains business logic and third-party integrations. |
| `config/` | Application configuration files. |
| `database/migrations/` | The source of truth for the database schema. |
| `resources/views/` | Blade templates that compose the UI. |
| `routes/web.php` | Defines all web-facing application routes. |
| `tests/` | Application tests. |