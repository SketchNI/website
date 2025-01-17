<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Reset Password" />
    <app-layout>
        <div class="max-w-96 mx-auto">
            <form @submit.prevent="submit">
                <div>
                    <input-label for="email" value="Email" />

                    <text-input id="email" type="email" class="mt-1 block w-full"
                                v-model="form.email" autofocus autocomplete="username" />

                    <input-error class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <input-label for="password" value="Password" />

                    <text-input id="password" type="password" class="mt-1 block w-full"
                                v-model="form.password" autocomplete="new-password" />

                    <input-error class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <input-label for="password_confirmation" value="Confirm Password" />

                    <text-input id="password_confirmation" type="password" class="mt-1 block w-full"
                                v-model="form.password_confirmation" autocomplete="new-password" />

                    <input-error class="mt-2" :message="form.errors.password_confirmation" />
                </div>

                <div class="mt-4 flex items-center justify-end">
                    <primary-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Reset Password
                    </primary-button>
                </div>
            </form>
        </div>
    </app-layout>
</template>
