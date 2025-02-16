<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { HomeModernIcon } from "@heroicons/vue/20/solid/index.js";
import { usePage } from "@inertiajs/vue3";
import NoInfoPager from "@/Components/NoInfoPager.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    tickets: {
        data: Object,
        links: Object,
        meta: Object,
    }
});

const page = usePage();
</script>

<template>
    <x-head title="Support Tickets" />
    <admin-layout>
        <div class="mb-6 inline-flex space-x-2 items-end">
            <h1 class="font-semibold text-white">
                Support Tickets
            </h1>
            <p class="text-sm text-subtext0">View and reply to support tickets.</p>
        </div>

        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <nav aria-label="Breadcrumb" class="flex space-x-4 items-center">
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
                                <x-link :href="route('backend.support.index')"
                                        class="ml-4 text-lg font-medium text-gray-500 hover:text-gray-700 inline-flex items-center space-x-2">
                                    <i class="fas fa-ticket" />
                                    <span class="text-sm">Support Tickets</span>
                                </x-link>
                            </div>
                        </li>
                    </ol>
                </nav>
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
                                        Subject
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                        From
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                        Status
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                        Last Response
                                    </th>
                                    <th class="relative py-3.5 pl-3 pr-4 sm:pr-6" scope="col">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-surface0 bg-surface2">
                                <tr v-if="tickets.data.length === 0" class="bg-mantle">
                                    <td colspan="5">
                                        <div class="text-3xl text-blue flex justify-center items-center h-32">
                                            No data to show
                                        </div>
                                    </td>
                                </tr>
                                <tr v-else v-for="(ticket, i) in tickets.data" :key="i"
                                    :class="[i % 2 === 0 ? 'bg-surface0' : 'bg-surface1', 'hover:bg-surface2 select-none cursor-default']">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium md:w-3/5">
                                        <p class="text-text" v-text="ticket.subject" />
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-text">
                                        <p class="text-text">{{ ticket.user.name }}</p>
                                        <p class="text-subtext0 text-xs">{{ ticket.user.email }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-text">
                                        <p>{{ ticket.status.label }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-text">
                                        <p>{{ ticket.updated_at }}</p>
                                    </td>
                                    <td class="relative whitespace-nowrap text-right text-sm font-medium pr-4">
                                        <x-link :href="route('backend.support.index', ticket)">
                                            <primary-button class="text-xs px-1.5 py-2">
                                                View
                                            </primary-button>
                                        </x-link>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-if="tickets.data.length > 0 && tickets.meta.total > tickets.meta.per_page">
                            <no-info-pager :pagination="tickets.meta" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </admin-layout>
</template>
