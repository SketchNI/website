<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import moment from 'moment/moment';
import Pager from "@/Components/Pager.vue";
import useApp from "@/Composables/useApp.js";
import { Deferred } from "@inertiajs/vue3";
import LoadingPane from "@/Pages/Blog/LoadingPane.vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    posts: {
        meta: Object,
        links: Object,
        data: Object,
    },
    categories: Object,
});

const app = useApp();
</script>

<template>
    <x-head title="Blog Posts" />

    <app-layout>
        <div class="flex bg-surface0 p-4 shadow shadow-crust mb-6 text-subtext1 items-center justify-between">
            <div class="flex items-center space-x-1">
                <span>Category:</span>
                <span class="text-blue font-bold">All</span>
                <span class="group cat-count" v-text="` (${posts?.meta.total ?? 0 } posts)`" />
            </div>
        </div>

        <div class="flex gap-6">
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
                    <div class="space-y-4 w-4/6 ">
                        <x-link :href="route('blog.show', { slug: post.slug })"
                                class="block bg-surface0 p-4 shadow shadow-crust hover:bg-mantle group transition duration-150 ease-in"
                                v-for="post in posts.data" :key="post.id">
                            <article class="space-y-2">
                                <h1 class="font-semibold text-blue text-xl group-hover:underline">{{ post.title }}</h1>
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
                                <p class="text-subtext1">{{ post.excerpt }}</p>
                            </article>
                        </x-link>

                        <Pager :pagination="posts.meta" />
                    </div>
                </div>
            </Deferred>
            <div class="bg-mantle w-2/6 p-4 shadow shadow-crust">
                <ul class="space-y-2 text-lg">
                    <li v-for="category in categories" :key="category.id">
                        <x-link :href="route('category.show', { category })" class="group parent category space-x-2">
                            <span v-text="category.name" />
                            <span class="group cat-count" v-text="`(${category.posts_count} posts)`" />
                        </x-link>
                        <ul v-if="category.children.length > 0">
                            <li v-for="(child, i) in category.children" :key="i">
                                <x-link :href="route('category.show', { category: child })" class="group child category space-x-2">
                                    <span v-text="child.name" />
                                    <span class="group cat-count" v-text="`(${child.posts_count} posts)`" />
                                </x-link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </app-layout>
</template>

<style scoped lang="postcss">
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
    @apply text-xs font-semibold text-base bg-blue px-2 py-1 shadow-sm shadow-overlay0 mx-1
    hover:bg-overlay0 hover:text-text hover:shadow-none uppercase tracking-wide;
    @apply transition duration-150 ease-in;
}
</style>
