<script setup>
defineProps({
    tags: Object,
    type: String,
})
</script>

<template>
    <ul>
        <li v-for="tag in tags" :key="tag.id">
            <x-link :href="route('category.show', { slug: tag.slug, type: type })" class="group category tag-item">
                <span v-text="tag.name" />
                <span v-if="type === 'post' && tag.attached_count !== 0" class="group cat-count" v-text="tag.attached_count ?? 0" />
            </x-link>
        </li>
    </ul>
</template>

<style scoped>
.category {
    @apply font-bold font-mono text-primary w-full cursor-pointer inline-flex items-center px-2 py-1 space-x-2
    text-sm hover:bg-primary/50 hover:text-white
    transition duration-150 ease-in;

    &::before {
        content: "/tag/";
        @apply text-secondary font-black hover:text-secondary text-xs;
        @apply transition duration-150 ease-in;
    }
}

.tag-item {
    @apply font-bold;
}

.cat-count {
    @apply bg-primary font-black font-mono text-black rounded-full px-1.5 py-[.0075rem] text-sm
    group-hover:text-white group-hover:bg-primary;
    @apply transition duration-150 ease-in;
}
</style>
