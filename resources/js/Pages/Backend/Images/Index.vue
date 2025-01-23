<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ImageTile from "@/Components/ImageTile.vue";
import { ref } from "vue";
import { HomeModernIcon, PlusCircleIcon } from "@heroicons/vue/20/solid/index.js";

defineProps({
    images: Object,
});

const showResponse = ref(false);
const response = ref({});

window.mitt.on('image:update', e => {
    response.value = { type: e.type, message: e.message };
    showResponse.value = true;

    setTimeout(() => {
        showResponse.value = false;
    }, 5000);
})

</script>

<template>
    <x-head title="Image Manager" />

    <admin-layout>
        <div class="mb-6 inline-flex space-x-2 items-end">
            <h1 class="font-semibold text-white">
                Images
            </h1>
            <p class="text-sm text-subtext0">View and edit uploaded images.</p>
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
                                <x-link :href="route('backend.images.index')"
                                        class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Images
                                </x-link>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div v-if="showResponse" class="mb-4">
            <div v-if="response.type === 'success'"
                 class="bg-green shadow shadow-crust text-base px-6 py-4">
                {{ response.message }}
            </div>
            <div v-else-if="response.type === 'error'"
                 class="bg-red shadow shadow-crust text-base px-6 py-4">
                {{ response.message }}
            </div>
        </div>

        <div class="grid grid-cols-5 gap-4">
            <div class="col-span-5 md:col-span-3 lg:col-span-1 p-4 shadow-sm shadow-overlay0 space-y-3"
                 :class="[image.from === 'sharex' ? 'bg-red-700/40' : 'bg-crust']"
                 v-for="image in images" :key="image.id">
                <image-tile :image="image" />
            </div>
        </div>
    </admin-layout>
</template>

<style scoped>

</style>
