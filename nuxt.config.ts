// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-11-01',
  devtools: { enabled: false, },

  css: [
    '~/assets/css/main.css',
  ],

  app: {
    head: {
      bodyAttrs: {
        class: ['font-mono', 'antialiased', 'bg-[#1e1e2e]', 'text-blue-100']
      },
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
      title: 'Sketch World!',
      link: [
        { rel: 'stylesheet', href: 'https://fonts.bunny.net/css?family=fragment-mono:400,400i' },
        { rel: 'stylesheet', href: '//prismjs.catppuccin.com/mocha.css' }
      ],
      script: [
        { src: 'https://kit.fontawesome.com/5b07eb1cee.js', crossorigin: 'anonymous', defer: true }
      ]
    },
  },

  content: {
    highlight: {
      langs: [
        'php', 'javascript', 'vue', 'bash', 'http'
      ],
      theme: 'github-dark',
    },
    markdown: {
      remarkPlugins: [
        'remark-gfm'
      ]
    }
  },

  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {},
    },
  },

  modules: ['@nuxt/content'],
})
