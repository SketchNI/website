<script setup>
import moment from "moment";
import useApp from "@/Composables/useApp.js";
import hljs from 'highlight.js/lib/common';
import '@/../css/a11y-dark.css';
import '@catppuccin/highlightjs/css/catppuccin-macchiato.css';
import { nextTick, ref } from "vue";
import TagPanel from "@/Components/TagPanel.vue";
import useRole from "@/Composables/useRole.js";
import useUser from "@/Composables/useUser.js";

const props = defineProps({
    post: {
        id: Number,
        slug: String,
        title: String,
        content: String,
        excerpt: String,
        tags: Array,
        comments: Array,
        featured_image: String | null,
        published_at: String,
        created_at: String,
        updated_at: String,
        author: {
            id: Number,
            name: String,
            email: String,
        }
    },
    tags: Object,
})

const app = useApp();
const role = useRole();
const user = useUser();

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

    <div class="flex max-lg:flex-col gap-6 items-start max-lg:px-4">
        <div class="space-y-4 w-full">
            <div class="bg-red-400/40 font-black text-white text-xl px-6 py-3">
                This is a preview of an unpublished post!
            </div>
            <article class="article prose max-w-[82.5ch] prose-blue prose-invert">
                <div class="flex flex-col space-y-2 mb-4">
                    <h1 class="text-5xl mb-2 w-full border-b border-blue/60 pb-2" v-text="post.title" />
                    <div class="text-subtext0 items-center">
                        Published
                        <time class="text-text font-semibold">
                            <span class="text-red font-black">not yet published</span>
                        </time>
                        by
                        <span class="font-semibold text-green">{{ post.author.name }}</span>
                    </div>

                    <div v-if="post.tags?.length > 0" class="flex items-center space-x-2">
                        <div class="text-subtext0 text-sm">Tags:</div>
                        <div class="inline-flex items-center space-x-2">
                            <div v-for="(tag, i) in post.tags" :key="i" class="text-xs">
                                <x-link href="route('category.show', { slug: tag.slug })" class="tag !text-sm">
                                    <span>{{ tag.name }}</span>
                                </x-link>
                            </div>
                        </div>
                    </div>

                    <div v-if="post.created_at !== post.updated_at"
                         class="text-overlay2 mt-1 mb-3 text-sm">
                        (Last updated at
                        <time
                            :datetime="post.updated_at"
                            :title="moment(post.updated_at).format('Do MMM YYYY [at] hh:mma')"
                            class="text-text font-semibold">
                            {{ moment(post.updated_at).format('Do MMM YYYY [at] hh:mma') }}
                        </time>
                        )
                    </div>
                </div>

                <div v-html="post.content" class="w-full" />
            </article>
        </div>

        <TagPanel :tags="tags" />
    </div>
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
</style>
