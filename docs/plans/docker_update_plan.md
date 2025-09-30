# Docker Configuration Update Plan

## Overview
This plan captures the planned changes to the Docker and Sail-based tooling in `temp-laravel/` so the team can track tasks before implementation.

## Task Matrix
- **Status Legend**: ☐ Pending · ☐ In Progress · ☑ Done (update as work progresses)

| File / Area | Task | Status | Notes |
|-------------|------|--------|-------|
| `docker-compose.yml` | Align DB defaults with current `.env` (`laravel` + `sail`), confirm service dependencies, review healthcheck endpoint | ☐ Pending | Ensure Vite port tooling documented; optionally run via PHP-FPM+Nginx even in dev |
| `docker-compose.override.yml` | Maintain bind-mount overrides; consider optional Vite service or documentation | ☐ Pending | Avoid duplicate volume definitions |
| `docker-compose-production.yml` | Verify env vars, caching strategy, logging volumes, optional TLS guidance | ☐ Pending | Keep Mailhog commented for staging only |
| `Dockerfile` | Consider PHP 8.3 bump, optional multi-stage build, guard `npm run build`, retain vendor cache | ☐ Pending | Ensure builder handles readiness marker permissions |
| `Makefile` | Sync compose command with actual filename, add QA targets (`axe`, `lighthouse`) | ☐ Pending | Clarify usage of `docker compose` vs `compose.yaml` |
| `.dockerignore` | Confirm exclusions, add new reports if needed | ☐ Pending | No change expected; re-validate after updates |
| `.env.docker` | Replace DB creds with `laravel`/`sail`, add analytics, Mailchimp, booking placeholders | ☐ Pending | Document secret management for production |
| `docker/app-healthcheck.sh` | Point to configurable host/route (`HEALTHCHECK_URL` default `http://app/healthz`) | ☐ Pending | Ensure Laravel publishes `/healthz` |
| `docker/entrypoint.sh` | Add optional seeding toggle, guard build steps, improve migration logging | ☐ Pending | Keep retries for DB/Redis checks |
| `docker/nginx.conf` | Add gzip/security headers, caching for static assets, forward proxy headers | ☐ Pending | Confirm upstream matches container names |
| `docker/composer` | Validate composer version, fetch latest release periodically | ☐ Pending | Optional checksum verification |

## Dependencies & Open Questions
- Provide `/healthz` route/controller in Laravel to satisfy health checks.
- Decide between `artisan serve` vs PHP-FPM + Nginx for local dev parity.
- Coordinate with DevOps on TLS termination and secret storage for production deployments.

## Next Steps
1. Review plan with stakeholders (DevOps + engineering).
2. Execute updates in priority order (health checks, env alignment, Dockerfile tweaks, Makefile scripts).
3. Test workflows: `docker compose up`, `launch_laravel_dev_server.sh`, `npm run lint:accessibility`, `npm run lighthouse`.
4. Update documentation (README, QA checklist) with new commands and configuration guidance.
