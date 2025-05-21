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

const common = 'flex items-center px-4 text-lg font-medium space-x-0.5 leading-5 focus:outline-none transition duration-150 ease-in-out';

const classes = computed(() =>
    props.active
        ? 'text-white bg-primary/60 focus:outline-none'
        : 'text-primary hover:text-white hover:bg-primary/60',
);
</script>

<template>
    <x-link :class="[common, classes]" :href="href">
        <span class="text-secondary">/</span>
        <span>
            <slot />
        </span>
    </x-link>
</template>
