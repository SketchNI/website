<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import Divider from "@/Components/divider.vue";
import useApp from "@/Composables/useApp.js";
import useRole from "@/Composables/useRole.js";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const app = useApp();
const roles = useRole();
</script>

<template>
    <x-head title="Edit Account" />

    <app-layout>
        <div class="mx-auto max-w-7xl space-y-12 sm:px-6 lg:px-8 flex flex-col items-center">
            <div v-if="app.env === 'local'">
                <div class="pb-8">
                    Role: {{ roles.join(', ') }}
                </div>

                <divider class="w-[52rem]" />
            </div>
            <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" class="max-w-xl" />

            <divider class="w-[52rem]" />

            <UpdatePasswordForm class="max-w-xl" />

            <divider class="w-[52rem]" />

            <DeleteUserForm class="max-w-xl" />
        </div>
    </app-layout>
</template>
