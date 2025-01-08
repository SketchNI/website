<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import useApp from "@/Composables/useApp.js";
import { HomeModernIcon, PlusCircleIcon } from "@heroicons/vue/20/solid";
import { ArchiveBoxXMarkIcon, TrashIcon } from "@heroicons/vue/24/outline";
import NoInfoPager from "@/Components/NoInfoPager.vue";
import moment from "moment";
import { Deferred, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    posts: {
        data: Object,
        meta: Object,
        links: Object,
    },
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
            <nav aria-label="Breadcrumb" class="flex">
                <ol class="flex space-x-4 bg-surface0 px-6 shadow-md shadow-crust" role="list">
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
                            <svg aria-hidden="true" class="h-full w-6 shrink-0 text-overlay0"
                                 fill="currentColor"
                                 preserveAspectRatio="none" viewBox="0 0 24 44">
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
                <Deferred data="counts">
                    <div class="flex space-x-4">
                        <x-link :href="route('backend.blog.index', { filter: 'unpublished' })"
                                class="inline-flex space-x-1 text-overlay2">
                            <button class="inline-flex items-center gap-x-1.5 bg-surface1 px-3 py-2 text-sm font-semibold text-text shadow-sm shadow-crust hover:bg-surface2 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple transition duration-150 ease-in"
                                    type="button">
                                <archive-box-x-mark-icon class="size-5 shrink-0" />
                                <span>Unpublished</span>
                                <span class="bg-text text-black rounded-full px-1.5">{{ counts.unpublished }}</span>
                            </button>
                        </x-link>
                        <x-link :href="route('backend.blog.index', { filter: 'deleted' })"
                                class="inline-flex space-x-1 text-overlay2">
                            <button class="inline-flex items-center gap-x-1.5 bg-red px-3 py-2 text-sm font-semibold text-mantle shadow-sm shadow-crust group hover:bg-red-400 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple transition duration-150 ease-in"
                                    type="button">
                                <trash-icon class="size-5 shrink-0" />
                                <span>Deleted</span>
                                <span
                                    class="bg-red-500 group-hover:bg-red-600 text-white rounded-full px-1.5">{{
                                    counts.deleted
                                    }}</span>
                            </button>
                        </x-link>
                    </div>
                    <template #fallback></template>
                </Deferred>
                <Deferred data="posts">
                    <div v-if="posts.meta.total > posts.meta.per_page">
                        <no-info-pager :pagination="posts.meta" />
                    </div>
                    <template #fallback></template>
                </Deferred>

                <div>
                    <x-link :href="route('backend.blog.create')" class="inline-flex space-x-1 text-overlay2">
                        <primary-button class="space-x-1.5 transition duration-150 ease-in" type="button">
                            <plus-circle-icon class="size-5 shrink-0" />
                            <span>New Post</span>
                        </primary-button>
                    </x-link>
                </div>
            </div>
        </div>

        <div class="">
            <div class="flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div v-if="page.props.app.hasOwnProperty('flash') && page.props.app.flash !== null"
                             class="my-4">
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
                                    <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-text sm:pl-6 md:w-3/5"
                                        scope="col">
                                        Title
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                        Published
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                        Created
                                    </th>
                                    <th class="relative py-3.5 pl-3 pr-4 sm:pr-6" scope="col">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-surface0 bg-surface2">
                                <Deferred data="posts">
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
                                            <x-link v-if="post.deleted_at === null"
                                                    :href="route('backend.blog.edit', { id: post.id})"
                                                    class="bg-blue text-mantle hover:bg-base hover:text-text py-3 px-4 transition duration-150 ease-in">
                                                Edit
                                            </x-link>

                                            <x-link v-else
                                                    :href="route('backend.blog.edit', { id: post.id })"
                                                    class="bg-red text-mantle hover:bg-base hover:text-text py-3 px-4 transition duration-150 ease-in">
                                                Restore
                                            </x-link>
                                        </td>
                                    </tr>
                                    <template #fallback>
                                        <tr>
                                            <td class="text-center py-8 space-x-2 text-xl" colspan="4">
                                                <i class="fas fa-circle-notch animate-spin" />
                                                <span class="">Loading</span>
                                            </td>
                                        </tr>
                                    </template>
                                </Deferred>
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
