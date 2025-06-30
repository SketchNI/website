<template>
    <div
        class="z-30 mt-1 border border-r-0 w-44 text-right py-2 px-2 border-primary/40 bg-gray-800/70 text-white shadow-sm shadow-black"
        v-text="value" />
</template>
<script setup>
defineProps({
    value: String,
})
</script>
