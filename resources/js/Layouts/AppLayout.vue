<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import DropdownALink from '@/Components/DropdownALink.vue';
import NavLink from '@/Components/NavLink.vue';
import NavALink from '@/Components/NavALink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import ResponsiveNavALink from '@/Components/ResponsiveNavALink.vue';
import { Bars3Icon, ChevronDownIcon, XMarkIcon } from '@heroicons/vue/16/solid';
import useUser from "@/Composables/useUser.js";
import useRole from "@/Composables/useRole.js";
import Theme from "@/Components/Theme.vue";
import Divider from "@/Components/divider.vue";
import { usePage } from "@inertiajs/vue3";

const user = useUser();
const role = useRole();
const page = usePage();

const isAdmin = (['super-admin', 'admin', 'mod'].includes(role))

const showingNavigationDropdown = ref(false);
const theme = ref(localStorage.getItem('theme'));

window.mitt.on('theme:update', (event) => {
    theme.value = event;
});

if (page.props.app.hasOwnProperty('flash') && page.props.app.flash !== null) {
    const flash = page.props.app.flash;

    if (flash.type === 'success') {
        console.info(flash.message);
    } else {
        console.error(flash.message);
    }
}
</script>

<template>
    <div :class="[theme, 'bg-base min-h-full transition duration-150 ease-in']">
        <nav class="bg-mantle font-mono">
            <!-- Primary Navigation Menu -->
            <div class="max-w-6xl lg:mx-auto">
                <div class="w-full">
                    <div class="flex items-center justify-between">
                        <div class="flex space-x-2">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <x-link :href="route('home')" class="text-3xl logo space-x-0">
                                    <img src="/images/WebLogo.png" alt="SketchNI Logo" class="h-14" />
                                </x-link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-2 lg:flex">
                                <nav-link :href="route('home')" :active="route().current('home')">
                                    Home
                                </nav-link>
                                <nav-link :href="route('blog.index')"
                                          :active="route().current('blog.index') || route().current('blog.show') || route().current('category.show')">
                                    Blog
                                </nav-link>
                                <nav-link :href="route('teams')" :active="route().current('teams')">
                                    Teams
                                </nav-link>
                                <nav-a-link :href="route('auth', { driver: 'github' })" v-if="user === null">
                                    Login
                                </nav-a-link>
                            </div>
                        </div>

                        <div class="hidden lg:flex lg:items-center" v-if="user !== null">
                            <!-- Settings Dropdown -->
                            <dropdown align="right" width="48">
                                <template #trigger>
                                    <button type="button"
                                            class="inline-flex -mt-px items-center px-4 py-[1.30rem] font-medium text-blue hover:bg-surface0 hover:text-text focus:bg-surface0 focus:outline-none transition ease-in-out duration-150">
                                        <span class="inline-flex items-center space-x-1.5">
                                            <span class="text-red">$ ./</span>
                                            <span>{{ user.name }}</span>
                                        </span>

                                        <ChevronDownIcon class="-me-0.5 ms-2 size-4" />
                                    </button>
                                </template>

                                <template #content>
                                    <dropdown-link :href="route('backend.index')"
                                                   v-if="isAdmin"
                                                   class="text-red hover:bg-red hover:text-mantle">
                                        Backend
                                    </dropdown-link>

                                    <div class="h-px -mx-3 border-b border-mantle"></div>

                                    <dropdown-a-link :href="route('logout')">
                                        Log Out
                                    </dropdown-a-link>
                                </template>
                            </dropdown>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center lg:hidden mr-6 lg:mr-0">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                    class="inline-flex items-center justify-center p-2 rounded-md text-text hover:bg-surface1 hover:shadow-sm hover:shadow-surface0/70 focus:outline-none focus:bg-surface2 transition duration-150 ease-in-out">
                                <bars3-icon class="size-6 inline-flex" v-if="!showingNavigationDropdown" />
                                <x-mark-icon class="size-6 inline-flex" v-if="showingNavigationDropdown" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="lg:hidden">
                <div class="space-y-1 pb-3 pt-2">
                    <responsive-nav-link :href="route('home')" :active="route().current('home')">
                        Home
                    </responsive-nav-link>
                    <responsive-nav-link :href="route('blog.index')"
                                         :active="route().current('blog.index') || route().current('blog.show')">
                        Blog
                    </responsive-nav-link>
                    <responsive-nav-link :href="route('teams')" :active="route().current('teams')">
                        Teams
                    </responsive-nav-link>
                    <responsive-nav-a-link :href="route('auth', { driver: 'github' })">
                        Login
                    </responsive-nav-a-link>
                </div>

                <!-- Responsive Settings Options -->
                <div class="border-t border-gray-200 pb-1 pt-4" v-if="user !== null">
                    <div class="px-4">
                        <div class="font-medium text-[1rem] text-text">
                            {{ user.name }}
                        </div>
                        <div class="font-medium text-sm text-overlay0">
                            {{ user.email }}
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <responsive-nav-link :href="route('backend.index')"
                                             v-if="isAdmin"
                                             class="text-red hover:bg-red hover:text-mantle">
                            Backend
                        </responsive-nav-link>
                        <responsive-nav-a-link :href="route('logout')">
                            Log Out
                        </responsive-nav-a-link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <main>
            <slot />
        </main>

        <divider class="h-[2px]" />

        <footer
            class="max-w-6xl my-6 pb-6 text-center md:flex text-subtext0 items-center justify-between lg:mx-auto space-y-3 mx-4 md:space-y-0">
            <p class="text-sm">&copy; SketchNI {{ new Date().getFullYear() }}</p>

            <theme />
        </footer>
    </div>
</template>
