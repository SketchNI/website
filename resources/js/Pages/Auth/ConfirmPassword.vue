<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <x-head title="Confirm Password" />

    <app-layout>
        <div class="max-w-96 mx-auto">
            <div class="mb-4 text-sm text-text">
                This is a secure area of the application. Please confirm your
                password before continuing.
            </div>

            <form @submit.prevent="submit">
                <div>
                    <input-label for="password" value="Password" />

                    <text-input id="password" type="password" class="mt-1 block w-full"
                                v-model="form.password" autocomplete="current-password" autofocus />

                    <input-error class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4 flex justify-end">
                    <primary-button class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Confirm
                    </primary-button>
                </div>
            </form>
        </div>
    </app-layout>
</template>
