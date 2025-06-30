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
        <h1 class="font-semibold text-white">
            Users
        </h1>
        <p class="text-sm text-subtext0">View and edit registered users.</p>
    </div>

    <breadcrumb :breadcrumbs="breadcrumbs" />

    <div class="mt-6">
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
                                    ID
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                    Name
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                    Verified
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                    Created
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                    Role
                                </th>
                                <th class="relative py-3.5 pl-3 pr-4 sm:pr-6" scope="col">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-surface0 bg-surface2">
                            <Deferred data="users">
                                <tr v-for="(user, i) in users.data" :key="user.id"
                                    :class="[i % 2 === 0 ? 'bg-surface0' : 'bg-surface1', 'hover:bg-surface2 select-none cursor-default']">
                                    <td class="pl-4 pr-3 text-sm font-medium text-center">
                                        {{ user.id }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2 text-sm font-medium">
                                        <p class="text-text" v-text="user.name" />
                                        <p class="text-subtext0 text-xs font-normal" v-text="user.email" />
                                    </td>
                                    <td class="whitespace-nowrap px-3 text-sm space-x-2">
                                        <div class="inline-flex items-center space-x-2">
                                            <span :class="user.email_verified ? 'bg-green' : 'bg-red'"
                                                  class="size-2.5 rounded-full inline-block" />
                                            <span v-text="user.email_verified ? 'Verified' : 'Unverified'" />
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 text-sm"
                                        v-text="moment(user.created_at).format('Do MMM YYYY [at] hh:mma')" />
                                    <td class="whitespace-nowrap px-3 text-sm"
                                        v-text="user.role.display_name" />
                                    <td class="relative whitespace-nowrap text-right text-sm font-medium pr-4">
                                        <x-link :href="route('backend.users.edit', { user })"
                                                class="bg-blue text-mantle hover:bg-base hover:text-text py-2 px-4 transition duration-150 ease-in">
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
