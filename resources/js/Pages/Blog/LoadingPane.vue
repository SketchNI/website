<template>
    <div class="flex h-32 w-72 mx-auto items-center text-3xl space-x-2">
        <arrow-path-icon class="size-6 animate-spin-slow" />
        <span class="loading">Loading {{ resource }}</span>
    </div>
</template>
<script setup>
import { ArrowPathIcon } from "@heroicons/vue/24/solid";

defineProps({
    resource: {
        type: String,
        default: "posts",
    }
})
</script>
