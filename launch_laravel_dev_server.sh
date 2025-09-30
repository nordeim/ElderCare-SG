#!/usr/bin/env bash
set -euo pipefail

HOST="0.0.0.0"
PORT=8000
SERVER_PID=""
QA_FAILED=0

INSTALL_DEPS=false
RUN_MIGRATIONS=false
RUN_QA=true
TAIL_LOGS=false

usage() {
    cat <<'USAGE'
Usage: ./launch_laravel_dev_server.sh [options]

Options:
  --install          Run composer/npm install before launching the server.
  --seed             Execute php artisan migrate --seed before launch.
  --skip-qa          Skip automated QA scripts (axe & Lighthouse).
  --logs             Display the latest Laravel log entries after QA scripts.
  --port <number>    Override the default port (8000).
  --help             Show this help message.
USAGE
}

log() {
    local level message
    level="$1"
    shift
    message="$*"
    printf '[%s] %-5s %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$level" "$message"
}

cleanup() {
    if [[ -n "$SERVER_PID" ]] && ps -p "$SERVER_PID" > /dev/null 2>&1; then
        log INFO "Stopping Laravel dev server (PID: $SERVER_PID)."
        kill "$SERVER_PID" >/dev/null 2>&1 || true
    fi
}

trap cleanup EXIT INT TERM

check_binary() {
    local binary="$1"
    if ! command -v "$binary" >/dev/null 2>&1; then
        log ERROR "Required command '$binary' is not available."
        exit 1
    fi
}

ensure_port_free() {
    if command -v lsof >/dev/null 2>&1; then
        if lsof -i TCP:"$PORT" -sTCP:LISTEN >/dev/null 2>&1; then
            log ERROR "Port $PORT is already in use. Stop the conflicting process or choose another port with --port."
            exit 1
        fi
    elif command -v ss >/dev/null 2>&1; then
        if ss -ltn | awk '{print $4}' | grep -q ":$PORT$"; then
            log ERROR "Port $PORT is already in use. Stop the conflicting process or choose another port with --port."
            exit 1
        fi
    else
        log WARN "Skipping port availability check (lsof/ss not found)."
    fi
}

install_dependencies() {
    log INFO "Running composer install..."
    composer install --no-interaction --prefer-dist
    log INFO "Running npm install..."
    npm install
}

run_migrations() {
    log INFO "Running php artisan migrate --seed..."
    php artisan migrate --seed
}

start_server() {
    log INFO "Starting Laravel dev server on http://localhost:$PORT ..."
    php artisan serve --host="$HOST" --port="$PORT" > storage/logs/dev-server.log 2>&1 &
    SERVER_PID=$!
    log INFO "Laravel dev server PID: $SERVER_PID (logs: storage/logs/dev-server.log)."
}

wait_for_server() {
    if ! command -v curl >/dev/null 2>&1; then
        log WARN "curl not found; skipping readiness check."
        return
    fi

    log INFO "Waiting for server to respond..."
    for _ in {1..30}; do
        if curl --silent --head --fail "http://localhost:$PORT" >/dev/null; then
            log INFO "Server is responding at http://localhost:$PORT."
            return
        fi
        sleep 1
    done
    log WARN "Server did not respond within 30 seconds. Continue with caution."
}

run_accessibility_checks() {
    log INFO "Running accessibility audit (npm run lint:accessibility)..."
    if ! npm run lint:accessibility; then
        log WARN "Accessibility audit failed; review output above."
        QA_FAILED=1
    else
        log INFO "Accessibility audit completed."
    fi
}

run_lighthouse() {
    log INFO "Running Lighthouse CI audit (npm run lighthouse)..."
    if ! npm run lighthouse; then
        log WARN "Lighthouse audit failed; review output above."
        QA_FAILED=1
    else
        log INFO "Lighthouse audit completed. Reports saved under storage/app/lighthouse."
    fi
}

show_manual_steps() {
    cat <<EOM
------------------------------------------------------------------------
Manual QA Checklist
- Open the homepage: http://localhost:$PORT
- Confirm seeded programs/testimonials render correctly.
- Submit the newsletter form with a test email; expect success or fallback
  banner and confirm log entries in storage/logs/laravel.log when keys absent.
------------------------------------------------------------------------
EOM
}

show_logs() {
    if [[ -f storage/logs/laravel.log ]]; then
        log INFO "Showing latest Laravel log entries (tail -n 40)..."
        tail -n 40 storage/logs/laravel.log
    else
        log WARN "Log file storage/logs/laravel.log not found."
    fi
}

# Parse arguments
while [[ $# -gt 0 ]]; do
    case "$1" in
        --install)
            INSTALL_DEPS=true
            shift
            ;;
        --seed)
            RUN_MIGRATIONS=true
            shift
            ;;
        --skip-qa)
            RUN_QA=false
            shift
            ;;
        --logs)
            TAIL_LOGS=true
            shift
            ;;
        --port)
            PORT="$2"
            shift 2
            ;;
        --help)
            usage
            exit 0
            ;;
        *)
            log ERROR "Unknown option: $1"
            usage
            exit 1
            ;;
    esac
done

# Pre-flight checks
check_binary php
check_binary composer
check_binary npm
ensure_port_free

if [[ "$INSTALL_DEPS" == true ]]; then
    install_dependencies
fi

if [[ "$RUN_MIGRATIONS" == true ]]; then
    run_migrations
fi

start_server
wait_for_server
show_manual_steps

if [[ "$RUN_QA" == true ]]; then
    run_accessibility_checks
    run_lighthouse
else
    log INFO "Skipping automated QA scripts per --skip-qa flag."
fi

if [[ "$TAIL_LOGS" == true ]]; then
    show_logs
fi

if [[ "$QA_FAILED" -ne 0 ]]; then
    log WARN "One or more QA checks failed. Review logs above."
else
    log INFO "QA checks completed successfully."
fi

log INFO "Dev server is running (PID: $SERVER_PID). Press Ctrl+C to exit when finished."

wait "$SERVER_PID"
