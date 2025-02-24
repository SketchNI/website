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
    @apply font-bold font-mono text-blue w-full cursor-pointer inline-flex items-center px-2 py-1 space-x-2
    text-sm hover:bg-surface1 hover:text-text
    transition duration-150 ease-in;

    &::before {
        content: "/";
        @apply pr-2 text-red-400 font-black hover:text-red-600 text-xs;
        @apply transition duration-150 ease-in;
    }
}

.tag-item {
    @apply font-bold;
}

.cat-count {
    @apply bg-blue-400 font-black font-mono text-black rounded-full px-1.5 py-[.0075rem] text-sm
    group-hover:text-white group-hover:bg-blue-600;
    @apply transition duration-150 ease-in;
}
</style>
