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
  plugins: [forms, typography, animate],
};

