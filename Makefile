.DEFAULT_GOAL := help

APP := eldersg-app
# Compose command (override with COMPOSE="docker compose -f docker-compose.yml" if needed)
COMPOSE ?= docker compose

.PHONY: up down logs restart migrate migrate-fresh migrate-refresh migrate-rollback migrate-status seed tinker artisan bash health cache clear test test-ci lint npm-dev npm-build npm-ci env-check key-check reset prune help

up: ## Build and start containers
	$(COMPOSE) up --build -d

down: ## Stop and remove containers and volumes
	$(COMPOSE) down -v

restart: ## Restart just the app container
	docker restart $(APP)

logs: ## Tail app container logs
	docker logs -f $(APP)

migrate: ## Run database migrations
	docker exec $(APP) php artisan migrate

migrate-fresh: ## Drop all tables, re-run migrations & seed
	docker exec $(APP) php artisan migrate:fresh --seed

migrate-refresh: ## Rollback and re-run all migrations
	docker exec $(APP) php artisan migrate:refresh

migrate-rollback: ## Rollback last migration batch
	docker exec $(APP) php artisan migrate:rollback

migrate-status: ## Show migration status
	docker exec $(APP) php artisan migrate:status

seed: ## Run Laravel seeders
	docker exec $(APP) php artisan db:seed

tinker: ## Open Laravel Tinker shell
	docker exec -it $(APP) php artisan tinker

artisan: ## Run arbitrary artisan command (set ARGS variable)
ifdef ARGS
	docker exec -it $(APP) php artisan $(ARGS)
else
	@echo "Usage: make artisan ARGS=\"<command>\""
endif

bash: ## Open shell in app container
	docker exec -it $(APP) bash

health: ## Check health endpoint
	curl -fsS http://localhost:8000/healthz

cache: ## Build Laravel caches
	docker exec $(APP) php artisan config:cache && \
	docker exec $(APP) php artisan route:cache && \
	docker exec $(APP) php artisan view:cache

clear: ## Clear Laravel caches
	docker exec $(APP) php artisan config:clear || true && \
	docker exec $(APP) php artisan route:clear || true && \
	docker exec $(APP) php artisan view:clear || true

test: ## Run PHPUnit / Laravel tests
	$(COMPOSE) exec $(APP) php artisan test

test-ci: ## Run tests under non-interactive / CI mode
	$(COMPOSE) exec $(APP) php artisan test --env=testing --no-interaction

lint: ## Run Laravel Pint or PHP-CS-Fixer
	docker exec $(APP) ./vendor/bin/pint

qa-axe: ## Run accessibility audit (axe)
	$(COMPOSE) exec $(APP) npm run lint:accessibility

qa-lighthouse: ## Run Lighthouse CI audit
	$(COMPOSE) exec $(APP) npm run lighthouse

npm-dev: ## Start Vite development server (CTRL+C to stop)
	$(COMPOSE) exec -it $(APP) npm run dev

npm-build: ## Build frontend assets for production
	$(COMPOSE) exec $(APP) npm run build

npm-ci: ## Install node dependencies inside container
	$(COMPOSE) exec $(APP) npm ci
env-check: ## Validate required .env variables
	@grep -E '^APP_KEY=|^DB_HOST=|^DB_DATABASE=|^DB_USERNAME=|^DB_PASSWORD=' .env || echo "Missing required .env vars"

key-check: ## Ensure APP_KEY exists
	@grep -E '^APP_KEY=.+$$' .env || echo "APP_KEY missing or empty"

reset: ## Stop containers, remove volumes, prune
	$(COMPOSE) down -v && docker volume prune -f

prune: ## Remove dangling images and volumes
	docker system prune -f && docker volume prune -f

help: ## Show all available commands
	@echo "Usage: make <target>  (you can also do make VAR=val to override)" && \
	echo && \
	grep -E '^[a-zA-Z0-9_-]+:.*?## ' $(MAKEFILE_LIST) | \
	awk 'BEGIN {FS = ":.*?## "}; { printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2 }'
