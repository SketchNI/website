<script setup>
defineProps({
    disabled: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <button
        :disabled="disabled"
        class="inline-flex items-center px-4 py-2 bg-blue border border-transparent font-semibold text-sm text-base uppercase tracking-widest hover:bg-overlay0 focus:text-text active:bg-base active:text-text focus:outline-none focus:ring-2 focus:ring-blue focus:ring-offset-crust focus:ring-offset-2 transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
