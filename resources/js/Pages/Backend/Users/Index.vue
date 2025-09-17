<script setup>
import moment from "moment/moment";
import { Deferred, usePage } from "@inertiajs/vue3";
import useApp from "@/Composables/useApp.js";
import LoadingPane from "@/Pages/Blog/LoadingPane.vue";
import NoInfoPager from "@/Components/NoInfoPager.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

const props = defineProps({
    users: {
        data: Object,
        meta: Object,
        links: Object,
    },
    breadcrumbs: Object,
})

const page = usePage();
const app = useApp();

</script>

<template>
    <x-head title="User Manager" />

    <div class="mb-6 inline-flex space-x-2 items-end">
        <h1 class="font-semibold text-xl text-white">
            Users
        </h1>
        <p class="text-sm text-gray-400">View and edit registered users.</p>
    </div>

    <breadcrumb :breadcrumbs="breadcrumbs" />

    <div class="mt-6">
        <div class="flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-500">
                            <thead class="bg-gray-800">
                            <tr>
                                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-200 sm:pl-6 lg:w-8"
                                    scope="col">
                                    ID
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-200" scope="col">
                                    Name
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-200" scope="col">
                                    Verified
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-200" scope="col">
                                    Created
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-200" scope="col">
                                    Role
                                </th>
                                <th class="relative py-3.5 pl-3 pr-4 sm:pr-6" scope="col">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                            <Deferred data="users">
                                <tr v-for="(user, i) in users.data" :key="user.id"
                                    :class="[i % 2 === 0 ? 'bg-gray-700' : 'bg-gray-800', 'hover:bg-gray-900 select-none cursor-default transition duration-150 ease-in']">
                                    <td class="pl-4 pr-3 text-sm font-medium text-center text-white">
                                        {{ user.id }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-medium">
                                        <p class="text-white" v-text="user.name" />
                                        <p class="text-gray-300 font-normal" v-text="user.email" />
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm space-x-2">
                                        <div class="inline-flex items-center text-gray-300 space-x-2">
                                            <span :class="user.email_verified ? 'bg-green' : 'bg-red'"
                                                  class="size-2.5 rounded-full inline-block" />
                                            <span v-text="user.email_verified ? 'Verified' : 'Unverified'" />
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-gray-300 text-sm"
                                        v-text="moment(user.created_at).format('Do MMM YYYY [at] hh:mma')" />
                                    <td class="whitespace-nowrap px-3 py-4 text-gray-300 text-sm"
                                        v-text="user.role.display_name" />
                                    <td class="relative whitespace-nowrap text-right text-sm font-medium pr-4">
                                        <x-link :href="route('backend.users.edit', { user })"
                                                class="bg-primary text-white hover:bg-primary-dark py-3 px-4 transition duration-150 ease-in">
                                            Edit
                                        </x-link>
                                    </td>
                                </tr>
                                <template #fallback>
                                    <tr>
                                        <td class="py-8 w-full" colspan="6">
                                            <LoadingPane resource="users" />
                                        </td>
                                    </tr>
                                </template>
                            </Deferred>
                            </tbody>
                        </table>
                    </div>

                    <Deferred data="users">
                        <div v-if="users.meta.total > users.meta.per_page">
                            <no-info-pager :pagination="users.meta" />
                        </div>
                        <template #fallback></template>
                    </Deferred>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
