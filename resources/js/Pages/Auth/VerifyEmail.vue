<script setup>
import { computed } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <x-head title="Email Verification" />

    <app-layout>
        <div class="max-w-96 mx-auto">
            <div class="mb-4 text-sm text-text">
                Thanks for signing up! Before getting started, could you verify your
                email address by clicking on the link we just emailed to you? If you
                didn't receive the email, we will gladly send you another.
            </div>

            <div
                v-if="verificationLinkSent" class="mb-4 text-sm font-medium text-green">
                A new verification link has been sent to the email address you
                provided during registration.
            </div>

            <form @submit.prevent="submit">
                <div class="mt-4 flex items-center justify-between">
                    <primary-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Resend Verification Email
                    </primary-button>

                    <x-link :href="route('logout')" as="button" class="link"
                            method="post">
                        Log Out
                    </x-link>
                </div>
            </form>
        </div>
    </app-layout>
</template>
