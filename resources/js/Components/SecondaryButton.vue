<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button :type="type"
            class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent font-semibold text-sm text-gray-200 uppercase tracking-widest hover:bg-gray-600 focus:text-white active:bg-gray-800 active:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-gray-900 focus:text-white focus:ring-offset-2 transition ease-in-out duration-150">
        <slot />
    </button>
</template>
