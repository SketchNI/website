<script setup>


import AppLayout from "@/Layouts/AppLayout.vue";

const teams = [
    {
        name: 'Madhouse Infra',
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
        image: 'maddie.png',
        since: 'Feb 15th, 2021',
    },
    {
        name: 'Madhouse Miners',
        links: [
            {
                name: 'GitHub',
                url: 'https://github.com/madhouseminers',
                icon: 'fa-brands fa-github',
            },
            {
                name: 'Madhouse Miners Website',
                url: 'https://madhouseminers.com',
                icon: 'fa-solid fa-globe',
            },
        ],
        description: 'We are a friendly bunch of people who run and play on a network of modded Minecraft servers.',
        what_i_do: "I help administer the minecraft servers and moderate the Discord server.",
        image: 'maddie.png',
        since: 'Feb 15th, 2021',
    },
    {
        name: 'The Claw',
        links: [
            {
                name: 'The Claw Website',
                url: 'https://theclaw.team',
                icon: 'fa-solid fa-globe',
            },
        ],
        description: "Build stuff, learn things, love what you do. The Claw is science and technology — for everyone.",
        what_i_do: "I moderate the Discord server and Twitch channel and have contributed some code in the past.",
        image: 'theclaw.webp',
        since: 'Aug 16th, 2020',
    },
];
</script>

<template>
    <x-head title="Teams" />

    <app-layout>
        <div>
            <div class="flex gap-6 my-6 flex-col lg:flex-row">
                <div class="card group space-y-4" v-for="(team, i) in teams" :key="i">
                    <div class="flex justify-center items-center space-x-4">
                        <img :src="`/images/${team.image}`" :alt="`${team.name}'s Logo`"
                             class="bg-crust p-2 border-2 border-blue w-24 h-24" />
                        <h1 class="text-xl text-blue-400" v-text="team.name" />
                    </div>
                    <div class="flex flex-row justify-center items-center space-x-4 text-green">
                        <a v-for="(link, i) in team.links" :key="i"
                            :href="link.url" target="_blank" class="card-link">
                            <i :class="link.icon"></i>
                            <span class="sr-only" v-text="link.name" />
                        </a>
                    </div>
                    <p class="text-red" v-text="team.description" />
                    <p class="text-green" v-text="team.what_i_do" />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<style scoped>

</style>
