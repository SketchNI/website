import '../css/app.css';
import './bootstrap';
import "./fontawesome.js"; // Stops phpstorm complaining about no local file.

import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index.js';
import Vue3Toastify from 'vue3-toastify';
import AppLayout from "@/Layouts/AppLayout.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        let page = pages[`./Pages/${name}.vue`];
        const layout = name.startsWith('Backend/') ? AdminLayout : AppLayout;
        page.default.layout = page.default.layout || layout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Vue3Toastify, {
                autoHideDuration: 5000,
            })
            .component('x-head', Head)
            .component('x-link', Link)
            .mount(el);
    },
    progress: {
        color: '#89b4fa',
    },
}).then(() => {
    console.debug('[npm] sketchni.uk ready')
});
