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
            class="inline-flex items-center px-4 py-2 bg-surface0 border border-transparent font-semibold text-sm text-subtext1 uppercase tracking-widest hover:bg-overlay0 focus:text-text active:bg-base active:text-text focus:outline-none focus:ring-2 focus:ring-blue focus:ring-offset-crust focus:text-subtext1 focus:ring-offset-2 transition ease-in-out duration-150">
        <slot />
    </button>
</template>
