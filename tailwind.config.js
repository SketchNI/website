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
      primary: colors.blue[500],
      secondary: colors.red[500],
      accent: colors.emerald[400],
      'primary-dark': colors.blue[700],
      black: colors.black,
      white: colors.white,
      gray: colors.neutral,
      red: colors.rose,
      blue: colors.blue,
      purple: colors.purple,
      green: colors.emerald,
      inherit: 'inherit',
      current: 'currentColor',
      transparent: 'transparent',
    },
    extend: {
      fontFamily: {
        sans: ['Alata', ...defaultTheme.fontFamily.sans],
        mono: ['Fragment Mono', ...defaultTheme.fontFamily.mono],
      }
    },
  },
  plugins: [forms, typography, headlessui],
}

