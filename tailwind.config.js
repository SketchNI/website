import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import catppuccin from '@catppuccin/tailwindcss';
import typography from '@tailwindcss/typography';
import colors from "tailwindcss/colors";

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
            white: '#f0eded',
            inherit: 'inherit',
            current: 'currentColor',
            transparent: 'transparent',
            orange: colors.orange[400],
            blue: {
                DEFAULT: '#89B4FA',
                50: '#EBF2FE',
                100: '#D7E6FD',
                200: '#B0CDFC',
                300: '#89B4FA',
                400: '#5392F8',
                500: '#1D70F5',
                600: '#0955D2',
                700: '#073F9C',
                800: '#042A66',
                900: '#021430',
                950: '#010915'
            },
            red: {
                DEFAULT: '#F38BA8',
                50: '#FDE7ED',
                100: '#FBD5DF',
                200: '#F7B0C4',
                300: '#F38BA8',
                400: '#EE5882',
                500: '#E8255C',
                600: '#C21444',
                700: '#8F0F32',
                800: '#5C0A21',
                900: '#29040F',
                950: '#100206'
            }
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
            textSize: {
                md: defaultTheme.fontSize.base,
            },
            boxShadow: {
                sm: '.2rem .2rem 0 0',
                DEFAULT: '.4rem .4rem 0 0',
                md: '.4rem .4rem 0 0',
            },
            animation: {
                'spin-slow': 'spin 1.5s ease-in-out infinite',
            }
        },
    },

    plugins: [forms, typography, catppuccin({
        defaultFlavour: "mocha",
    })],
};
