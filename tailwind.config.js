import colors from "tailwindcss/colors.js";
import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";
import headlessui from "@headlessui/tailwindcss";

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./components/**/*.{js,vue,ts}",
    "./layouts/**/*.vue",
    "./pages/**/*.vue",
    "./plugins/**/*.{js,ts}",
    "./app.vue",
    "./error.vue",
    "./nuxt.config.ts"
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
      boxShadow: {
        sm: '.4rem .4rem 0 0',
        DEFAULT: '.4rem .4rem 0 0',
        md: '.4rem .4rem 0 0',
      },
      boxShadowColor: {
        DEFAULT: '#313244'
      },
      fontFamily: {
        sans: ['Alata', ...defaultTheme.fontFamily.sans],
        mono: ['Fragment Mono', ...defaultTheme.fontFamily.mono],
      }
    },
  },
  plugins: [forms, typography, headlessui],
}

