import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import catppuccin from '@catppuccin/tailwindcss';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

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
            "c-light": '#585b70',
            "c-dark": '#313244',
            "c-darkest": '#11111b',
            inherit: 'inherit',
            current: 'currentColor',
            transparent: 'transparent',
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

    plugins: [forms, catppuccin({
        defaultFlavour: "mocha",
    })],
};
