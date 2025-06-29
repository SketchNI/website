<script setup>
defineProps({
    counts: Object,
})
</script>

<template>
    <x-head title="Admin Panel"></x-head>

    <div class="bg-gray-800 shadow shadow-black">
        <div class="mx-auto">
            <div class="grid grid-cols-1 gap-px bg-gray-900 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="stat in counts" :key="stat.name" class="bg-gray-800/60 px-4 py-6 sm:px-6 lg:px-8">
                    <p class="text-sm/6 font-medium text-gray-400 uppercase">{{ stat.name }}</p>
                    <p class="mt-2 flex items-baseline gap-x-2">
                        <span class="text-4xl font-semibold tracking-tight text-white">{{ stat.value }}</span>
                        <span v-if="stat.unit" class="text-sm text-gray-300">{{ stat.unit }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
