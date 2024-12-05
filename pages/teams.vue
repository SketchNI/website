<script setup lang="ts">
import madhouse from '~/assets/images/maddie.png';
import theclaw from '~/assets/images/theclaw.webp'

const route = useRoute();

const teams = [
  {
    name: 'Madhouse Platform',
    links: [
      {
        name: 'GitHub',
        url: 'https://github.com/madhouseplatform',
        icon: 'fa-brands fa-github',
      },
      {
        name: 'Madhouse Miners Website',
        url: 'https://madhouseminers.com',
        icon: 'fa-solid fa-globe',
      },
    ],
    description: "We're developing the software that powered Madhouse Miners",
    what_i_do: "I develop for the web side of the infrastructure.",
    image: madhouse,
    since: 'Feb 15th, 2021',
  },
  {
    name: 'Madhouse Miners',
    links: [
      {
        name: 'GitHub',
        url: 'https://github.com/madhouseminers',
        icon: 'fa-brands fa-github'
      },
      {
        name: 'Madhouse Miners Website',
        url: 'https://madhouseminers.com',
        icon: 'fa-solid fa-globe'
      },
    ],
    description: 'We are a friendly bunch of people who run and play on a network of modded Minecraft servers.',
    what_i_do: "I help administer the minecraft servers and moderate the Discord server.",
    image: madhouse,
    since: 'Feb 15th, 2021',
  },
  {
    name: 'The Claw',
    links: [
      {
        name: 'The Claw Website',
        url: 'https://theclaw.team',
        icon: 'fa-solid fa-globe'
      },
    ],
    description: "Build stuff, learn things, love what you do. The Claw is science and technology — for everyone.",
    what_i_do: "I moderate the Discord server and Twitch channel and have contributed some code in the past.",
    image: theclaw,
    since: 'Aug 16th, 2020',
  },
]

</script>

<template>
    <div>
        <div class="flex gap-6 my-6">
            <div v-for="(team, i) in teams" :key="i" class="card group space-y-4">
                <div class="flex items-center space-x-4">
                    <div class="w-24">
                        <img :src="team.image" :alt="`${team.name}'s Logo`" class="" />
                    </div>
                    <h1 class="text-xl text-blue-400">{{ team.name }}</h1>
                </div>
                <div class="flex flex-row justify-center items-center space-x-4 text-green">
                    <nuxt-link v-for="(link, j) in team.links" :key="j" :to="link.url" target="_blank" class="card-link">
                        <i :class="link.icon"></i>
                        <span class="sr-only">{{ link.name }}</span>
                    </nuxt-link>
                </div>
                <p class="text-red" v-html="team.description" />
                <p class="text-green" v-html="team.what_i_do" />
            </div>
        </div>
    </div>
</template>

<style scoped>
.card {
    @apply border-2 border-primary-dark hover:bg-c-darkest p-6 shadow w-1/2 select-none cursor-pointer;
    @apply transition duration-150 ease-in;
}

.card-link {
    @apply p-2 inline-flex rounded-md text-white hover:shadow-md hover:shadow-c-light hover:bg-c-dark;
    @apply transition duration-150 ease-in;

    & > svg {
        @apply text-2xl;
    }
}
</style>
