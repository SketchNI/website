<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { PhotoIcon } from "@heroicons/vue/16/solid";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref } from "vue";
import TextInputPrefix from "@/Components/TextInputPrefix.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

const page = usePage();
const logoPreview = ref(null);
const logoSelector = ref(null);

defineProps({
    breadcrumbs: Array,
})

const form = useForm({
    name: '',
    description: '',
    role: '',
    github: '',
    website: '',
    logo: null,
});

const updateTeam = () => {
    form.post(route('backend.teams.store'), {
        onSuccess: () => clearLogoInput(),
    });
};

const selectLogo = () => {
    logoSelector.value.click();
}

const updateLogoPreview = () => {
    const logo = logoSelector.value.files[0];

    if (!logo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        logoPreview.value = e.target.result;
    };

    reader.readAsDataURL(logo);
    form.logo = logoSelector.value.files[0];
};

const clearLogoInput = () => {
    if (logoSelector.value?.value) {
        logoSelector.value.value = null;
    }
}

</script>

<template>
    <x-head :title="`Create New Team // Team Manager`" />

    <div class="mb-6 inline-flex space-x-2 items-end">
        <h1 class="font-semibold text-text text-xl">Create Team</h1>
        <p class="text-subtext0">View and edit your teams.</p>
    </div>

    <breadcrumb :breadcrumbs="breadcrumbs" />

    <div class="mt-6">
        <form class="flex items-start space-x-6" @submit.prevent="updateTeam">
            <div class="w-4/5 bg-mantle shadow shadow-crust px-6 py-4 space-y-4">
                <div v-if="page.props.app.hasOwnProperty('flash') && page.props.app.flash !== null" class="my-4">
                    <div v-if="page.props.app.flash.type === 'success'"
                         class="bg-green shadow shadow-crust text-base px-6 py-4">
                        {{ page.props.app.flash.message }}
                    </div>
                    <div v-else-if="page.props.flash.type === 'error'"
                         class="bg-red shadow shadow-crust text-base px-6 py-4">
                        {{ page.props.app.flash.message }}
                    </div>
                </div>

                <div>
                    <input-label for="logo" value="Logo" />

                    <input id="logo" ref="logoSelector" accept="image/*" class="hidden" name="logo"
                           type="file" @change="updateLogoPreview" />

                    <div class="mt-4">
                        <span v-if="logoPreview"
                              :style="'background-image: url(\'' + logoPreview + '\');'"
                              class="block rounded-sm border-2 border-blue size-32 bg-cover bg-no-repeat bg-center mb-4"
                        />

                        <primary-button class="text-xs space-x-1" type="button" @click.prevent="selectLogo">
                            <photo-icon class="size-4" />
                            <span>Select Logo</span>
                        </primary-button>
                    </div>
                </div>

                <div>
                    <input-label for="name" value="Name" />
                    <text-input id="name" v-model="form.name" class="mt-1 block w-full" type="text" />
                    <input-error :message="form.errors.name" class="mt-2" />
                </div>

                <div>
                    <input-label for="description" value="Description" />
                    <text-area-input id="description" v-model="form.description" class="mt-1 block w-full" />
                    <input-error :message="form.errors.description" class="mt-2 text-red" />
                </div>

                <div>
                    <input-label for="role" value="Role" />
                    <text-area-input id="role" v-model="form.role" class="mt-1 block w-full" />
                    <input-error :message="form.errors.role" class="mt-2 text-red" />
                </div>

                <div>
                    <input-label for="github" value="GitHub" />
                    <div class="inline-flex items-center w-full">
                        <text-input-prefix value="https://github.com/" />
                        <text-input id="github" v-model="form.github" class="mt-1 flex grow" type="text" />
                    </div>
                    <input-error :message="form.errors.github" class="mt-2" />
                </div>

                <div>
                    <input-label for="website" value="Website" />
                    <text-input id="website" v-model="form.website" class="mt-1 w-full block" type="text" />
                    <input-error :message="form.errors.website" class="mt-2" />
                </div>
            </div>

            <div class="w-1/5 space-y-4">
                <div class="bg-mantle shadow shadow-crust px-6 py-4">
                    <primary-button :aria-disabled="form.processing"
                                    :class="[form.processing ? 'bg-blue/60 cursor-not-allowed disabled:bg-blue/60 disabled:text-mantle' : '']"
                                    :disabled="form.processing"
                                    class="w-full text-xl text-center justify-center space-x-1"
                                    type="submit">
                        <span v-if="!form.processing" class="space-x-1.5">
                            <i class="fas fa-save size-5 shrink-0" />
                            <span>Create Team</span>
                        </span>
                        <span v-else class="space-x-1.5">
                            <i class="fas fa-circle-notch animate-spin size-5 shrink-0" />
                            <span>Creating Team</span>
                        </span>
                    </primary-button>
                </div>
            </div>
        </form>
    </div>
</template>

