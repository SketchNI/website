<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <x-head title="Log in" />

    <app-layout>
        <div class="max-w-96 mx-auto">
            <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                {{ status }}
            </div>

            <a :href="route('auth', { driver: 'github' })">
                <primary-button
                    class="inline-flex space-x-2 bg-text shadow-sm shadow-blue/60 w-full justify-center items-center">
                    <i class="fab fa-github text-xl" />
                    <span class="text-xl">Sign In With GitHub</span>
                </primary-button>
            </a>

            <div class="flex w-full items-center my-6">
                <div class="h-px bg-blue w-1/2" />
                <div class="bg-blue-400/60 px-3 py-2 text-white rounded-full text-sm">or</div>
                <div class="h-px bg-blue w-1/2" />
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" />

                    <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email"
                               autofocus autocomplete="username" />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Password" />

                    <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
                               autocomplete="current-password" />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4 block">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ms-2 text-sm text-subtext1">Remember me</span>
                    </label>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <div class="space-y-4 flex flex-col">
                        <x-link v-if="canResetPassword" :href="route('password.request')"
                                class="link text-sm">
                            Forgot your password?
                        </x-link>

                        <x-link :href="route('register')" class="link text-sm">
                            Register
                        </x-link>
                    </div>

                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Log in
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </app-layout>
</template>
