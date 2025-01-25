import { usePage } from "@inertiajs/vue3";

export default function () {
    const app = usePage().props.app;
    return usePage().props.app = {
        env: app.env,
        flash: app.flash,
        theme: app.theme,
        url: app.url,
    };
}
