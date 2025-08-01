<script setup>
import ImageTile from "@/Components/ImageTile.vue";
import { ref } from "vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { Deferred } from "@inertiajs/vue3";
import NoInfoPager from "@/Components/NoInfoPager.vue";
import { toast } from "vue3-toastify";

defineProps({
    images: {
        data: Object,
        meta: Object,
        links: Object,
    },
    breadcrumbs: Array,
});

const showResponse = ref(false);
const response = ref({});

window.mitt.on('image:update', e => {
    toast(e.message, { type: e.type })
})

</script>

<template>
    <x-head title="Image Manager" />

    <div class="mb-6 inline-flex space-x-2 items-end">
        <h1 class="font-semibold text-white">
            Images
        </h1>
        <p class="text-sm text-subtext0">View and edit uploaded images.</p>
    </div>

    <breadcrumb :breadcrumbs="breadcrumbs" />

    <div class="grid grid-cols-5 gap-4 mt-6">
        <div class="col-span-5 md:col-span-3 lg:col-span-1 p-4 shadow shadow-black space-y-3"
             :class="[image.from.toString().toLowerCase().includes('sharex') ? 'bg-blue-800/40' : 'bg-gray-800']"
             v-for="image in images.data" :key="image.id">
            <image-tile :image="image" />
        </div>
    </div>

    <Deferred data="images">
        <div v-if="images.meta.total > images.meta.per_page">
            <no-info-pager :pagination="images.meta" />
        </div>
        <template #fallback></template>
    </Deferred>
</template>

<style scoped>

</style>
