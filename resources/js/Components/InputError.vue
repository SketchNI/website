<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-secondary space-y-1">
            {{ message }}
        </p>
    </div>
</template>
