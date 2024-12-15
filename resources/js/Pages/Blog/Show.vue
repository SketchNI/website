<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    post: Object,
    categories: Object,
    theme: String,
})
</script>

<template>
    <x-head title="Blog Posts" />

    <app-layout>
        <div class="flex gap-6">
            <div class="space-y-4 w-4/6">
                <article class="article prose prose-blue" :class="[theme !== 'latte' ? 'prose-invert' : '']" v-html="post.content" />
            </div>

            <div class="bg-mantle w-2/6 p-4 shadow shadow-crust">
                <ul class="space-y-2 text-lg">
                    <li v-for="cat in categories" :key="cat.id">
                        <x-link :href="route('category.show', { slug: cat.slug })" class="group category space-x-2">
                            <span v-text="cat.name" />
                            <span class="group cat-count" v-text="`(${cat.posts_count} posts)`" />
                        </x-link>
                    </li>
                </ul>
            </div>
        </div>
    </app-layout>
</template>

<style scoped lang="postcss">
.category {
    @apply font-bold text-blue border-b-2 border-transparent cursor-pointer inline-flex items-center
    hover:bg-blue hover:text-base px-2 py-1.5
    transition duration-150 ease-in;
}

.cat-count {
    @apply text-sm font-normal text-subtext0 group-hover:text-black;
    @apply transition duration-150 ease-in;
}

.tag {
    @apply text-xs font-semibold text-base bg-blue px-2 py-1 shadow-sm shadow-overlay0 mx-1
    hover:bg-overlay0 hover:text-text hover:shadow-none uppercase tracking-wide;
    @apply transition duration-150 ease-in;
}
</style>
