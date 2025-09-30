# AI Agent Context: Elderly Daycare Platform

**Purpose:**
This document is the source of truth for AI agents interacting with this codebase. It provides a concise, factual overview of the project's architecture, key files, and development conventions.

---

## Project Overview

This project is a web application for an **Elderly Daycare Platform**. It allows for booking services, managing clients, and handling related administrative tasks.

The architecture is a standard **Laravel Model-View-Controller (MVC)** pattern. The application is designed to run in a Docker containerized environment for development and production consistency.

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

The project is ready to run out-of-the-box. The Docker environment is pre-configured using the `.env.docker` file. On the first run, an `.env` file will be generated automatically inside the application container.

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

# Drop all tables and re-run all migrations
make migrate-fresh

# Run tests
make test

# Clear all application caches
make clear

# View all available commands
make help
```

---

## Development Conventions

### Architecture
*   **Adhere to the existing MVC architecture.**
*   Controllers (`app/Http/Controllers`) should be lean and primarily responsible for handling HTTP requests and delegating to Services.
*   Use Form Requests (e.g., `app/Http/Requests/NewsletterSubscriptionRequest.php`) for validating incoming request data.

### Key Directories
| Path | Description |
| :--- | :--- |
| `app/Http/Controllers/` | Controllers organized by area. |
| `app/Models/` | All Eloquent models. |
| `app/Services/` | Domain-specific business logic services. |
| `config/` | Contains standard Laravel config. |
| `database/migrations/` | The source of truth for the database schema. |
| `routes/web.php` | Defines all web-facing application routes. |
| `resources/views/` | Blade templates for the frontend. |
| `tests/` | Application tests. |