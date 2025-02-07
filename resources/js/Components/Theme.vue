<script setup>
import { ref, watch } from "vue";
import useApp from "@/Composables/useApp.js";
import { Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions } from "@headlessui/vue";
import { CheckIcon, ChevronUpDownIcon } from "@heroicons/vue/20/solid";

const app = useApp();

if (!localStorage.getItem('theme')) {
    localStorage.setItem('theme', app.theme);
}

const themes = [
    { name: 'frappe', label: 'Frappe' },
    { name: 'macchiato', label: 'Macchiato' },
    { name: 'mocha', label: 'Mocha' }
];

const detectTheme = () => {
    if (app.theme === "frappe") {
        return themes[0];
    } else if (app.theme === "macchiato") {
        return themes[1];
    } else if (app.theme === "mocha") {
        return themes[2];
    }
}

const themeRef = ref(app.theme);
const activeTheme = ref(detectTheme());

watch(() => activeTheme.value, (new_theme) => {
    themeRef.value = new_theme.name;

    window.axios.post(route('set-theme'), { theme: new_theme.name })
        .then(() => {
            localStorage.setItem('theme', themeRef.value);
            window.mitt.emit('theme:update', themeRef.value);
        })
});
</script>

<template>
    <div class="relative">
        <listbox v-model="activeTheme" name="theme">
            <listbox-label class="label">Theme</listbox-label>
            <listbox-button class="button">
                <span>{{ activeTheme.label }}</span>
                <chevron-up-down-icon class="size-5 text-gray-400" />
            </listbox-button>
            <listbox-options class="options">
                <listbox-option
                    v-for="(theme, i) in themes"
                    :key="i"
                    :value="theme"
                    as="ul"
                    v-slot="{ active, selected }">
                    <li :class="[active ? 'active' : '', selected ? 'selected' : '', 'option']">
                        <span>{{ theme.label }}</span>
                        <check-icon v-show="selected" />
                    </li>
                </listbox-option>
            </listbox-options>
        </listbox>
    </div>
</template>

<style scoped>
.button {
    @apply bg-mantle border border-l-0 border-blue px-3 py-1;
    @apply inline-flex items-center space-x-2;
    @apply transition duration-150 ease-in;
}

.label {
    @apply bg-mantle border border-r-0 border-blue pl-3 py-1.5 pt-[.45rem] pb-[.45rem];
}

.selected {
    @apply bg-blue text-crust px-3 -mx-3;
}

.active {
    @apply bg-blue text-crust -mx-3 px-3;
}

.option {
    @apply py-2 text-left flex items-center space-x-2;

    & svg {
        @apply size-4;
    }
}

.options {
    @apply absolute bottom-9 z-10 mt-1 cursor-pointer px-3 max-h-60 w-full overflow-auto rounded-md bg-crust py-1 text-left text-text shadow-lg ring-1
    ring-black/5 focus:outline-none sm:text-sm;
}
</style>
