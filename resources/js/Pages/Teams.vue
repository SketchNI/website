<script setup>
defineProps({
    teams: {
        data: Object,
    },
})
</script>

<template>
    <x-head title="Teams" />

    <div>
        <div class="flex mx-4 lg:mx-0 gap-6 my-6 flex-col lg:flex-row">
            <div v-for="(team, i) in teams.data" :key="i" class="card group space-y-4">
                <div class="flex justify-center items-center space-x-4">
                    <img :alt="`${team.name}'s Logo`" :src="team.logo"
                         class="bg-black p-2 border-2 border-primary w-24 h-24" />
                    <h1 class="text-xl text-primary" v-text="team.name" />
                </div>
                <div class="flex flex-row justify-center items-center space-x-4 text-green">
                    <a :href="`https://github.com/${team.github}`" class="card-link" target="_blank">
                        <i class="fa-brands fa-github"></i>
                        <span class="sr-only">GitHub</span>
                    </a>
                    <a :href="team.website" class="card-link" target="_blank">
                        <i class="fa-solid fa-globe"></i>
                        <span class="sr-only">Website</span>
                    </a>
                </div>
                <p class="text-secondary" v-text="team.description" />
                <p class="text-accent" v-text="team.role" />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
