import { defineConfig } from 'vitest/config';

export default defineConfig({
    test: {
        environment: 'jsdom',
        globals: true,
        setupFiles: ['resources/js/tests/setupTests.ts'],
        include: ['resources/js/tests/**/*.spec.ts'],
        coverage: {
            reporter: ['text', 'lcov'],
            all: true,
            thresholds: {
                lines: 80,
                statements: 80,
                branches: 70,
                functions: 80,
            },
        },
        sequence: {
            concurrent: false,
        },
    },
});
