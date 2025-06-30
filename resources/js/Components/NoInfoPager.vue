<script setup>
defineProps({
    pagination: Object
})
</script>

<template>
    <nav class="flex justify-end bg-gray-900 px-4 py-2">
        <div class="isolate inline-flex -space-x-px" v-if="pagination.total > pagination.per_page">
            <span v-for="link in pagination.links" :key="link.label">
                <x-link
                    v-if="link.url !== null && link.active === false"
                    preserve-scroll
                    :href="link.url"
                    v-html="link.label"
                    class="pager"
                    :class="{ '!bg-primary-dark !text-white': link.active, '!text-white': !link.url }"
                />

                <span v-else
                      v-html="link.label"
                      :class="{ '!bg-primary-dark !text-white !font-black': link.active }"
                      class="inactive-pager" />
            </span>
        </div>
    </nav>
</template>

<style scoped>
.pager {
    @apply flex items-center justify-center bg-gray-800 px-4 py-2 font-mono text-sm text-gray-300
    border-y border-primary/60 first-of-type:border-l border-r hover:bg-primary-dark hover:text-white;
    @apply transition duration-150 ease-in;
}

.inactive-pager {
    @apply flex font-mono items-center justify-center text-sm bg-gray-800/50 px-4 py-2 text-gray-400 cursor-not-allowed
    border-y border-primary/40 first:border-l last:border-r;
}
</style>
