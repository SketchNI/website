<script setup>
import { computed } from 'vue';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const common = 'flex items-center px-8 py-6 font-medium space-x-1 leading-5 focus:outline-none transition duration-150 ease-in-out';

const classes = computed(() =>
    props.active
        ? 'text-text bg-surface0 focus:outline-none'
        : 'text-blue hover:text-text hover:bg-surface0',
);
</script>

<template>
    <x-link :href="href" :class="[common, classes]">
        <span class="text-red">/</span>
        <span><slot /></span>
    </x-link>
</template>
