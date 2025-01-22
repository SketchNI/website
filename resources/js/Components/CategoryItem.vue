<script setup>
defineProps({
    categories: Array,
    className: 'parent'
})
</script>

<template>
    <ul class="text-lg">
        <li v-for="category in categories" :key="category.id">
            <x-link :href="route('category.show', { category })" :class="[className, 'group category']">
                <span v-text="category.name" />
                <span v-if="category.posts_count !== 0" class="group cat-count" v-text="category.posts_count" />
            </x-link>
            <category-item :className="className === 'child' ? 'grandchild' : 'child'" v-if="category.children" :categories="category.children" />
        </li>
    </ul>
</template>

<style scoped>
.category {
    @apply font-bold font-mono text-blue w-full text-normal cursor-pointer inline-flex
    items-center hover:bg-surface1 hover:text-text px-2 py-1 space-x-2
    transition duration-150 ease-in;

    &::before {
        content: "/";
        @apply pr-2 text-red-400 font-black hover:text-red-600 text-sm;
        @apply transition duration-150 ease-in;
    }
}

.parent {
    @apply font-bold;
}

.child {
    @apply pl-6;
}

.grandchild {
    @apply pl-12;
}

.cat-count {
    @apply bg-blue-400 font-black font-mono text-black rounded-full px-1.5 py-[.0075rem] text-sm
    group-hover:text-white group-hover:bg-blue-600;
    @apply transition duration-150 ease-in;
}
</style>
