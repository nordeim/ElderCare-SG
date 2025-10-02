const dispatchBrowserEvent = (name, detail = {}) => {
    if (typeof window.dispatchEvent === 'function') {
        window.dispatchEvent(new CustomEvent(name, { detail }));
    }
};

const emittedEvents = new Set();
const shouldThrottle = (name, detail) => {
    const dedupeKey = `${name}:${JSON.stringify(detail ?? {})}`;

    if (emittedEvents.has(dedupeKey)) {
        return true;
    }

    emittedEvents.add(dedupeKey);

    // Clear entry after a short window to allow future emissions.
    setTimeout(() => emittedEvents.delete(dedupeKey), 10_000);

    return false;
};

const emit = (name, detail = {}) => {
    if (!name || shouldThrottle(name, detail)) {
        return;
    }

    dispatchBrowserEvent(name, detail);

    if (typeof window.plausible === 'function') {
        const goals = window.__eldercareAnalyticsGoals ?? {};
        const mappedGoal = goals[name] ?? name;

        window.plausible(mappedGoal, { props: detail });
    }
};

const createEmitter = (namespace = 'app') => {
    return (event, detail = {}) => emit(`${namespace}.${event}`, detail);
};

export { emit, createEmitter };

if (!window.eldercareAnalytics) {
    window.eldercareAnalytics = { emit, createEmitter };
}

const flushAnalyticsQueue = () => {
    if (!Array.isArray(window.__eldercareAnalyticsQueue)) {
        return;
    }

    window.__eldercareAnalyticsQueue.forEach((event) => {
        if (!event?.name) {
            return;
        }

        emit(event.name, event.detail ?? {});
    });

    window.__eldercareAnalyticsQueue = [];
};

flushAnalyticsQueue();

const captureError = (type, errorEvent) => {
    const detail = {
        message: errorEvent?.message ?? errorEvent?.reason?.message ?? 'Unknown error',
        stack: errorEvent?.error?.stack ?? errorEvent?.reason?.stack ?? null,
        source: errorEvent?.filename ?? null,
        type,
    };

    emit('app.error', detail);
};

window.addEventListener('error', (event) => {
    captureError('error', event);
});

window.addEventListener('unhandledrejection', (event) => {
    captureError('unhandledrejection', event);
});
