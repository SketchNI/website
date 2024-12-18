<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import useApp from "@/Composables/useApp.js";
import { HomeModernIcon } from "@heroicons/vue/20/solid";
import NoInfoPager from "@/Components/NoInfoPager.vue";
import moment from "moment";
import { usePage } from "@inertiajs/vue3";

defineProps({
    posts: Object,
    counts: {
        deleted: Number,
        published: Number,
        unpublished: Number,
    },
});

const app = useApp();
const page = usePage();
</script>

<template>
    <x-head title="Blog Posts Manager" />

    <admin-layout>
        <div class="mb-6 inline-flex space-x-2 items-end">
            <h1 :class="['font-semibold', app.theme === 'latte' ? 'text-black' : 'text-white']">
                Blog Posts
            </h1>
            <p class="text-sm text-subtext0">View and edit your blog posts.</p>
        </div>

        <div class="flex items-center justify-between mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol role="list" class="flex space-x-4 bg-surface0 px-6 shadow-md shadow-crust">
                    <li class="flex">
                        <div class="flex items-center">
                            <x-link :href="route('backend.index')" class="text-text hover:text-subtext0">
                                <home-modern-icon class="size-5" />
                                <span class="sr-only">Home</span>
                            </x-link>
                        </div>
                    </li>
                    <li class="flex">
                        <div class="flex items-center">
                            <svg class="h-full w-6 shrink-0 text-overlay0" viewBox="0 0 24 44"
                                 preserveAspectRatio="none"
                                 fill="currentColor" aria-hidden="true">
                                <path d="M.293 0l22 22-22 22h1.414l22-22-22-22H.293z" />
                            </svg>
                            <x-link :href="route('backend.blog.index')"
                                    class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Blog
                            </x-link>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="flex items-center space-x-6">
                <div class="flex space-x-4">
                    <x-link :href="route('backend.blog.index', { filter: 'unpublished' })"
                            class="inline-flex space-x-1 text-overlay2">
                        <span>Unpublished</span>
                        <span>(<span class="text-text">{{ counts.unpublished }}</span>)</span>
                    </x-link>
                    <x-link :href="route('backend.blog.index', { filter: 'deleted' })"
                            class="inline-flex space-x-1 text-overlay2">
                        <span>Deleted</span>
                        <span>(<span class="text-text">{{ counts.deleted }}</span>)</span>
                    </x-link>
                </div>
                <div v-if="posts.meta.total > posts.meta.per_page">
                    <no-info-pager :pagination="posts.meta" />
                </div>
            </div>
        </div>

        <div class="">
            <div class="flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div v-if="page.props.app.hasOwnProperty('flash') && page.props.app.flash !== null" class="my-4">
                            <div v-if="page.props.app.flash.type === 'success'"
                                 class="bg-green shadow shadow-crust text-base px-6 py-4">
                                {{ page.props.app.flash.message }}
                            </div>
                            <div v-else-if="page.props.app.flash.type === 'error'"
                                 class="bg-red shadow shadow-crust text-base px-6 py-4">
                                {{ page.props.app.flash.message }}
                            </div>
                        </div>

                        <div class="overflow-hidden shadow shadow-mantle ring-1 ring-mantle/5">
                            <table class="min-w-full divide-y divide-overlay0">
                                <thead class="bg-surface0">
                                <tr>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-text sm:pl-6 md:w-3/5">
                                        Title
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-text">
                                        Published
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-text">
                                        Created
                                    </th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-surface0 bg-surface2">
                                <tr v-for="(post, i) in posts.data" :key="i"
                                    :class="[i % 2 === 0 ? 'bg-surface0' : 'bg-surface1', 'hover:bg-surface2 select-none cursor-default']">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium md:w-3/5">
                                        <p class="text-text" v-text="post.title" />
                                        <p class="text-subtext0 font-normal" v-text="post.author.name" />
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm inline-flex items-center space-x-2">
                                        <p :class="post.published_at === null ? 'bg-red' : 'bg-green'"
                                           class="p-1.5 rounded-full " />
                                        <p v-text="moment(post.published_at).format('Do MMM YYYY [at] hh:mma')" />
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm"
                                        v-text="moment(post.created_at).format('Do MMM YYYY [at] hh:mma')" />
                                    <td class="relative whitespace-nowrap text-right text-sm font-medium pr-4">
                                        <x-link :href="route('backend.blog.show', { id: post.id})"
                                                class="bg-blue text-mantle hover:bg-base hover:text-text py-3 px-4 transition duration-150 ease-in"
                                                v-if="post.deleted_at === null">
                                            Edit
                                        </x-link>

                                        <x-link :href="route('backend.blog.restore', { id: post.id })"
                                                class="bg-red text-mantle hover:bg-base hover:text-text py-3 px-4 transition duration-150 ease-in"
                                                v-else>
                                            Restore
                                        </x-link>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<style scoped>

</style>
