<script setup>
defineProps({
    pagination: Object
})
</script>

<template>
    <nav class="flex justify-between bg-mantle px-4 py-2 shadow shadow-crust">
        <div class="inline-flex items-center space-x-1 text-subtext0 text-sm py-2.5">
            <span>Showing</span>
            <span class="font-semibold text-subtext1">{{ pagination.from }}</span>
            <span>to</span>
            <span class="font-semibold text-subtext1">{{ pagination.to }}</span>
            <span>of</span>
            <span class="font-semibold text-subtext1">{{ pagination.total }}</span>
            <span>results</span>
        </div>

        <div class="isolate inline-flex -space-x-px" v-if="pagination.total > pagination.per_page">
            <span v-for="link in pagination.links" :key="link.label">
                <x-link
                    v-if="link.url !== null && link.active === false"
                    preserve-scroll
                    :href="link.url.replace('http:', 'https:')"
                    v-html="link.label"
                    class="pager"
                    :class="{ '!bg-crust !text-text': link.active, '!text-text': !link.url }"
                />

                <span v-else
                      v-html="link.label"
                      :class="{ '!bg-crust !text-subtext0 !font-black': link.active }"
                      class="inactive-pager" />
            </span>
        </div>
    </nav>
</template>

<style scoped>
.pager {
    @apply flex items-center justify-center bg-base px-4 py-2 font-mono text-sm text-blue
    border-y border-blue/60 first-of-type:border-l border-r hover:bg-base;
    @apply transition duration-150 ease-in;
}

.inactive-pager {
    @apply flex font-mono items-center justify-center text-sm bg-base px-4 py-2 text-subtext0 cursor-not-allowed
    border-y border-blue/40 first:border-l last:border-r;
}
</style>
