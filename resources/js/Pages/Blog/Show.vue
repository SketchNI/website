<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import moment from "moment";
import { Deferred } from "@inertiajs/vue3";
import useApp from "@/Composables/useApp.js";

defineProps({
    post: {
        id: Number,
        slug: String,
        title: String,
        content: String,
        excerpt: String,
        categories: Array,
        published_at: String,
        created_at: String,
        updated_at: String,
        author: {
            id: Number,
            name: String,
            email: String,
        }
    },
    categories: Object,
})

const app = useApp();
</script>

<template>
    <x-head title="Blog Posts" />

    <app-layout>
        <div class="flex gap-6">
            <div class="space-y-4 w-4/6">
                <Deferred data="post">
                    <template #fallback>
                        <div class="flex items-center text-4xl justify-center py-32 bg-mantle shadow shadow-crust">
                            <i class="fas fa-spinner animate-spin mr-2" />
                            <span>Rendering post</span>
                        </div>
                    </template>

                    <article class="article prose prose-blue" :class="[app.theme !== 'latte' ? 'prose-invert' : '']">
                    <p class="text-subtext0">
                        Published
                        <time v-if="moment().diff(post.published_at, 'days') <= 7"
                              :datetime="post.published_at"
                              :title="moment(post.published_at).format('Do MMM YYYY [at] hh:mma')"
                              class="font-semibold"
                              :class="[app.theme === 'latte' ? 'text-black' : 'text-white']">
                            {{ moment(new Date()).from(post.published_at, true) }} ago
                        </time>
                        <time v-else
                              :datetime="post.published_at"
                              :title="moment(post.published_at).format('Do MMM YYYY [at] hh:mma')"
                              class="font-semibold"
                              :class="[app.theme === 'latte' ? 'text-black' : 'text-white']">
                            {{ moment(post.published_at).format('Do MMM YYYY [at] hh:mma') }}
                        </time>
                        by
                        <span class="font-semibold font-mono">{{ post.author.name }}</span>
                        <span v-if="post.categories?.length > 0">
                            in
                            <span v-for="(cat, i) in post.categories" :key="i">
                                <x-link :href="route('category.show', { slug: cat.slug })" class="tag">
                                    {{ cat.name }}
                                </x-link>
                            </span>
                        </span>
                    </p>

                    <div v-html="post.content"/>
                </article>
                </Deferred>
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
