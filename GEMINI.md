# GEMINI Agent Context: ElderCare SG Project

This document provides a comprehensive overview for AI agents to understand and interact with the ElderCare SG codebase.

## 1. Project Overview

**Purpose:** ElderCare SG is a modern, design-first web platform for families in Singapore seeking elderly daycare services. The project emphasizes a compassionate, reliable, and accessible user experience.

**Technologies:**
*   **Backend:** Laravel 12 (PHP 8.3)
*   **Frontend:** Blade Templates with TailwindCSS and Alpine.js
*   **Database:** MariaDB 10.11
*   **Caching & Queues:** Redis
*   **Development Environment:** Docker

**Architecture:** The project follows a standard **MVC (Model-View-Controller) pattern with a dedicated Service layer**. Business logic and third-party integrations are housed in `app/Services`, keeping controllers lean. The entire development environment is containerized with Docker and managed via a `Makefile`.

## 2. Building and Running

The project is designed to be run easily within its Docker environment.

### **Primary Workflow (Recommended)**

To build and start all services (app, database, Redis), run the following command. This command also handles initial setup like database migrations and key generation.

```sh
make up
```
The application will be available at `http://localhost:8000`.

### **Running Tests**

To execute the full PHPUnit test suite, use the following command:

```sh
make test
```

## 3. Development Conventions

*   **Service Layer:** All new business logic should be placed in service classes within the `app/Services` directory.
*   **Docker-First:** All development and execution commands (including `artisan` and `npm`) should be run through the `Makefile` targets to ensure consistency with the containerized environment (e.g., `make migrate`, `make bash`).
*   **Documentation-Driven:** Development is guided by detailed planning and status documents located in the `docs/` directory. These documents outline the project's roadmap, feature implementation plans, and current status.
*   **Accessibility Focus:** The project adheres to WCAG 2.1 AA standards, and accessibility is a core consideration for all UI components.
*   **Data Seeding:** The database is populated with sample data via seeders (e.g., `StaffSeeder`, `FaqSeeder`), which are essential for viewing implemented features like the Virtual Tour and FAQ section.