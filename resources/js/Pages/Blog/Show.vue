<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import moment from "moment";
import useApp from "@/Composables/useApp.js";
import hljs from 'highlight.js/lib/common';
import '@/../css/a11y-dark.css';
import '@catppuccin/highlightjs/css/catppuccin-macchiato.css';
import { nextTick, ref } from "vue";
import CategoryPanel from "@/Components/CategoryPanel.vue";
import Divider from "@/Components/divider.vue";
import useRole from "@/Composables/useRole.js";
import CommentItem from "@/Components/Blog/CommentItem.vue";
import CommentBox from "@/Components/Blog/CommentBox.vue";

const props = defineProps({
    post: {
        id: Number,
        slug: String,
        title: String,
        content: String,
        excerpt: String,
        categories: Array,
        comments: Array,
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
const role = useRole();

const showCommentForm = ref(false);

nextTick(() => {
    hljs.highlightAll();
});

</script>

<template>
    <x-head>
        <title>{{ post.title }}</title>
        <meta :content="post.title" property="og:title" />
        <meta :content="post.excerpt" property="og:description" />
        <meta :content="post.url" property="og:url" />
        <meta :content="post.featured_image" property="og:image" />
        <meta content="en_GB" property="og:locale" />
        <meta content="sketchni.uk" property="og:site_name" />
        <meta content="article" property="og:type" />
        <meta :content="post.featured_image" name="image" />
        <meta :content="post.excerpt" name="description" />
        <meta content="Written by" name="twitter:label1" />
        <meta :content="post.author.name" name="twitter:data1" />
        <link :href="post.url" rel="canonical" />
    </x-head>

    <app-layout>
        <div class="flex max-lg:flex-col gap-6 items-start max-lg:px-4">
            <div class="space-y-4 w-full">
                <article class="article prose max-w-[82.5ch] prose-blue prose-invert">
                    <div class="flex flex-col space-y-2 mb-4">
                        <h1 class="text-5xl mb-2 w-full border-b border-blue/60 pb-2" v-text="post.title" />
                        <div class="text-subtext0 items-center">
                            Published
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
                            by
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

                <div>
                    <divider class="from-red-300 to-red-300 via-blue-400 from-10% to-90% h-0.5 rounded-full" />

                    <h3 class="inline-flex items-center space-x-2 text-xl my-6">
                        <span>Comments</span>
                        <span class="text-sm text-subtext0">({{ post.comments.length }} comments)</span>
                    </h3>

                    <div>
                        <transition name="slide"
                                    enter-active-class="ease-out duration-300"
                                    enter-from-class="opacity-0 scale-y-4 sm:scale-y-0 sm:scale-95"
                                    enter-to-class="opacity-100 scale-y-0 sm:scale-100"
                                    leave-active-class="ease-in duration-200"
                                    leave-from-class="opacity-100 scale-y-0 sm:scale-100"
                                    leave-to-class="opacity-0 scale-y-4 sm:scale-y-0 sm:scale-95">
                            <div v-if="showCommentForm">
                                <comment-box :post="post" />
                            </div>
                        </transition>

                        <div role="list" class="space-y-4" v-if="post.comments.length > 0">
                            <p v-if="!showCommentForm" class="text-normal w-64 mb-4">
                                <button
                                    class="text-blue underline hover:text-blue-400 focus:text-white transition duration-150 ease-in"
                                    type="button"
                                    @click.prevent="showCommentForm = true">
                                    Leave a comment?
                                </button>
                            </p>

                            <divider class="h-0.5 my-6 from-red to-red from-10% to-90%" />

                            <comment-item
                                v-for="(comment, i) in post.comments" :key="i" :comment="comment" :post_id="post.id" />
                        </div>
                        <div class="space-y-4" v-else>
                            <div class="py-8 text-xl text-overlay2 flex flex-col items-center justify-center w-full">
                                <div class="inline-flex space-x-2 w-64">
                                    <i class="fas fa-circle-exclamation" />
                                    <p class="m-0 w-full">There are no comments.</p>
                                </div>
                                <p v-if="!showCommentForm" class="text-normal w-64 mt-4">
                                    <button
                                        class="text-blue underline hover:text-blue-400 focus:text-white transition duration-150 ease-in"
                                        type="button"
                                        @click.prevent="showCommentForm = true">
                                        Leave a comment?
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
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
