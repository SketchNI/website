<script setup>
import moment from "moment/moment";
import { PlusCircleIcon } from "@heroicons/vue/20/solid/index.js";
import NoInfoPager from "@/Components/NoInfoPager.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Deferred } from "@inertiajs/vue3";
import Breadcrumb from "@/Components/Breadcrumb.vue";

defineProps({
    pages: {
        data: Object,
        meta: Object,
        links: Object,
    },
    counts: Object,
    breadcrumbs: {}
})
</script>

<template>
    <x-head title="Pages List" />

    <div class="mb-6 inline-flex space-x-2 items-end">
        <h1 class="font-semibold text-white">
            Pages
        </h1>
        <p class="text-sm text-gray-300">View and edit your pages.</p>
    </div>

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <breadcrumb :breadcrumbs="breadcrumbs" />

            <Deferred data="counts">
                <div class="flex items-center">
                    <x-link :href="route('backend.pages.index')"
                            class="group filter-link">
                        <span>Pages</span>
                        <span class="filter-counter text-green">{{ counts.pages }}</span>
                    </x-link>

                    <div class="h-8 border-r mx-3 border-gray-600"></div>

                    <x-link :href="route('backend.pages.index', { filter: 'unpublished' })"
                            class="group filter-link">
                        <span>Unpublished</span>
                        <span class="filter-counter text-yellow">{{ counts.unpublished }}</span>
                    </x-link>

                    <div class="h-8 border-r mx-3 border-gray-600"></div>

                    <x-link :href="route('backend.pages.index', { filter: 'deleted' })" class="group filter-link">
                        <span>Deleted</span>
                        <span class="filter-counter text-red">{{ counts.deleted }}</span>
                    </x-link>
                </div>
                <template #fallback></template>
            </Deferred>
        </div>

        <div>
            <x-link :href="route('backend.pages.create')" class="inline-flex space-x-1 text-overlay2">
                <primary-button class="space-x-1.5 normal-case text-sm" type="button">
                    <plus-circle-icon class="size-5 shrink-0" />
                    <span class="text-sm">New Page</span>
                </primary-button>
            </x-link>
        </div>
    </div>

    <div class="">
        <div class="flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden ring-1 ring-primary/5">
                        <table class="min-w-full divide-y divide-gray-500">
                            <thead class="bg-gray-800">
                            <tr>
                                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-200 sm:pl-6 md:w-3/5"
                                    scope="col">
                                    Title
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-200" scope="col">
                                    Published
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-200" scope="col">
                                    Created
                                </th>
                                <th class="relative py-3.5 pl-3 pr-4 sm:pr-6" scope="col">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                            <tr v-if="pages.data.length === 0" class="bg-gray-600">
                                <td colspan="4">
                                    <div class="text-3xl text-primary flex justify-center items-center h-32">
                                        No data to show
                                    </div>
                                </td>
                            </tr>
                            <tr v-else v-for="(page, i) in pages.data" :key="i"
                                :class="[i % 2 === 0 ? 'bg-gray-700' : 'bg-gray-800', 'hover:bg-gray-900 select-none cursor-default transition duration-150 ease-in']">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium md:w-3/5">
                                    <p class="text-white" v-text="page.title" />
                                    <p class="text-gray-300 font-normal" v-text="page.author.name" />
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm space-x-2">
                                    <div class=" inline-flex items-center space-x-2">
                                        <span :class="page.is_published ? 'bg-red' : 'bg-green'"
                                              class="size-2.5 rounded-full inline-block" />
                                        <span v-if="page.is_published"
                                              v-text="moment(page.published_at).format('Do MMM YYYY [at] hh:mma')" />
                                        <span v-else class="italic">- Not Published -</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm"
                                    v-text="moment(page.created_at).format('Do MMM YYYY [at] hh:mma')" />
                                <td class="relative whitespace-nowrap text-right text-sm font-medium pr-4">
                                    <x-link v-if="!page.is_deleted"
                                            :href="route('backend.pages.edit', { id: page.id })"
                                            class="bg-primary text-white hover:bg-primary-dark py-3 px-4 transition duration-150 ease-in">
                                        Edit
                                    </x-link>

                                    <x-link v-else
                                            :href="route('backend.pages.show', { page })"
                                            class="bg-secondary text-black hover:bg-secondary/60 hover:text-white py-3 px-4 transition duration-150 ease-in">
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

        <div v-if="pages.meta.total > pages.meta.per_page" class="flex justify-end">
            <no-info-pager :pagination="pages.meta" />
        </div>
    </div>
</template>

<style scoped>
.filter-link {
    @apply flex items-center space-x-2 px-2 py-2 text-gray-300 hover:text-white hover:bg-gray-600/50
    transition duration-150 ease-in;
}

.filter-counter {
    @apply bg-primary/50 font-black font-mono group-hover:bg-primary/60 rounded-full px-2 py-0.5 text-sm;
    @apply transition duration-150 ease-in;
}
</style>
