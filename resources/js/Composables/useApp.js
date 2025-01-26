import { usePage } from "@inertiajs/vue3";

export default function () {
    return usePage().props.app;
}
