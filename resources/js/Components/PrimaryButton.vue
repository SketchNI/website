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
        class="inline-flex items-center px-4 py-2 bg-primary border border-transparent font-semibold text-sm text-black uppercase tracking-widest hover:text-white hover:bg-primary/60 focus:text-black active:bg-primary active:text-black focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-gray-900 focus:ring-offset-2 transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
