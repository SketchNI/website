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

const common = 'border-l-4 border-transparent px-2 py-3 text-lg font-medium tracking-wide leading-5 hover:border-primary/60 focus:outline-none transition duration-150 ease-in-out';

const classes = computed(() =>
    props.active
        ? 'text-white px-4 border-l-primary focus:outline-none'
        : 'text-primary hover:text-white ',
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
