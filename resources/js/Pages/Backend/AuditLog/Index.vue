<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { HomeModernIcon } from "@heroicons/vue/20/solid/index.js";
import { usePage } from "@inertiajs/vue3";
import NoInfoPager from "@/Components/NoInfoPager.vue";
import moment from "moment/moment";

const props = defineProps({
    logs: {
        data: Object,
        meta: Object,
        links: Object,
    },
});

console.log(props.logs);

const page = usePage();
</script>

<template>
    <x-head title="Audit Logs" />

    <admin-layout>
        <div class="mb-6 inline-flex space-x-2 items-end">
            <h1 class="font-semibold text-white">
                Audit Logs
            </h1>
            <p class="text-sm text-subtext0">View application audit logs.</p>
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
                                <x-link :href="route('backend.misc.audit-log.index')"
                                        class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                    Audit Logs
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
                                    <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-text sm:pl-6 lg:w-8"
                                        scope="col">
                                        Causer
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                        Target Type
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                        Target
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                        Type
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                        Event
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                        When
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-surface0 bg-surface2">
                                <tr v-for="(log, i) in logs.data" :key="log.id"
                                    :class="[i % 2 === 0 ? 'bg-surface0' : 'bg-surface1', 'hover:bg-surface2 select-none cursor-default']">
                                    <td class="w-2/12 py-2 pl-4 pr-3 text-sm font-medium">
                                        <p class="text-text" v-text="log.causer.name" />
                                        <p class="text-subtext0 text-xs font-normal" v-text="log.causer.email" />
                                    </td>
                                    <td class="w-24 pl-4 pr-3 py-2 text-sm font-medium">
                                        <div v-if="log.subject_type !== null">
                                            <p class="text-text" v-text="log.subject_type.replace('App\\Models\\', '')" />
                                        </div>
                                    </td>
                                    <td class="w-2/12 pl-4 pr-3 py-2 text-sm font-medium">
                                        <div v-if="log.subject_id !== null">
                                            <p class="text-text" v-text="log.subject.name" />
                                            <p class="text-subtext0 text-xs font-normal" v-text="log.subject.email" />
                                        </div>
                                    </td>
                                    <td class="w-24 whitespace-nowrap px-3 py-2 text-sm font-medium text-text">
                                        <p class="text-text" v-text="log.log_name" />
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2 text-sm font-medium text-text">
                                        <p class="text-text" v-text="log.description" />
                                    </td>
                                    <td class="w-44 whitespace-nowrap px-3 text-sm"
                                            v-text="moment(log.created_at).format('Do MMM YYYY [at] hh:mma')" />
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-if="logs.total > logs.per_page">
                            <no-info-pager :pagination="logs" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<style scoped>

</style>
