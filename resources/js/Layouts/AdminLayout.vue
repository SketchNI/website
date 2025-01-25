<script setup>
import { ref } from 'vue';
import {
    BanknotesIcon,
    DocumentTextIcon,
    HomeModernIcon,
    LifebuoyIcon,
    ListBulletIcon,
    UsersIcon,
} from "@heroicons/vue/24/outline";
import {
    BeakerIcon,
    BugAntIcon,
    ChartPieIcon,
    CpuChipIcon,
    FlagIcon,
    NumberedListIcon,
    PhotoIcon,
    PlayCircleIcon,
    SquaresPlusIcon,
    UsersIcon as SolidUsersIcon
} from '@heroicons/vue/20/solid';
import useUser from "@/Composables/useUser.js";
import useRole from "@/Composables/useRole.js";
import AdminLink from "@/Components/AdminLink.vue";
import Divider from "@/Components/divider.vue";
import useApp from "@/Composables/useApp.js";
import Theme from "@/Components/Theme.vue";

const app = useApp();
const user = useUser();
const role = useRole();
const showingNavigationDropdown = ref(false);

const theme = ref(localStorage.getItem('theme'));

window.mitt.on('theme:update', (event) => {
    theme.value = event;
})

const menu = [
    {
        name: "General", links: [
            { name: "Home", route: 'backend.index', icon: HomeModernIcon, role: ['mod', 'admin', 'super-admin'] },
            { name: "Blog", route: 'backend.blog.index', icon: ListBulletIcon, role: ['mod', 'admin', 'super-admin'] },
            { name: "Users", route: 'backend.users.index', icon: SolidUsersIcon, role: ['mod', 'admin', 'super-admin'] },
            { name: "Teams", route: 'backend.teams.index', icon: UsersIcon, role: ['mod', 'admin', 'super-admin'] },
            { name: "Pages", route: 'backend.pages.index', icon: DocumentTextIcon, role: ['mod', 'admin', 'super-admin'] },
            { name: "Support", route: 'backend.support.index', icon: LifebuoyIcon, role: ['mod', 'admin', 'super-admin'] },
            { name: "Images", route: 'backend.images.index', icon: PhotoIcon, color: 'red', role: ['super-admin'] },
        ],
    },
    {
        name: "Finance", links: [
            { name: "Invoices", route: 'backend.finance.invoices.index', icon: BanknotesIcon, role: ['mod', 'admin', 'super-admin'] },
            { name: "Customers", route: 'backend.finance.customers.index', icon: UsersIcon, role: ['mod', 'admin', 'super-admin'] },
        ]
    },
    {
        name: "Infrastructural", links: [
            { name: "Builds", route: 'backend.infrastructure.builds.index', icon: BeakerIcon, role: ['admin', 'super-admin'] },
            { name: "Releases", route: 'backend.infrastructure.releases.index', icon: PlayCircleIcon, role: ['admin', 'super-admin'] },
            { name: "Runners", route: 'backend.infrastructure.runners.index', icon: CpuChipIcon, role: ['admin', 'super-admin'] },
            { name: "Issues", route: 'backend.infrastructure.issues.index', icon: BugAntIcon, role: ['mod', 'admin', 'super-admin'] },
        ]
    },
    {
        name: "Misc", links: [
            { name: "Audit Logs", route: 'backend.misc.audit-log.index', icon: NumberedListIcon, role: ['admin', 'super-admin'] },
            { name: "Statistics", route: 'backend.misc.statistics.index', icon: ChartPieIcon, role: ['admin', 'super-admin'] },
            { name: "Reports", route: 'backend.misc.reports.index', icon: FlagIcon, role: ['admin', 'super-admin'] },
            { name: "Scheduler", route: 'backend.misc.scheduler.index', icon: SquaresPlusIcon, role: ['mod', 'admin', 'super-admin'] },
        ]
    }
]
</script>

<template>
    <div :class="[theme, 'bg-base h-full']">
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-crust bg-mantle">
                <div class="flex h-16 shrink-0 items-center sticky top-0 bg-mantle border-b-2 border-surface0">
                    <x-link :href="route('home')" class="text-3xl logo w-full">
                        <span class="text-blue">Ske</span>
                        <span class="text-red">tch</span>
                        <span class="text-green">NI</span>
                    </x-link>
                </div>
                <nav class="flex flex-1 flex-col">
                    <ul class="flex flex-1 flex-col gap-y-7 font-sans" role="list">
                        <li>
                            <ul class="space-y-1 overflow-y-auto min-h-full" role="list">
                                <li v-for="(cat, i) in menu" :key="i">
                                    <ul>
                                        <li class="px-6 py-1 tracking-widest text-subtext0 font-bold text-xs/6 uppercase">
                                            {{ cat.name }}
                                        </li>

                                        <li v-for="(item, i) in cat.links" :key="i">
                                            <admin-link v-if="item.role.includes(role)"
                                                        :active="route().current() === item.route"
                                                        :color="item.color ?? 'blue'"
                                                        :href="route(item.route)"
                                                        :icon="item.icon">
                                                {{ item.name }}
                                            </admin-link>
                                        </li>
                                    </ul>
                                    <ul class="py-2">
                                        <li>
                                            <divider class="from-mantle to-mantle via-blue/60 h-px" />
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Page -->
        <main class="admin-main text-text mx-auto py-10 lg:pl-72">
            <div class="px-4 sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>

        <footer
            :class="[app.url.includes('backend') ? 'lg:pl-[20rem] lg:pr-8' : 'max-w-6xl', 'my-6 pb-6 text-center md:flex text-subtext0 items-center justify-between mx-auto']">
            <div class="inline-flex space-x-3 items-center">
                <p class="text-sm">&copy; SketchNI {{ new Date().getFullYear() }}</p>

                <theme />
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
