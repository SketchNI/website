<script setup>
import { PlusCircleIcon } from "@heroicons/vue/24/outline";
import useApp from "@/Composables/useApp.js";
import { Deferred, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import useUser from "@/Composables/useUser.js";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

defineProps({
    teams: Object,
    breadcrumbs: Array,
});

const app = useApp();
const page = usePage();
const user = useUser();
</script>

<template>
    <x-head title="Team Manager" />

    <div class="mb-6 inline-flex space-x-2 items-end">
        <h1 :class="['font-semibold', app.theme === 'latte' ? 'text-black' : 'text-white']">
            Teams
        </h1>
        <p class="text-sm text-subtext0">View and edit your teams.</p>
    </div>

    <div class="flex items-center justify-between space-x-6 mb-6">
        <breadcrumb :breadcrumbs="breadcrumbs" />

        <div>
            <x-link :href="route('backend.teams.create')" class="inline-flex space-x-1 text-overlay2">
                <primary-button class="space-x-1.5 transition duration-150 ease-in" type="button">
                    <plus-circle-icon class="size-5 shrink-0" />
                    <span>New Team</span>
                </primary-button>
            </x-link>
        </div>
    </div>

    <div class="">
        <div class="flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow shadow-mantle ring-1 ring-mantle/5">
                        <table class="min-w-full divide-y divide-overlay0">
                            <thead class="bg-surface0">
                            <tr>
                                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-text sm:pl-6"
                                    scope="col">
                                    Name
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                    Description
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                    GitHub
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                    Website
                                </th>
                                <th class="relative py-3.5 pl-3 pr-4 sm:pr-6" scope="col">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-surface0 bg-surface2">
                            <Deferred data="teams">
                                <tr v-if="teams.data.length === 0" class="bg-mantle">
                                    <td colspan="5">
                                        <div class="text-3xl text-blue flex justify-center items-center h-32">
                                            No data to show
                                        </div>
                                    </td>
                                </tr>
                                <tr v-else v-for="(team, i) in teams.data" :key="i"
                                    :class="[i % 2 === 0 ? 'bg-surface0' : 'bg-surface1', 'hover:bg-surface2 select-none cursor-default']">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 font-medium">
                                        <p class="text-text" v-text="team.name" />
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        <p class="truncate w-[35ch]">{{ team.description }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4">
                                        <x-link :href="`https://github.com/${team.github}`" class="link"
                                                target="_blank">
                                            <span>{{ team.github }}</span>
                                            <i class="fas fa-external-link-alt"></i>
                                        </x-link>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4">
                                        <x-link :href="team.website" class="link" target="_blank">
                                            <span>{{ team.website }}</span>
                                            <i class="fas fa-external-link-alt"></i>
                                        </x-link>
                                    </td>
                                    <td class="relative whitespace-nowrap text-right text-sm font-medium pr-4">
                                        <x-link
                                            :href="route('backend.teams.edit', { id: team.id})"
                                            class="bg-blue text-mantle hover:bg-base hover:text-text py-3 px-4 transition duration-150 ease-in">
                                            Edit
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
</template>

<style lang="postcss" scoped>
.link {
    @apply inline-flex space-x-1 items-start;

    & > span {
        @apply text-blue;
    }

    & > .svg-inline--fa {
        @apply size-3 text-gray-300;
    }
}
</style>
