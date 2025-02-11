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
    element: {
        default: 'link'
    }
});

const common = 'block w-full px-4 py-2 text-start font-black leading-5 transition duration-150 ease-in-out';

const classes = computed(() =>
    props.active
        ? 'text-text bg-surface0 focus:outline-none'
        : 'text-blue hover:text-text hover:bg-surface0 focus:outline-none',
);
</script>

<template>
    <x-link :href="href" :class="[common, classes]" v-if="element === 'link'">
        <slot />
    </x-link>

    <a :href="href" :class="[common, classes]" v-if="element === 'a'">
        <slot />
    </a>
</template>
