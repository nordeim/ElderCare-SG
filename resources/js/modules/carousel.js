import EmblaCarousel from 'embla-carousel';

window.initEmbla = (element, options = {}) => {
    const defaultOptions = {
        loop: true,
        align: 'center',
        skipSnaps: false,
    };

    const embla = EmblaCarousel(element, { ...defaultOptions, ...options });

    return embla;
};
