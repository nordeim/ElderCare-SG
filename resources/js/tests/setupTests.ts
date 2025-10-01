import '@testing-library/jest-dom';

import { afterEach, vi } from 'vitest';

vi.useFakeTimers();

afterEach(() => {
    document.body.innerHTML = '';
    vi.clearAllTimers();
});

if (typeof document === 'undefined') {
    throw new Error('jsdom environment not active. Check vitest.config.ts test.environment.');
}
