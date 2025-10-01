const dispatchBrowserEvent = (name, detail = {}) => {
    if (typeof window.dispatchEvent === 'function') {
        window.dispatchEvent(new CustomEvent(name, { detail }));
    }
};

const emit = (name, detail = {}) => {
    dispatchBrowserEvent(name, detail);

    if (typeof window.plausible === 'function') {
        window.plausible(name, { props: detail });
    }
};

const createEmitter = (namespace = 'app') => {
    return (event, detail = {}) => emit(`${namespace}.${event}`, detail);
};

export { emit, createEmitter };

if (!window.eldercareAnalytics) {
    window.eldercareAnalytics = { emit, createEmitter };
}
