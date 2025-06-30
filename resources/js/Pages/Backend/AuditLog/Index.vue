<script setup>
import { usePage } from "@inertiajs/vue3";
import NoInfoPager from "@/Components/NoInfoPager.vue";
import LogEntry from "@/Components/AuditLog/LogEntry.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

const props = defineProps({
    logs: {
        data: Object,
        meta: Object,
        links: Object,
    },
    breadcrumbs: Array,
});

const page = usePage();
</script>

<template>
    <x-head title="Audit Logs" />

    <div class="mb-6 inline-flex space-x-2 items-end">
        <h1 class="font-semibold text-xl text-white">
            Audit Logs
        </h1>
        <p class="text-sm text-gray-300">View application audit logs.</p>
    </div>

    <breadcrumb :breadcrumbs="breadcrumbs" />

    <div class="mt-6">
        <div class="flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <table class="min-w-full !border-0">
                            <thead class="bg-gray-800">
                            <tr>
                                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6 lg:w-8"
                                    scope="col">
                                    Causer
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-white" scope="col">
                                    Target Type
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-white" scope="col">
                                    Target
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-white" scope="col">
                                    Type
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-white" scope="col">
                                    Description
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-white" scope="col">
                                    Event
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-white" scope="col">
                                    When
                                </th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="">
                            <tr v-for="(log, i) in logs.data" :key="log.id"
                                :class="[i % 2 === 0 ? 'bg-gray-700' : 'bg-gray-800', 'text-white hover:bg-gray-900 select-none cursor-default transition duration-150 ease-in']">
                                <log-entry :item="log" />
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="logs.meta.total > logs.meta.per_page">
                        <no-info-pager :pagination="logs.meta" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
