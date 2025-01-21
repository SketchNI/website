<script setup>
import { reactive, ref, watch } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Bars3Icon, ChevronDownIcon, XMarkIcon } from '@heroicons/vue/16/solid';
import useUser from "@/Composables/useUser.js";
import useRole from "@/Composables/useRole.js";
import useApp from "@/Composables/useApp.js";
import mitt from 'mitt';

const emitter = mitt();

const user = useUser();
const role = useRole();
const app = useApp();
const showingNavigationDropdown = ref(false);

if (!localStorage.getItem('theme')) {
    localStorage.setItem('theme', app.theme);
}

const theme = reactive({ theme: localStorage.getItem('theme') });

watch(() => theme.theme, () => {
    window.axios.post(route('set-theme'), { theme: theme.theme }).then(() => {
        localStorage.setItem('theme', theme.theme);
    });
});
</script>

<template>
    <div :class="[theme.theme, 'bg-base min-h-full']">
        <nav class="bg-mantle font-mono">
            <!-- Primary Navigation Menu -->
            <div class="max-w-6xl lg:mx-auto">
                <div class="w-full">
                    <div class="flex items-center justify-between space-x-2">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <x-link :href="route('home')" class="text-3xl logo space-x-0">
                                    <span class="text-blue">Ske</span>
                                    <span class="text-red">tch</span>
                                    <span class="text-green">NI</span>
                                </x-link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-4 sm:-my-px sm:ms-4 sm:flex">
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
                                <nav-link :href="route('login')" :active="route().current('login')"
                                          v-if="user === null">
                                    Login
                                </nav-link>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center" v-if="user !== null">
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
                                    <dropdown-link :href="route('profile.edit')">
                                        Profile
                                    </dropdown-link>

                                    <dropdown-link :href="route('backend.index')"
                                                   v-if="role.includes('mod')"
                                                   class="text-red hover:bg-red hover:text-mantle">
                                        Backend
                                    </dropdown-link>

                                    <div class="h-px -mx-3 border-b border-mantle"></div>

                                    <dropdown-link :href="route('logout')" method="post" as="button">
                                        Log Out
                                    </dropdown-link>
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
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
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
                    <responsive-nav-link :href="route('login')" :active="route().current('login')" v-if="user === null">
                        Login
                    </responsive-nav-link>
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
                        <responsive-nav-link :href="route('profile.edit')">
                            Profile
                        </responsive-nav-link>
                        <responsive-nav-link :href="route('backend.index')"
                                             v-if="role.includes('mod')"
                                             class="text-red hover:bg-red hover:text-mantle">
                            Backend
                        </responsive-nav-link>
                        <responsive-nav-link :href="route('logout')" method="post" as="button">
                            Log Out
                        </responsive-nav-link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <main>
            <slot />
        </main>

        <footer
            :class="[app.url.includes('backend') ? 'lg:pl-[20rem] lg:pr-8' : 'max-w-6xl', 'my-6 pb-6 text-center md:flex text-subtext0 items-center justify-between mx-auto']">
            <div class="inline-flex space-x-3 items-center">
                <p class="text-sm">&copy; SketchNI {{ new Date().getFullYear() }}</p>

                <div>
                    <label for="theme" class="text-subtext0 pr-2">Theme</label>
                    <select name="theme" v-model="theme.theme" id="theme"
                            class="bg-crust text-text px-1 py-0.5 text-sm w-28 border border-overlay2">
                        <option value="mocha" :selected="theme.theme === 'mocha'">Mocha</option>
                        <option value="macchiato" :selected="theme.theme === 'macchiato'">Macchiato</option>
                        <option value="frappe" :selected="theme.theme === 'frappe'">Frappe</option>
                    </select>
                </div>
            </div>
            <p class="text-sm">
                <span>Built with </span>
                <a href="https://laravel.com" target="_blank" class="link">Laravel</a>,
                <a href="https://inertiajs.com/" target="_blank" class="link">InertiaJS</a>,
                <a href="https://vuejs.org" target="_blank" class="link">VueJS</a> and
                <a href="https://tailwindcss.com" target="_blank" class="link">TailwindCSS</a>
            </p>
        </footer>
    </div>
</template>
