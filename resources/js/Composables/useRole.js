import { usePage } from "@inertiajs/vue3";

export default function () {
    return usePage().props.auth.hasOwnProperty("role") ? usePage().props.auth.role : null;
}
