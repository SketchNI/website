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
    ChartPieIcon,
    FlagIcon,
    NumberedListIcon,
    PhotoIcon,
    SquaresPlusIcon,
    UsersIcon as SolidUsersIcon
} from '@heroicons/vue/20/solid';
import useRole from "@/Composables/useRole.js";
import AdminLink from "@/Components/AdminLink.vue";
import Divider from "@/Components/divider.vue";
import Theme from "@/Components/Theme.vue";

const role = useRole();

const theme = ref(localStorage.getItem('theme'));

window.mitt.on('theme:update', (event) => {
    theme.value = event;
})

const menu = [
    {
        name: "General", links: [
            { name: "Home", route: 'backend.index', icon: HomeModernIcon, role: ['mod', 'admin', 'super-admin'] },
            { name: "Blog", route: 'backend.blog.index', icon: ListBulletIcon, role: ['mod', 'admin', 'super-admin'] },
            {
                name: "Users",
                route: 'backend.users.index',
                icon: SolidUsersIcon,
                role: ['mod', 'admin', 'super-admin']
            },
            { name: "Teams", route: 'backend.teams.index', icon: UsersIcon, role: ['mod', 'admin', 'super-admin'] },
            {
                name: "Pages",
                route: 'backend.pages.index',
                icon: DocumentTextIcon,
                role: ['mod', 'admin', 'super-admin']
            },
            {
                name: "Support",
                route: 'backend.support.index',
                icon: LifebuoyIcon,
                role: ['mod', 'admin', 'super-admin']
            },
            { name: "Images", route: 'backend.images.index', icon: PhotoIcon, color: 'red', role: ['super-admin'] },
        ],
    },
    {
        name: "Finance", links: [
            {
                name: "Invoices",
                route: 'backend.finance.invoices.index',
                icon: BanknotesIcon,
                role: ['mod', 'admin', 'super-admin']
            },
            {
                name: "Customers",
                route: 'backend.finance.customers.index',
                icon: UsersIcon,
                role: ['mod', 'admin', 'super-admin']
            },
        ]
    },
    {
        name: "Misc", links: [
            {
                name: "Audit Logs",
                route: 'backend.misc.audit-log.index',
                icon: NumberedListIcon,
                role: ['admin', 'super-admin']
            },
            {
                name: "Statistics",
                route: 'backend.misc.statistics.index',
                icon: ChartPieIcon,
                role: ['admin', 'super-admin']
            },
            { name: "Reports", route: 'backend.misc.reports.index', icon: FlagIcon, role: ['admin', 'super-admin'] },
            {
                name: "Scheduler",
                route: 'backend.misc.scheduler.index',
                icon: SquaresPlusIcon,
                role: ['mod', 'admin', 'super-admin']
            },
        ]
    }
]
</script>

<template>
    <div :class="[theme, 'bg-base min-h-full']">
        <div class="lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-crust bg-mantle">
                <div class="flex h-16 shrink-0 items-center sticky top-0 bg-mantle border-b-2 border-surface0">
                    <x-link :href="route('home')" class="logo space-x-0">
                        <img src="/images/WebLogo.png" alt="SketchNI Logo" class="h-14" />
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
            class="lg:pl-[20rem] lg:pr-8 my-6 pb-6 text-center md:flex text-subtext0 items-center justify-between mx-auto">
            <p class="text-sm">&copy; SketchNI {{ new Date().getFullYear() }}</p>

            <theme />
        </footer>
    </div>
</template>
