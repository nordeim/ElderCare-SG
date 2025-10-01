import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        visualizer({
            filename: 'stats.html',
            open: false,
            gzipSize: true,
            brotliSize: true,
        }),
    ],
});
