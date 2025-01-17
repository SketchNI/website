<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6 p-6 w-[36rem] shadow shadow-crust bg-surface0">
        <header>
            <h2 class="text-lg font-medium text-text">
                Delete Account
            </h2>

            <p class="mt-1 text-sm text-subtext1">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Before deleting your account, please
                download any data or information that you wish to retain.
            </p>
        </header>

        <danger-button @click="confirmUserDeletion">Delete Account</danger-button>

        <modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once your account is deleted, all of its resources and data
                    will be permanently deleted. Please enter your password to
                    confirm you would like to permanently delete your account.
                </p>

                <div class="mt-6">
                    <input-label class="sr-only" for="password" value="Password" />

                    <text-input id="password" ref="passwordInput" v-model="form.password"
                                class="mt-1 block w-3/4" placeholder="Password" type="password"
                                @keyup.enter="deleteUser" />

                    <input-error :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <secondary-button @click="closeModal">
                        Cancel
                    </secondary-button>

                    <danger-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-3"
                                   @click="deleteUser">
                        Delete Account
                    </danger-button>
                </div>
            </div>
        </modal>
    </section>
</template>
