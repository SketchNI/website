import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import catppuccin from '@catppuccin/tailwindcss';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/views/**/*.blade.php'
    ],

    safelist: {
        pattern: /./,
    },

    theme: {
        colors: {
            primary: '#89b4fa',
            secondary: '#fb7185',
            accent: '#a6e3a1',
            'primary-dark': '#527dc2',
            'secondary-dark': '#c25472',
            'accent-dark': '#5ead57',
            black: '#11111b',
            white: '#cdd6f4',
            inherit: 'inherit',
            current: 'currentColor',
            transparent: 'transparent',
        },
        fontSize: {
            xs: ['0.75rem', { lineHeight: '1rem' }],
            sm: ['0.875rem', { lineHeight: '1.25rem' }],
            normal: ['1rem', { lineHeight: '1.5rem' }],
            lg: ['1.125rem', { lineHeight: '1.75rem' }],
            xl: ['1.25rem', { lineHeight: '1.75rem' }],
            '2xl': ['1.5rem', { lineHeight: '2rem' }],
            '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
            '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
            '5xl': ['3rem', { lineHeight: '1' }],
            '6xl': ['3.75rem', { lineHeight: '1' }],
            '7xl': ['4.5rem', { lineHeight: '1' }],
            '8xl': ['6rem', { lineHeight: '1' }],
            '9xl': ['8rem', { lineHeight: '1' }],
        },
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
                mono: ['Red Hat Mono', ...defaultTheme.fontFamily.mono],
            },
            boxShadow: {
                sm: '.2rem .2rem 0 0',
                DEFAULT: '.4rem .4rem 0 0',
                md: '.4rem .4rem 0 0',
            },
        },
    },

    plugins: [forms, typography, catppuccin({
        defaultFlavour: "mocha",
    })],
};
