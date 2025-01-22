<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineProps({
    exception: String,
    code: Number
})
</script>

<template>
    <x-head :title="`This Action Is Forbidden`" />
    <admin-layout>
        <div class="max-w-3xl">
            <div class="bg-red-400/40 p-8 font-medium text-center shadow-md shadow-red-400/70 space-y-3">
                <p class="text-xl">{{ exception }}</p>
                <p class="">This action has been logged.</p>
            </div>
        </div>
    </admin-layout>
</template>

<style scoped>

</style>
