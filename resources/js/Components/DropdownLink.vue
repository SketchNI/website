<script setup>
defineProps({
    href: {
        type: String,
        required: true,
    },
    element: {
        default: 'link'
    }
});
</script>

<template>
    <x-link :href="href" v-if="element === 'link'"
            class="block w-full px-4 py-2 text-start leading-5 text-text hover:bg-base focus:outline-none focus:bg-mantle transition duration-150 ease-in-out">
        <slot />
    </x-link>

    <a :href="href" v-if="element === 'a'"
            class="block w-full px-4 py-2 text-start leading-5 text-text hover:bg-base focus:outline-none focus:bg-mantle transition duration-150 ease-in-out">
        <slot />
    </a>
</template>
