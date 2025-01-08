import { usePage } from "@inertiajs/vue3";

export default function () {
    return usePage().props.auth.hasOwnProperty("roles") ? usePage().props.auth.roles : null;
}
