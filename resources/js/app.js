import '../css/app.css';
import './bootstrap';
import "./fontawesome.js"; // Stops phpstorm complaining about no local file.

import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index.js';
import Vue3Toastify from 'vue3-toastify';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
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
