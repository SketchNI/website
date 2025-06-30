<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { ArrowTurnUpLeftIcon, PhotoIcon, TrashIcon } from "@heroicons/vue/16/solid";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { ref } from "vue";
import TextInputPrefix from "@/Components/TextInputPrefix.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

const props = defineProps({
    team: {
        id: Number,
        name: String,
        description: String,
        role: String,
        website: String,
        github: String,
        logo: String,
    },
    breadcrumbs: Array,
})

const page = usePage();
const confirmingTeamDeletion = ref(false);
const logoPreview = ref(null);
const logoSelector = ref(null);

const form = useForm({
    _method: 'PUT',
    id: props.team.id,
    name: props.team.name,
    description: props.team.description,
    role: props.team.role,
    github: props.team.github,
    website: props.team.website,
    logo: props.team.logo,
});

const updateTeam = () => {
    form.post(route('backend.teams.update', { team: props.team }), {
        onSuccess: () => clearLogoInput(),
    });
};

const confirmTeamDeletion = () => {
    confirmingTeamDeletion.value = true;
};

const closeModal = () => {
    confirmingTeamDeletion.value = false;
};

const deleteTeam = () => {
    form.delete(route('backend.teams.destroy', { team: props.team, photo: false }), {
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
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
    <x-head :title="`Editing ${team.name} // Team Manager`" />

    <div class="mb-6 inline-flex space-x-2 items-end">
        <h1 class="font-semibold text-white text-xl"><span class="text-gray-300">Editing</span> {{ team.name }}</h1>
        <p class="text-gray-400">View and edit your teams.</p>
    </div>

    <breadcrumb :breadcrumbs="breadcrumbs" />

    <div class="mt-6">
        <form class="flex items-start space-x-6" @submit.prevent="updateTeam">
            <div class="w-4/5 bg-gray-800 px-6 py-4 space-y-4">
                <div>
                    <input-label for="logo" value="Logo" />

                    <input id="logo" ref="logoSelector" accept="image/*" class="hidden" name="logo"
                           type="file" @change="updateLogoPreview" />

                    <div v-if="team.logo !== null" class="p-4">
                        <img v-if="!logoPreview" :alt="`Logo for ${team.name}`"
                             :src="team.logo"
                             class="size-32 border-2 border-primary rounded-sm" />

                        <span v-else
                              :style="'background-image: url(\'' + logoPreview + '\');'"
                              class="block rounded-sm border-2 border-primary size-32 bg-cover bg-no-repeat bg-center"
                        />

                        <div class="mt-4">
                            <div class="flex flex-row space-x-4">
                                <primary-button class="text-xs space-x-1" type="button" @click.prevent="selectLogo">
                                    <photo-icon class="size-4" />
                                    <span>Change Logo</span>
                                </primary-button>

                                <secondary-button v-if="logoPreview" class="text-xs space-x-1"
                                                  type="button" @click.prevent="logoPreview = null">
                                    <arrow-turn-up-left-icon class="size-4" />
                                    <span>Clear Change</span>
                                </secondary-button>
                            </div>
                        </div>
                    </div>

                    <div v-else class="mt-4">
                        <span v-if="logoPreview"
                              :style="'background-image: url(\'' + logoPreview + '\');'"
                              class="block rounded-sm border-2 border-primary size-32 bg-cover bg-no-repeat bg-center"
                        />

                        <primary-button class="text-xs space-x-1 mt-4" type="button" @click.prevent="selectLogo">
                            <photo-icon class="size-4" />
                            <span>Change Logo</span>
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
                    <text-input id="website" v-model="form.website" class="mt-1 block w-full" type="text" />
                    <input-error :message="form.errors.website" class="mt-2" />
                </div>
            </div>

            <div class="w-1/5 space-y-4">
                <div class="bg-gray-800 px-6 py-4">
                    <h1 class="uppercase text-sm text-gray-300 font-bold">Manage</h1>

                    <div class="my-4">
                        <danger-button class="space-x-1" type="button" @click="confirmTeamDeletion">
                            <trash-icon class="size-4 shrink-0" />
                            <span>Delete Team</span>
                        </danger-button>

                        <modal :show="confirmingTeamDeletion" @close="closeModal">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-100">
                                    Are you sure you want to delete this team?
                                </h2>

                                <p class="mt-1 text-sm text-gray-300">
                                    Once this team is deleted, there is no way to restore it.
                                </p>

                                <div class="mt-6 flex justify-end">
                                    <secondary-button @click="closeModal">
                                        Cancel
                                    </secondary-button>

                                    <danger-button
                                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                                        class="ms-3"
                                        @click="deleteTeam">
                                        Delete Team
                                    </danger-button>
                                </div>
                            </div>
                        </modal>
                    </div>
                </div>

                <div class="bg-gray-800 px-6 py-4">
                    <primary-button :aria-disabled="form.processing"
                                    :class="[form.processing ? 'bg-primary/60 cursor-not-allowed disabled:bg-primary/60 disabled:text-mantle' : '']"
                                    :disabled="form.processing"
                                    class="w-full text-xl text-center justify-center space-x-1"
                                    type="submit">
                        <span v-if="!form.processing" class="space-x-1.5">
                            <i class="fas fa-save size-5 shrink-0" />
                            <span>Save Team</span>
                        </span>
                        <span v-else class="space-x-1.5">
                            <i class="fas fa-circle-notch animate-spin size-5 shrink-0" />
                            <span>Saving Team</span>
                        </span>
                    </primary-button>
                </div>
            </div>
        </form>
    </div>
</template>

