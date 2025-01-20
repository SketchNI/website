<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <x-head title="Forgot Password" />

    <app-layout>
        <div class="max-w-96 mx-auto">
            <div class="mb-4 text-sm text-text">
                Forgot your password? No problem. Just let us know your email
                address and we will email you a password reset link that will allow
                you to choose a new one.
            </div>

            <div v-if="status" class="mb-4 text-sm font-medium text-green">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <input-label for="email" value="Email" />

                    <text-input id="email" v-model="form.email" autocomplete="username"
                                autofocus class="mt-1 block w-full" type="email" />

                    <input-error :message="form.errors.email" class="mt-2" />
                </div>

                <div class="mt-4 flex items-center justify-end">
                    <primary-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Email Password Reset Link
                    </primary-button>
                </div>
            </form>
        </div>
    </app-layout>
</template>
