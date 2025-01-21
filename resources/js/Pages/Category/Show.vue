<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import moment from 'moment';
import Pager from "@/Components/Pager.vue";

const props = defineProps({
    posts: {
        data: Object,
        meta: Object,
        links: Object,
    },
    categories: Object,
    current: String,
})

const log = (date) => {
    console.log(date);
}
</script>

<template>
    <x-head title="Blog Posts" />

    <app-layout>
        <div class="flex bg-surface0 p-4 shadow shadow-crust mb-6 text-subtext1 items-center justify-between">
            <div class="flex items-center space-x-1">
                <span>Category:</span>
                <span class="text-blue font-bold">{{ current }}</span>
                <span class="group cat-count" v-text="` (${posts.data.length} posts)`" />
            </div>

            <div>
                <x-link :href="route('blog.index')" class="text-red px-2 py-2 hover:bg-red hover:text-black">Clear
                    filter
                </x-link>
            </div>
        </div>

        <div class="flex gap-6">
            <div class="space-y-4 w-4/6 ">
                <x-link v-for="post in posts.data"
                        :key="post.id"
                        :href="route('blog.show', { slug: post.slug })" class="block bg-surface0 p-4 shadow shadow-crust hover:bg-mantle group transition duration-150 ease-in">
                    <article class="space-y-2">
                        <h1 class="font-semibold text-blue text-xl group-hover:underline">{{ post.title }}</h1>
                        <p class="text-subtext0">
                            Published
                            <time v-if="moment().diff(post.created_at, 'days') <= 7"
                                  :datetime="post.created_at"
                                  :title="moment(post.created_at).format('Do MMM YYYY [at] hh:mma')"
                                  class="font-semibold">
                                {{ moment(new Date()).from(post.created_at, true) }} ago
                            </time>
                            <time v-else
                                  :datetime="post.created_at"
                                  :title="moment(post.created_at).format('Do MMM YYYY [at] hh:mma')"
                                  class="font-semibold">
                                {{ moment(post.created_at).format('Do MMM YYYY [at] hh:mma') }}
                            </time>
                            by
                            <span class="font-semibold font-mono">{{ post.author.name }}</span>
                            <span v-if="post.categories?.length > 0">
                                in
                                <span v-for="(cat, i) in post.categories">
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

<style scoped>
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
