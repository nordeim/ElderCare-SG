let emblaModulePromise;

window.initEmbla = (element, options = {}) => {
    if (!element) {
        return;
    }

    if (!emblaModulePromise) {
        emblaModulePromise = import('embla-carousel').then((module) => module.default);
    }

    const defaultOptions = {
        loop: true,
        align: 'center',
        skipSnaps: false,
    };

    emblaModulePromise
        .then((EmblaCarousel) => {
            EmblaCarousel(element, { ...defaultOptions, ...options });
        })
        .catch((error) => {
            console.error('Failed to initialize Embla carousel', error);
        });
};
