<script setup lang="ts">
import { HomeModernIcon } from "@heroicons/vue/20/solid";

defineProps({
    breadcrumbs: Object
});
</script>

<template>
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <nav aria-label="Breadcrumb" class="flex space-x-4 items-center">
                <ol class="flex space-x-4 bg-gray-700 px-6 shadow-md shadow-black" role="list">
                    <li class="flex">
                        <div class="flex items-center">
                            <x-link :href="route('backend.index')" class="breadcrumb">
                                <home-modern-icon class="size-5" />
                                <span class="sr-only">Home</span>
                            </x-link>
                        </div>
                    </li>
                    <li class="flex" v-for="item in breadcrumbs" :key="item.name">
                        <div class="flex items-center">
                            <svg aria-hidden="true" class="h-full w-6 shrink-0 text-gray-500"
                                 fill="currentColor"
                                 preserveAspectRatio="none" viewBox="0 0 24 44">
                                <path d="M.293 0l22 22-22 22h1.414l22-22-22-22H.293z" />
                            </svg>
                            <x-link :href="item.route"
                                    :class="[item.active ? 'active' : '', 'breadcrumb ml-4 text-sm ']">
                                <span v-text="item.name" />
                            </x-link>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</template>

<style scoped>

</style>
