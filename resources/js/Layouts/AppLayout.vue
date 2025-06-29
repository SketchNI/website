<script setup>
import { computed, ref, watch } from 'vue';
import useUser from "@/Composables/useUser.js";
import useRole from "@/Composables/useRole.js";
import Divider from "@/Components/divider.vue";
import { usePage } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";
import Sidebar from "@/Layouts/Partials/Sidebar.vue";

const user = useUser();
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
</script>

<template>
    <div class="bg-gray-950 min-h-full transition duration-150 ease-in">
        <!-- Content -->
        <main class="max-w-7xl lg:flex items-start space-x-4">
            <div class="w-full lg:w-1/5">
                <sidebar :links="page.props.app.sidebar" />
            </div>

            <!-- Main Body -->
            <div class="body w-full lg:w-4/5">
                <slot />
            </div>
        </main>

        <divider class="h-[2px] mx-72 my-4" />

        <footer
            class="select-none max-w-7xl my-6 pb-6 text-center md:flex text-gray-400 items-center justify-between lg:mx-auto space-y-3 mx-4 md:space-y-0">
            <p class="text-sm">&copy; SketchNI {{ new Date().getFullYear() }}</p>
            <div class="text-sm">Made with
                <p class="wiggle" @mouseover="mouseOver" @mouseout="mouseOut">
                    {{ wiggle }}
                </p> by Sketch
            </div>
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
    0% {
        transform: scale(1) rotate(0deg);
    }
    25% {
        transform: scale(2.5) rotate(-10deg);
    }
    40% {
        transform: scale(2.5) rotate(10deg);
    }
    60% {
        transform: scale(2.5) rotate(-10deg);
    }
    75% {
        transform: scale(2.5) rotate(10deg);
    }
    100% {
        transform: scale(1) rotate(0deg);
    }
}
</style>
