const createAnalyticsEmitter = (namespace = 'tour') => {
    if (window.eldercareAnalytics?.createEmitter) {
        return window.eldercareAnalytics.createEmitter(namespace);
    }

    if (window.eldercareAnalytics?.emit) {
        return (event, detail = {}) => {
            window.eldercareAnalytics.emit(`${namespace}.${event}`, detail);
        };
    }

    return () => {};
};

const normalizeHotspots = (hotspots) => {
    if (!Array.isArray(hotspots)) {
        return [];
    }

    return hotspots.filter((item) => item && item.id);
};

const tourComponent = (options = {}) => {
    const config = {
        hotspots: [],
        transcriptUrl: null,
        analyticsNamespace: 'tour',
        ...options,
    };

    return {
        isOpen: false,
        hotspots: normalizeHotspots(config.hotspots),
        transcriptUrl: config.transcriptUrl,
        analyticsNamespace: config.analyticsNamespace ?? 'tour',
        activeHotspotId: null,
        dialogLabelId: `tour-${Math.random().toString(36).slice(2)}-label`,
        visitedHotspotIds: new Set(),
        completeEmitted: false,
        lastFocusedElement: null,
        analyticsEmitter: createAnalyticsEmitter(config.analyticsNamespace ?? 'tour'),

        init() {
            if (this.hotspots.length > 0) {
                this.activeHotspotId = this.hotspots[0].id;
            }
        },

        get activeHotspot() {
            return this.hotspots.find((hotspot) => hotspot.id === this.activeHotspotId) ?? null;
        },

        open() {
            if (this.isOpen) {
                return;
            }

            if (!this.activeHotspotId && this.hotspots.length > 0) {
                this.activeHotspotId = this.hotspots[0].id;
            }

            this.lastFocusedElement = document.activeElement instanceof HTMLElement ? document.activeElement : null;
            this.isOpen = true;
            document.body.style.setProperty('overflow', 'hidden');

            if (this.activeHotspotId) {
                this.visitedHotspotIds.add(this.activeHotspotId);
            }

            this.analyticsEmitter('open', {
                hotspotId: this.activeHotspotId,
                totalHotspots: this.hotspots.length,
            });

            this.$nextTick(() => {
                const buttons = this.getHotspotButtons();
                if (buttons.length > 0) {
                    buttons[0].focus();
                }
            });
        },

        close() {
            if (!this.isOpen) {
                return;
            }

            this.isOpen = false;
            document.body.style.removeProperty('overflow');

            this.$nextTick(() => {
                if (this.lastFocusedElement) {
                    this.lastFocusedElement.focus();
                }
            });
        },

        selectHotspot(id) {
            if (!id || this.activeHotspotId === id) {
                return;
            }

            this.activeHotspotId = id;
            this.visitedHotspotIds.add(id);

            this.analyticsEmitter('hotspot', {
                hotspotId: id,
                totalVisited: this.visitedHotspotIds.size,
                totalHotspots: this.hotspots.length,
            });

            if (!this.completeEmitted && this.visitedHotspotIds.size === this.hotspots.length && this.hotspots.length > 0) {
                this.completeEmitted = true;
                this.analyticsEmitter('complete', {
                    totalHotspots: this.hotspots.length,
                });
            }
        },

        navigateHotspots(delta) {
            if (!this.hotspots.length) {
                return;
            }

            const currentIndex = this.hotspots.findIndex((hotspot) => hotspot.id === this.activeHotspotId);
            const nextIndex = (currentIndex + delta + this.hotspots.length) % this.hotspots.length;
            const nextHotspot = this.hotspots[nextIndex];

            if (!nextHotspot) {
                return;
            }

            this.activeHotspotId = nextHotspot.id;
            this.visitedHotspotIds.add(nextHotspot.id);

            this.analyticsEmitter('hotspot', {
                hotspotId: nextHotspot.id,
                totalVisited: this.visitedHotspotIds.size,
                totalHotspots: this.hotspots.length,
            });

            if (!this.completeEmitted && this.visitedHotspotIds.size === this.hotspots.length && this.hotspots.length > 0) {
                this.completeEmitted = true;
                this.analyticsEmitter('complete', {
                    totalHotspots: this.hotspots.length,
                });
            }

            this.$nextTick(() => {
                const buttons = this.getHotspotButtons();
                const activeButton = buttons.find((button) => button.dataset.hotspotId === nextHotspot.id);

                if (activeButton) {
                    activeButton.focus();
                }
            });
        },

        handleHotspotKeydown(event) {
            if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
                event.preventDefault();
                this.navigateHotspots(1);
            }

            if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
                event.preventDefault();
                this.navigateHotspots(-1);
            }
        },

        getHotspotButtons() {
            return Array.from(this.$root.querySelectorAll('[data-hotspot-button]'));
        },
    };
};

window.tourComponent = tourComponent;

export { tourComponent };
