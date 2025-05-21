<script setup>
defineProps({
    pagination: Object
})
</script>

<template>
    <nav class="flex justify-between bg-zinc-800 px-4 py-2 shadow shadow-black">
        <div class="inline-flex items-center space-x-1 text-gray-400 text-sm py-2.5">
            <span>Showing</span>
            <span class="font-semibold text-gray-100">{{ pagination.from }}</span>
            <span>to</span>
            <span class="font-semibold text-gray-100">{{ pagination.to }}</span>
            <span>of</span>
            <span class="font-semibold text-gray-100">{{ pagination.total }}</span>
            <span>results</span>
        </div>

        <div class="isolate inline-flex -space-x-px" v-if="pagination.total > pagination.per_page">
            <span v-for="link in pagination.links" :key="link.label">
                <x-link
                    v-if="link.url !== null && link.active === false"
                    preserve-scroll
                    :href="link.url"
                    v-html="link.label"
                    class="pager"
                    :class="{ '!bg-zinc-800 !text-gray-300': link.active, '!text-gray-100': !link.url }"
                />

                <span v-else
                      v-html="link.label"
                      :class="{ '!bg-zinc-800 !text-gray !font-black': link.active }"
                      class="inactive-pager" />
            </span>
        </div>
    </nav>
</template>

<style scoped>
.pager {
    @apply flex items-center justify-center bg-zinc-900 px-4 py-2 font-mono text-sm text-primary
    border-y border-primary/60 first-of-type:border-l border-r hover:bg-zinc-800 hover:text-white;
    @apply transition duration-150 ease-in;
}

.inactive-pager {
    @apply flex font-mono items-center justify-center text-sm bg-zinc-900 px-4 py-2 text-gray-400 cursor-not-allowed
    border-y border-primary/40 first:border-l last:border-r;
}
</style>
