let axiosPromise;

const configureAxiosDefaults = (instance) => {
    if (!instance?.defaults) {
        return instance;
    }

    instance.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    const token = document.head ? document.head.querySelector('meta[name="csrf-token"]') : null;

    if (token) {
        instance.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    }

    return instance;
};

window.loadAxios = async () => {
    if (typeof window.axios === 'function') {
        return configureAxiosDefaults(window.axios);
    }

    if (!axiosPromise) {
        axiosPromise = import('axios')
            .then((module) => {
                const axiosInstance = configureAxiosDefaults(module.default ?? module);
                window.axios = axiosInstance;
                return axiosInstance;
            })
            .catch((error) => {
                console.error('Failed to load axios module', error);
                return null;
            });
    }

    return axiosPromise;
};
