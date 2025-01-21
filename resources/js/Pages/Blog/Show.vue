<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import moment from "moment";
import useApp from "@/Composables/useApp.js";
import hljs from 'highlight.js/lib/common';
import '@/../css/a11y-dark.css';
import '@catppuccin/highlightjs/css/catppuccin-macchiato.css';
import { nextTick } from "vue";
import CategoryPanel from "@/Components/CategoryPanel.vue";

const props = defineProps({
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
nextTick(() => {
    hljs.highlightAll();
});
</script>

<template>
    <x-head title="Blog Posts" />

    <app-layout>
        <div class="flex gap-6 items-start">
            <div class="space-y-4 w-full">
                <article class="article prose prose-blue prose-invert !w-full">
                    <div class="flex flex-col space-y-2 mb-4">
                        <div class="text-subtext0 space-x-1 items-center">
                            <span>Published</span>
                            <time v-if="moment().diff(post.published_at, 'days') <= 7"
                                  class="text-text font-semibold"
                                  :datetime="post.published_at"
                                  :title="moment(post.published_at).format('Do MMM YYYY [at] hh:mma')">
                                {{ moment(new Date()).from(post.published_at, true) }} ago
                            </time>
                            <time v-else
                                  :datetime="post.published_at"
                                  :title="moment(post.published_at).format('Do MMM YYYY [at] hh:mma')"
                                  class="text-text font-semibold">
                                {{ moment(post.published_at).format('Do MMM YYYY [at] hh:mma') }}
                            </time>
                            <span>by</span>
                            <span class="font-semibold text-green">{{ post.author.name }}</span>
                        </div>

                        <div v-if="post.categories?.length > 0">
                            <span>Posted in </span>
                            <span v-for="(cat, i) in post.categories" :key="i">
                                <x-link :href="route('category.show', { slug: cat.slug })" class="tag">
                                    <span>{{ cat.name }}</span>
                                </x-link>
                                <span v-if="i < post.categories.length && i !== post.categories.length - 1">, </span>
                                <span v-if="i === post.categories.length - 1">.</span>
                            </span>
                        </div>

                        <div v-if="post.created_at !== post.updated_at"
                             class="flex text-overlay2 mt-1 mb-3 text-sm">
                            <span>(Last updated at </span>
                            <time
                                :datetime="post.updated_at"
                                :title="moment(post.updated_at).format('Do MMM YYYY [at] hh:mma')"
                                class="text-text font-semibold">
                                {{ moment(post.updated_at).format('Do MMM YYYY [at] hh:mma') }}
                            </time>
                            <span>)</span>
                        </div>
                    </div>

                    <div v-html="post.content" class="w-full" />
                </article>
            </div>

            <CategoryPanel :categories="categories" />
        </div>
    </app-layout>
</template>

<style lang="postcss" scoped>
.prose :where(blockquote) {
    @apply px-6 pt-1 pb-8 bg-crust;
}

.category {
    @apply font-bold font-mono text-blue w-full text-[1rem] border-b-2 border-transparent cursor-pointer inline-flex
    items-center hover:bg-blue hover:text-base px-2 py-1.5
    transition duration-150 ease-in;

    &::before {
        content: ">";
        @apply pr-2;
    }
}

.parent {
    @apply font-bold;
}

.child {
    @apply pl-6;
}

.cat-count {
    @apply text-sm font-normal text-subtext0 group-hover:text-black;
    @apply transition duration-150 ease-in;
}

.tag {
    @apply font-bold font-mono text-blue w-full text-[1rem] border-b-2 border-transparent cursor-pointer
    items-center hover:text-blue-400
    transition duration-150 ease-in;
}
</style>
