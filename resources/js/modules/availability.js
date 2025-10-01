import Alpine from 'alpinejs';

const getAvailabilityMessages = () => window.eldercare?.availabilityMessages ?? null;

const resolveFormatterLocale = (lang) => {
    if (!lang) {
        return 'en-SG';
    }

    const normalized = lang.toLowerCase();

    switch (normalized) {
        case 'zh':
        case 'zh-hans':
        case 'zh-sg':
            return 'zh-SG';
        case 'en':
        case 'en-sg':
        default:
            return 'en-SG';
    }
};

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
    locale: resolveFormatterLocale(document.documentElement.lang || 'en'),
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
        const messages = getAvailabilityMessages();

        switch (this.statusLevel) {
            case 'loading':
                return messages?.status?.loading ?? 'Checking availability…';
            case 'error':
                return messages?.status?.error ?? 'Availability unavailable';
            case 'fallback':
                return messages?.status?.fallback ?? 'Manual confirmation required';
            case 'high':
                return messages?.status?.high ?? 'Ample visit slots available';
            case 'medium':
                return messages?.status?.medium ?? 'Limited slots available';
            case 'low':
                return messages?.status?.low ?? 'Few slots remaining';
            case 'none':
            default:
                return messages?.status?.none ?? 'Currently waitlisting';
        }
    },
    get statusDetail() {
        const messages = getAvailabilityMessages();

        if (this.statusLevel === 'loading') {
            return messages?.detail?.loading ?? 'Hang tight while we fetch the latest visit openings.';
        }

        if (this.statusLevel === 'error') {
            return messages?.detail?.error ?? "We're retrying the scheduling service.";
        }

        if (this.statusLevel === 'fallback') {
            const fallbackCopy = messages?.detail?.fallback ?? 'We will confirm availability within 24 hours.';
            return this.fallbackMessage || fallbackCopy;
        }

        const formattedTime = formatUpdatedLabel(this.updatedAt, this.locale);
        const daysCovered = this.slots.length;
        const slotSingle = messages?.detail?.slot_single ?? 'slot';
        const slotPlural = messages?.detail?.slot_plural ?? 'slots';
        const slotsCopy = this.totalAvailable === 1 ? slotSingle : slotPlural;

        if (!formattedTime) {
            const template = messages?.detail?.summary ?? ':total :slot_word in the next :days days.';
            return template
                .replace(':total', this.totalAvailable)
                .replace(':slot_word', slotsCopy)
                .replace(':days', daysCovered);
        }

        const template = messages?.detail?.summary_with_time ?? ':total :slot_word in the next :days days — updated :time';

        return template
            .replace(':total', this.totalAvailable)
            .replace(':slot_word', slotsCopy)
            .replace(':days', daysCovered)
            .replace(':time', formattedTime);
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
