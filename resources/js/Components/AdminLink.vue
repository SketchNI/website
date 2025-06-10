<script setup>
const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
    icon: String | Function,
    color: {
        type: String,
        default: 'blue'
    }
});

const colors = props.color === 'red' ? 'hover:bg-secondary/40 hover:border-secondary' : 'hover:bg-primary/40';
const active_colors = props.color === 'red' ? 'border-red text-white bg-red/20' : 'bg-primary/40 border-primary text-white';

</script>

<template>
    <li>
        <x-link :class="['group text-sm flex space-x-1 px-4 py-2 border-0 border-l-4 link transition duration-150 ease-in', colors, active ? active_colors : '']"
                :href="href">
            <component :is="icon" class="shrink-0 size-4" />
            <span>
                <slot />
            </span>
        </x-link>
    </li>
</template>
