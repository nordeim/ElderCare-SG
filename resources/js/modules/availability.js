import Alpine from 'alpinejs';

const formatUpdatedLabel = (timestamp, locale = 'en-SG') => {
    if (!timestamp) {
        return '';
    }

    const date = new Date(timestamp);

    if (Number.isNaN(date.getTime())) {
        return '';
    }

    return new Intl.DateTimeFormat(locale, {
        weekday: 'short',
        hour: '2-digit',
        minute: '2-digit',
    }).format(date);
};

const createAvailabilityStore = () => ({
    status: 'loading',
    slots: [],
    updatedAt: null,
    isStale: true,
    fallbackMessage: '',
    fallbackUsed: false,
    error: null,
    isLoading: false,
    initialized: false,
    pollId: null,
    locale: document.documentElement.lang || 'en-SG',
    init() {
        if (this.initialized) {
            return;
        }

        this.initialized = true;
        this.fetch(true);
        this.startPolling();

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                this.fetch();
                this.startPolling();
            } else {
                this.stopPolling();
            }
        });
    },
    async fetch(force = false) {
        if (this.isLoading && !force) {
            return;
        }

        this.isLoading = true;
        this.error = null;

        try {
            const response = await fetch(`/api/availability${force ? '?refresh=1' : ''}`, {
                headers: {
                    Accept: 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error(`Availability request failed with status ${response.status}`);
            }

            const payload = await response.json();

            this.status = payload.status ?? 'ok';
            this.slots = Array.isArray(payload.slots) ? payload.slots : [];
            this.updatedAt = payload.updated_at ?? null;
            this.isStale = Boolean(payload.is_stale);
            this.fallbackMessage = payload.fallback_message ?? '';
            this.fallbackUsed = Boolean(payload.fallback_used);
            this.error = null;
        } catch (error) {
            this.error = error instanceof Error ? error.message : 'Availability request failed.';
        } finally {
            this.isLoading = false;
        }
    },
    startPolling() {
        this.stopPolling();
        this.pollId = window.setInterval(() => {
            this.fetch();
        }, 180000); // 3 minutes
    },
    stopPolling() {
        if (this.pollId) {
            window.clearInterval(this.pollId);
            this.pollId = null;
        }
    },
    get totalAvailable() {
        return this.slots.reduce((total, slot) => total + Number(slot.available ?? 0), 0);
    },
    get statusLevel() {
        if (this.isLoading && !this.initialized) {
            return 'loading';
        }

        if (this.error) {
            return 'error';
        }

        if (this.fallbackUsed || this.status !== 'ok') {
            return 'fallback';
        }

        const total = this.totalAvailable;

        if (total >= 12) {
            return 'high';
        }

        if (total >= 6) {
            return 'medium';
        }

        if (total >= 1) {
            return 'low';
        }

        return 'none';
    },
    refresh() {
        this.fetch(true);
    },
    get indicatorClass() {
        return `availability-indicator--${this.statusLevel}`;
    },
    get statusLabel() {
        switch (this.statusLevel) {
            case 'loading':
                return 'Checking availability…';
            case 'error':
                return 'Availability unavailable';
            case 'fallback':
                return 'Manual confirmation required';
            case 'high':
                return 'Ample visit slots available';
            case 'medium':
                return 'Limited slots available';
            case 'low':
                return 'Few slots remaining';
            case 'none':
            default:
                return 'Currently waitlisting';
        }
    },
    get statusDetail() {
        if (this.statusLevel === 'loading') {
            return 'Hang tight while we fetch the latest visit openings.';
        }

        if (this.statusLevel === 'error') {
            return "We're retrying the scheduling service.";
        }

        if (this.statusLevel === 'fallback') {
            return this.fallbackMessage || 'We will confirm availability within 24 hours.';
        }

        const formattedTime = formatUpdatedLabel(this.updatedAt, this.locale);
        const daysCovered = this.slots.length;
        const slotsCopy = this.totalAvailable === 1 ? 'slot' : 'slots';

        if (!formattedTime) {
            return `${this.totalAvailable} ${slotsCopy} in the next ${daysCovered} days.`;
        }

        return `${this.totalAvailable} ${slotsCopy} in the next ${daysCovered} days — updated ${formattedTime}`;
    },
    get isHealthy() {
        return ['high', 'medium'].includes(this.statusLevel);
    },
});
document.addEventListener('alpine:init', () => {
    if (!Alpine.store('availability')) {
        const store = createAvailabilityStore();
        Alpine.store('availability', store);
        store.init();
    }
});
