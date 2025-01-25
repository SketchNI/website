<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import moment from "moment/moment";
import { HomeModernIcon } from "@heroicons/vue/20/solid/index.js";
import { Deferred, usePage } from "@inertiajs/vue3";
import useUser from "@/Composables/useUser.js";
import useApp from "@/Composables/useApp.js";
import LoadingPane from "@/Pages/Blog/LoadingPane.vue";

defineProps({
    users: {
        data: Object,
        meta: Object,
        links: Object,
    },
})

const page = usePage();
const app = useApp();
const user = useUser();


</script>

<template>
    <x-head title="User Manager" />

    <admin-layout>
        <div class="mb-6 inline-flex space-x-2 items-end">
            <h1 class="font-semibold text-white">
                Users
            </h1>
            <p class="text-sm text-subtext0">View and edit registered users.</p>
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
                                <x-link :href="route('backend.users.index')"
                                        class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                    Users
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
                                            v-text="user.role" />
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
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<style scoped>

</style>
