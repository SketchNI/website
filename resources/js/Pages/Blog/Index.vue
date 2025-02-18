<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import moment from 'moment/moment';
import Pager from "@/Components/Pager.vue";
import useApp from "@/Composables/useApp.js";
import { Deferred } from "@inertiajs/vue3";
import LoadingPane from "@/Pages/Blog/LoadingPane.vue";
import { ExclamationTriangleIcon, RssIcon } from "@heroicons/vue/24/solid";
import TagPanel from "@/Components/TagPanel.vue";

const props = defineProps({
    posts: {
        meta: Object,
        links: Object,
        data: Object,
    },
    tags: Object,
});

const app = useApp();
</script>

<template>
    <x-head title="Blog Posts" />

    <app-layout>
        <a class="flex items-center mb-4 text-blue-300 transition duration-150 ease-in-out group"
                href="/feed">
            <span class="group group-hover:bg-orange/40 px-1 py-1">
                <rss-icon class="size-5 text-orange group" />
            </span>
            <span class="group group-hover:bg-blue-300/40 px-2 py-0.5">RSS Feed</span>
        </a>
        <div
            class="flex bg-surface0 p-4 shadow shadow-crust mb-6 text-subtext1 items-center justify-between mx-4 lg:mx-0">
            <div class="flex items-center space-x-1">
                <span>Category:</span>
                <span class="text-blue font-bold">All</span>
                <span class="group cat-count" v-text="` (${posts?.meta.total ?? 0 } posts)`" />
            </div>
        </div>

        <div class="flex flex-col lg:flex-row items-start gap-y-6 lg:gap-6 w-full px-4 lg:px-0">
            <div class="w-full">
                <Deferred data="posts">
                    <template #fallback>
                        <LoadingPane />
                    </template>

                    <div v-if="posts.data.length === 0" class="w-full">
                        <div
                            class="flex h-32 w-full bg-crust/60 shadow shadow-surface0 text-yellow justify-center text-center items-center text-3xl space-x-2">
                            <exclamation-triangle-icon class="size-6 animate-pulse" />
                            <span>There are no posts to display. <span class="font-mono">:(</span></span>
                        </div>
                    </div>
                    <div v-else>
                        <div class="gap-4 w-full grid grid-cols-2">
                            <x-link :href="route('blog.show', { post })"
                                    class="w-full bg-surface0 p-4 shadow shadow-crust hover:bg-mantle group transition duration-150 ease-in"
                                    v-for="post in posts.data" :key="post.id">
                                <article class="space-y-2">
                                    <h1 class="font-semibold text-blue text-xl group-hover:underline">
                                        {{ post.title }}
                                    </h1>
                                    <p class="text-subtext0">
                                        Published
                                        <time v-if="moment().diff(post.published_at, 'days') <= 7"
                                              :datetime="post.published_at"
                                              :title="moment(post.published_at).format('Do MMM YYYY [at] hh:mma')"
                                              class="font-semibold text-text">
                                            {{ moment(new Date()).from(post.published_at, true) }} ago
                                        </time>
                                        <time v-else
                                              :datetime="post.published_at"
                                              :title="moment(post.published_at).format('Do MMM YYYY [at] hh:mma')"
                                              class="font-semibold text-text">
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
                                    <p class="text-subtext1">{{ post.excerpt }}</p>
                                </article>
                            </x-link>
                        </div>
                        <div class="mt-6">
                            <Pager :pagination="posts.meta" />
                        </div>
                    </div>
                </Deferred>
            </div>

            <TagPanel :tags="tags" />
        </div>
    </app-layout>
</template>

<style scoped lang="postcss">

.cat-count {
    @apply text-sm font-normal text-subtext0 group-hover:text-black;
    @apply transition duration-150 ease-in;
}

.tag {
    @apply font-bold font-mono text-blue cursor-pointer inline-flex items-center
    transition duration-150 ease-in;
}
</style>
