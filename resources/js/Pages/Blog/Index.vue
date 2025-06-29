<script setup>
import moment from 'moment/moment';
import Pager from "@/Components/Pager.vue";
import { Deferred } from "@inertiajs/vue3";
import LoadingPane from "@/Pages/Blog/LoadingPane.vue";
import { ExclamationTriangleIcon, RssIcon } from "@heroicons/vue/24/solid";
import TagPanel from "@/Components/TagPanel.vue";

const props = defineProps({
    posts: {
        meta: Object | null,
        links: Object,
        data: Object,
    } | null,
    tags: Object,
});

</script>

<template>
    <x-head title="Blog Posts" />

    <div
        class="flex bg-zinc-800 p-4 shadow shadow-black mb-6 text-white items-center justify-between mx-4 lg:mx-0">
        <div class="flex items-center space-x-1">
            <span>Category:</span>
            <span class="text-primary font-bold">All</span>
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
                        class="flex h-32 w-full bg-zinc-800 shadow shadow-black text-secondary justify-center text-center items-center text-3xl space-x-2">
                        <exclamation-triangle-icon class="size-6 animate-pulse" />
                        <span>There are no posts to display. <span class="font-mono">:(</span></span>
                    </div>
                </div>
                <div v-else>
                    <div class="gap-4 w-full grid grid-cols-2">
                        <x-link :href="route('blog.show', { post })"
                                class="w-full bg-gray-800 p-4 shadow shadow-black hover:bg-primary/50 group transition duration-150 ease-in"
                                v-for="post in posts.data" :key="post.id">
                            <article class="space-y-2">
                                <h1 class="font-semibold text-primary text-xl group-hover:underline">
                                    {{ post.title }}
                                </h1>
                                <p class="text-gray-400">
                                    Published
                                    <time v-if="moment().diff(post.published_at, 'days') <= 7"
                                          :datetime="post.published_at"
                                          :title="moment(post.published_at).format('Do MMM YYYY [at] hh:mma')"
                                          class="font-semibold text-gray-200">
                                        {{ moment(new Date()).from(post.published_at, true) }} ago
                                    </time>
                                    <time v-else
                                          :datetime="post.published_at"
                                          :title="moment(post.published_at).format('Do MMM YYYY [at] hh:mma')"
                                          class="font-semibold text-text">
                                        {{ moment(post.published_at).format('Do MMM YYYY [at] hh:mma') }}
                                    </time>
                                    by
                                    <span class="font-semibold font-mono text-primary">{{ post.author.name }}</span>
                                    <span v-if="post.categories?.length > 0">
                                        in
                                        <span v-for="(cat, i) in post.tags" :key="i">
                                            <x-link :href="route('category.show', { slug: cat.slug })" class="tag">
                                                {{ cat.name }}
                                            </x-link>
                                        </span>
                                    </span>
                                </p>
                                <p class="text-gray-200">{{ post.excerpt }}</p>
                            </article>
                        </x-link>
                    </div>
                    <div class="mt-6">
                        <Pager :pagination="posts.meta" />
                    </div>
                </div>
            </Deferred>
        </div>

        <TagPanel :tags="tags" type="post" />

    </div>
</template>

<style scoped lang="postcss">
.cat-count {
    @apply text-sm font-normal text-gray-400;
    @apply transition duration-150 ease-in;
}

.tag {
    @apply font-bold font-mono text-primary cursor-pointer inline-flex items-center
    transition duration-150 ease-in;
}
</style>
