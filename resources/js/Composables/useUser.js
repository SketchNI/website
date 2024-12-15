import { usePage } from "@inertiajs/vue3";

export default function () {
    return usePage().props.auth.hasOwnProperty("user") ? usePage().props.auth.user : null;
}
