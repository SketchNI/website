<script setup>
import { computed, ref, watch } from 'vue';
import {
    HomeModernIcon,
    ListBulletIcon,
    DocumentIcon,
    UsersIcon,
} from "@heroicons/vue/24/outline";
import {
    NumberedListIcon,
    PhotoIcon,
    SquaresPlusIcon,
    UsersIcon as SolidUsersIcon
} from '@heroicons/vue/20/solid';
import useRole from "@/Composables/useRole.js";
import AdminLink from "@/Components/AdminLink.vue";
import Divider from "@/Components/divider.vue";
import { toast } from "vue3-toastify";
import { usePage } from "@inertiajs/vue3";

const role = useRole();
const page = usePage();

const flash = computed(() => page.props.app?.flash);

// Watch for changes in the flash prop
watch(flash, (newFlash) => {
    if (newFlash?.message) {
        toast(newFlash.message, { type: newFlash.type || 'default', theme: 'dark' });
    }
}, { deep: true, immediate: true });

const wiggle = ref('💝');

const mouseOver = () => {
    wiggle.value = '💖';
}

const mouseOut = () => {
    wiggle.value = '💝';
}

const menu = [
    {
        name: "General", links: [
            { name: "Home", route: 'backend.index', icon: HomeModernIcon, role: ['mod', 'admin', 'super-admin'] },
            { name: "Blog", route: 'backend.blog.index', icon: ListBulletIcon, role: ['mod', 'admin', 'super-admin'] },
            { name: "Pages", route: 'backend.pages.index', icon: DocumentIcon, role: ['mod', 'admin', 'super-admin'] },
            {
                name: "Users",
                route: 'backend.users.index',
                icon: SolidUsersIcon,
                role: ['mod', 'admin', 'super-admin']
            },
            { name: "Teams", route: 'backend.teams.index', icon: UsersIcon, role: ['mod', 'admin', 'super-admin'] },
            { name: "Images", route: 'backend.images.index', icon: PhotoIcon, color: 'red', role: ['super-admin'] },
        ],
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
    <div class="bg-gray-950 min-h-full">
        <div class="lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-primary/50 bg-black">
                <div class="flex h-16 shrink-0 items-center sticky top-0 bg-black border-b-2 border-primary/50">
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
                                        <li class="px-6 py-1 tracking-widest text-gray-400 font-bold text-xs/6 uppercase">
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
                                            <divider class="from-black to-black h-px" />
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
            class="lg:pl-[20rem] lg:pr-8 my-6 pb-6 text-center md:flex text-gray-400 items-center justify-between lg:mx-auto space-y-3 mx-4 md:space-y-0">
            <p class="text-sm">&copy; SketchNI {{ new Date().getFullYear() }}</p>
            <div class="text-sm">Made with <p class="wiggle" @mouseover="mouseOver" @mouseout="mouseOut">{{ wiggle }}</p> by Sketch</div>
        </footer>
    </div>
</template>

<style scoped>
.wiggle {
    transform-origin: center center;
    display: inline-block;
}

.wiggle:hover {
    animation: wiggle-scale 1s ease-in-out;
}

@keyframes wiggle-scale {
    0%   { transform: scale(1) rotate(0deg); }
    25%  { transform: scale(2.5) rotate(-10deg); }
    40%  { transform: scale(2.5) rotate(10deg); }
    60%  { transform: scale(2.5) rotate(-10deg); }
    75%  { transform: scale(2.5) rotate(10deg); }
    100% { transform: scale(1) rotate(0deg); }
}
</style>
