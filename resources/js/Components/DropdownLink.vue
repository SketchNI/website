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
            class="block w-full px-4 py-2 text-start leading-5 text-white hover:bg-primary/60 focus:outline-none focus:bg-primary/60 transition duration-150 ease-in-out">
        <slot />
    </x-link>
</template>
