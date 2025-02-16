<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import moment from "moment";
import hljs from 'highlight.js/lib/common';
import '@/../css/a11y-dark.css';
import '@catppuccin/highlightjs/css/catppuccin-macchiato.css';
import { nextTick, ref } from "vue";
import CategoryPanel from "@/Components/CategoryPanel.vue";
import Divider from "@/Components/divider.vue";
import CommentItem from "@/Components/Blog/CommentItem.vue";
import CommentBox from "@/Components/Blog/CommentBox.vue";
import useUser from "@/Composables/useUser.js";
import { useForm } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    post: {
        id: Number,
        slug: String,
        title: String,
        content: String,
        excerpt: String,
        categories: Array,
        comments: Array,
        reactions: Array,
        reactions_summary: Array,
        featured_image: String|null,
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

const user = useUser();

const showCommentForm = ref(false);

const showTab = ref('comments');
const setTab = (tab) => {
    if (tab !== showTab.value) {
        showCommentForm.value = false;
    }
    showTab.value = tab;
}

nextTick(() => {
    hljs.highlightAll();
});

const availableReactions = [
    { label: 'Upvote', value: '👍' },
    { label: 'Downvote', value: '👎' },
    { label: 'Poop', value: '💩' },
    { label: 'Heart', value: '❤️' },
]

const form = useForm({
    reaction: {},
});

const attachReaction = (reaction) => {
    form.reaction = reaction.label.toLowerCase();
    sendReaction();
}

const sendReaction = () => {
    form.put(route('blog.react', { post: props.post }), {
        onSuccess: (data) => {
            console.log(data)
        }
    });
}

const getCount = (reaction) => {
    console.log(reaction)
    const reactions = props.post.reactions_summary;
    switch (reaction) {
        case 'upvote':
            return reactions.upvote;
        case 'downvote':
            return reactions.downvote;
        case 'poop':
            return reactions.poop;
        case 'heart':
            return reactions.heart;
    }
}

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
                            <span>Categories:</span>
                            <span v-for="(cat, i) in post.categories" :key="i">
                                <x-link :href="route('category.show', { slug: cat.slug })" class="tag">
                                    <span>{{ cat.name }}</span>
                                </x-link>
                            </span>
                        </div>

                        <div v-if="post.created_at !== post.updated_at"
                             class="text-overlay2 mt-1 mb-3 text-sm">
                            (Last updated at
                            <time
                                :datetime="post.updated_at"
                                :title="moment(post.updated_at).format('Do MMM YYYY [at] hh:mma')"
                                class="text-text font-semibold">
                                {{ moment(post.updated_at).format('Do MMM YYYY [at] hh:mma') }}
                            </time>)
                        </div>
                    </div>

                    <div v-html="post.content" class="w-full" />
                </article>

                <div>
                    <divider class="from-red-300 to-red-300 via-blue-400 from-10% to-90% h-px rounded-full" />

                    <div>
                        <form @submit.prevent="sendReaction">
                            <p class="font-medium text-subtext0 mt-4 mb-2">Leave a reaction</p>
                            <ul class="inline-flex items-center space-x-6 mb-4">
                                <li v-for="reaction in availableReactions" class="text-2xl" :key="reaction.label">
                                    <button type="button" :title="reaction.label" @click.prevent="attachReaction(reaction)"
                                            class="relative">
                                        <span>{{ reaction.value }}</span>
                                        <span class="sr-only">{{ reaction.label }}</span>
                                        <span class="badge">{{ getCount(reaction.label.toLowerCase()) ?? 0}}</span>
                                    </button>
                                </li>
                            </ul>
                        </form>
                    </div>

                    <divider class="h-px" />

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
                            <div v-if="!showCommentForm && user !== null" class="text-normal w-64 my-4">
                                <secondary-button
                                    type="button"
                                    @click.prevent="showCommentForm = true">
                                    Leave a comment?
                                </secondary-button>
                            </div>

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
                                <div v-if="!showCommentForm && user !== null" class="text-normal w-64 mt-6">
                                    <button
                                        class="text-blue underline hover:text-blue-400 focus:text-white transition duration-150 ease-in"
                                        type="button"
                                        @click.prevent="showCommentForm = true">
                                        Leave a comment?
                                    </button>
                                </div>
                                <div v-if="!showCommentForm && user === null" class="text-normal w-64 mt-4">
                                    <x-link
                                        :href="route('login')"
                                        class="text-blue underline hover:text-blue-400 focus:text-white transition duration-150 ease-in">
                                        Log in to leave a comment.
                                    </x-link>
                                </div>
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

.tag {
    @apply font-bold font-mono bg-blue-500 px-1.5 rounded-sm py-0.5 text-xs text-white no-underline text-[1rem] cursor-pointer
    items-center hover:bg-blue-600
    transition duration-150 ease-in;
}

.badge {
    @apply bg-lavender text-crust font-black font-mono group-hover:bg-surface2 rounded-full px-[5px] py-px text-xs;
    @apply absolute bottom-0 -right-[5px];
}
</style>
