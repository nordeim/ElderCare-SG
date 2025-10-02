const HERO_SELECTOR = '[data-hero]';
const VIDEO_SELECTOR = '[data-hero-video]';
const INTERSECTION_THRESHOLD = 0.3;

const parseHeroSources = (video) => {
    const raw = video?.dataset?.heroSources;

    if (!raw) {
        return [];
    }

    try {
        const parsed = JSON.parse(raw);
        return Array.isArray(parsed) ? parsed : [];
    } catch (error) {
        console.warn('Failed to parse hero video sources', error);
        return [];
    }
};

const ensureHeroSources = (video) => {
    if (!video || video.dataset.heroSourcesAppended === 'true') {
        return;
    }

    const sources = parseHeroSources(video);

    if (!sources.length) {
        return;
    }

    sources.forEach((sourceConfig) => {
        if (!sourceConfig?.src) {
            return;
        }

        const sourceElement = document.createElement('source');
        sourceElement.src = sourceConfig.src;

        if (sourceConfig.type) {
            sourceElement.type = sourceConfig.type;
        }

        video.appendChild(sourceElement);
    });

    video.dataset.heroSourcesAppended = 'true';
};

const playHeroVideo = (video) => {
    if (!video) {
        return;
    }

    ensureHeroSources(video);

    if (!video.dataset.heroLoaded) {
        video.dataset.heroLoaded = 'true';
        video.preload = 'auto';
        video.load();
    }

    const playPromise = video.play();

    if (playPromise?.catch) {
        playPromise.catch(() => {
            // Autoplay might still be blocked in some contexts; ignore silently.
        });
    }
};

const pauseHeroVideo = (video) => {
    if (!video) {
        return;
    }

    if (!video.paused) {
        video.pause();
    }
};

const observeHeroSection = (section, video) => {
    if (!('IntersectionObserver' in window)) {
        playHeroVideo(video);
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                playHeroVideo(video);
            } else {
                pauseHeroVideo(video);
            }
        });
    }, {
        threshold: INTERSECTION_THRESHOLD,
    });

    observer.observe(section);
};

const initHeroMedia = () => {
    const heroSections = document.querySelectorAll(HERO_SELECTOR);

    heroSections.forEach((section) => {
        const video = section.querySelector(VIDEO_SELECTOR);

        if (!video) {
            return;
        }

        if (video.readyState >= HTMLMediaElement.HAVE_METADATA) {
            observeHeroSection(section, video);
            return;
        }

        video.addEventListener('loadedmetadata', () => {
            observeHeroSection(section, video);
        }, { once: true });

        // Ensure metadata request kicks in when intersection observer registers.
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHeroMedia);
} else {
    initHeroMedia();
}
