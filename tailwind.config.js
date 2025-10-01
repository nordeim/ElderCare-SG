import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import animate from 'tailwindcss-animate';

const withOpacityValue = (variable) => ({ opacityValue }) => {
  if (opacityValue === undefined) {
    return `rgb(var(${variable}))`;
  }

  return `rgb(var(${variable}) / ${opacityValue})`;
};

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/js/**/*.vue',
    './resources/js/**/*.ts',
    './resources/**/*.php',
  ],
  darkMode: 'media',
  theme: {
    extend: {
      colors: {
        trust: withOpacityValue('--color-trust'),
        gold: withOpacityValue('--color-gold'),
        amber: withOpacityValue('--color-amber'),
        wellness: withOpacityValue('--color-wellness'),
        canvas: withOpacityValue('--color-canvas'),
        slate: {
          DEFAULT: withOpacityValue('--color-slate'),
          dark: withOpacityValue('--color-slate-dark'),
        },
      },
      fontSize: {
        'display-lg': ['clamp(2.75rem, 2.25rem + 1.5vw, 3.5rem)', { lineHeight: '1.05' }],
        'display-md': ['clamp(2.25rem, 2rem + 1vw, 3rem)', { lineHeight: '1.1' }],
        'heading-xl': ['clamp(1.75rem, 1.5rem + 0.8vw, 2.25rem)', { lineHeight: '1.2' }],
        'heading-lg': ['clamp(1.5rem, 1.35rem + 0.6vw, 2rem)', { lineHeight: '1.3' }],
        'heading-md': ['clamp(1.25rem, 1.15rem + 0.4vw, 1.6rem)', { lineHeight: '1.4' }],
        'body-lg': ['clamp(1.125rem, 1.05rem + 0.2vw, 1.25rem)', { lineHeight: '1.6' }],
        'body-md': ['1rem', { lineHeight: '1.65' }],
        'body-sm': ['0.9375rem', { lineHeight: '1.6' }],
        'body-xs': ['0.875rem', { lineHeight: '1.5' }],
      },
      fontFamily: {
        heading: ['"Playfair Display"', 'serif'],
        body: ['Inter', 'sans-serif'],
      },
      boxShadow: {
        card: '0 20px 45px -20px rgba(28, 61, 90, 0.25)',
      },
      maxWidth: {
        section: '1280px',
      },
      transitionTimingFunction: {
        'ease-out-cubic': 'cubic-bezier(0.22, 1, 0.36, 1)',
      },
      keyframes: {
        fadeInUp: {
          '0%': { opacity: 0, transform: 'translateY(24px)' },
          '100%': { opacity: 1, transform: 'translateY(0)' },
        },
      },
      animation: {
        'fade-in-up': 'fadeInUp 0.6s var(--ease-out-cubic, cubic-bezier(0.22, 1, 0.36, 1)) both',
      },
      container: {
        center: true,
        padding: {
          DEFAULT: '1.5rem',
          sm: '2rem',
          lg: '2.5rem',
        },
      },
    },
  },
  plugins: [forms, typography, animate, function ({ addUtilities }) {
    addUtilities({
      '.text-display-lg': {
        fontSize: 'clamp(2.75rem, 2.25rem + 1.5vw, 3.5rem)',
        lineHeight: '1.05',
      },
      '.text-display-md': {
        fontSize: 'clamp(2.25rem, 2rem + 1vw, 3rem)',
        lineHeight: '1.1',
      },
    });
  }],
};

