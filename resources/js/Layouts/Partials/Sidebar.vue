<template>
    <div class="block md:inline">
        <x-link :href="route('home')" class="logo">
            <img src="/images/WebLogo.png" alt="SketchNI Logo" class="h-14" />
        </x-link>
    </div>
    <div class="flex flex-col space-y-2">
        <!-- Navigation Links -->
        <nav-link v-for="(link, i) in links" :key="i" :href="link.route" :active="route().current(link.route)">
            <span v-text="link.name" />
        </nav-link>

        <nav-a-link :href="route('auth', { driver: 'github' })" v-if="user === null">
            Login
        </nav-a-link>
        <div class="flex items-center" v-if="user !== null">
            <!-- Settings Dropdown -->
            <dropdown align="left" width="48">
                <template #trigger>
                    <button type="button"
                            class="flex items-center w-full border-l-4 border-transparent text-primary px-2 py-3 text-lg font-medium tracking-wide leading-5 hover:text-white hover:border-l-primary focus:outline-none transition duration-150 ease-in-out">
                        <span class="inline-flex items-center space-x-1.5">
                            <span class="text-secondary">/</span>
                            <span>{{ user.name }}</span>
                        </span>

                        <ChevronDownIcon class="me-1 size-4" />
                    </button>
                </template>

                <template #content>
                    <dropdown-link :href="route('backend.index')"
                                   v-if="isAdmin"
                                   class="text-red hover:bg-secondary hover:text-white">
                        Backend
                    </dropdown-link>

                    <div class="h-px border-b border-primary/50"></div>

                    <dropdown-a-link :href="route('logout')">
                        Log Out
                    </dropdown-a-link>
                </template>
            </dropdown>
        </div>
    </div>
    <div class="w-full">
        <lastfm />
    </div>
</template>

<script setup>
import NavLink from "@/Components/NavLink.vue";
import NavALink from "@/Components/NavALink.vue";
import Lastfm from "@/Components/Lastfm.vue";
import useUser from "@/Composables/useUser.js";
import DropdownLink from "@/Components/DropdownLink.vue";
import DropdownALink from "@/Components/DropdownALink.vue";
import { ChevronDownIcon } from "@heroicons/vue/16/solid/index.js";
import Dropdown from "@/Components/Dropdown.vue";
import useRole from "@/Composables/useRole.js";

defineProps({ links: Array });

const user = useUser();
const role = useRole();

const isAdmin = (['super-admin', 'admin', 'mod'].includes(role));
</script>
