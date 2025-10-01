import Alpine from 'alpinejs';

const defaultState = () => ({
    segmentKey: null,
    segment: null,
    summary: null,
    updatedAt: null,
    fallbackLabel: 'Book a visit',
    fallbackHref: '#booking',
});

const store = {
    ...defaultState(),
    setFallback(summary) {
        if (!summary) {
            return;
        }

        if (summary.fallbackLabel) {
            this.fallbackLabel = summary.fallbackLabel;
        }

        if (summary.fallbackCta) {
            this.fallbackHref = summary.fallbackCta;
        }

        this.summary = summary;
    },
    apply(detail) {
        if (!detail || !detail.segmentKey || !detail.segment) {
            this.reset();
            return;
        }

        this.segmentKey = detail.segmentKey;
        this.segment = detail.segment;
        if (detail.summary) {
            this.setFallback(detail.summary);
        }
        this.updatedAt = new Date().toISOString();
    },
    reset() {
        const fallbackLabel = this.fallbackLabel;
        const fallbackHref = this.fallbackHref;

        Object.assign(this, defaultState());
        this.fallbackLabel = fallbackLabel;
        this.fallbackHref = fallbackHref;
    },
    get hasRecommendation() {
        return Boolean(this.segmentKey && this.segment);
    },
    get primaryCtaLabel() {
        return this.segment?.cta_label ?? this.summary?.fallbackLabel ?? this.fallbackLabel;
    },
    get primaryCtaHref() {
        return this.segment?.cta_href ?? this.summary?.fallbackCta ?? this.fallbackHref;
    },
    get programs() {
        return Array.isArray(this.segment?.programs) ? this.segment.programs : [];
    },
    get highlights() {
        return Array.isArray(this.segment?.highlights) ? this.segment.highlights : [];
    },
};

const registerStore = () => {
    if (!Alpine.store('assessmentRecommendation')) {
        Alpine.store('assessmentRecommendation', store);
    }

    const applyDetail = (detail) => {
        if (!detail) {
            return;
        }

        if (detail.summary) {
            Alpine.store('assessmentRecommendation').setFallback(detail.summary);
        }

        if (detail.segmentKey && detail.segment) {
            Alpine.store('assessmentRecommendation').apply(detail);
        }
    };

    const resetStore = () => {
        Alpine.store('assessmentRecommendation').reset();
    };

    window.addEventListener('assessment.complete', (event) => applyDetail(event.detail));
    window.addEventListener('assessment.skip', (event) => applyDetail(event.detail));
    window.addEventListener('assessment.submitted', (event) => applyDetail(event.detail));
    window.addEventListener('assessment.restart', resetStore);
    window.addEventListener('assessment.close', resetStore);
    window.addEventListener('assessment.open', resetStore);
};

if (typeof window !== 'undefined') {
    document.addEventListener('alpine:init', registerStore, { once: true });
}
