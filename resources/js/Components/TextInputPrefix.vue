<template>
    <div
        class="mt-1 border-t border-b border-l w-40 text-right py-2 px-2 border-overlay1 font-medium bg-base text-subtext0 shadow-sm shadow-surface1"
        v-text="value" />
</template>
<script setup>
defineProps({
    value: String,
})
</script>
