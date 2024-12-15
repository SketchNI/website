<script setup>
defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <x-link :href="href"
        class="block w-full px-4 py-2 text-start leading-5 text-text hover:bg-base focus:outline-none focus:bg-mantle transition duration-150 ease-in-out">
        <slot />
    </x-link>
</template>
