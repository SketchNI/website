<script setup>
import { HomeModernIcon } from "@heroicons/vue/24/outline";
import { useForm, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
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

    <admin-layout>
        <div class="mb-6 inline-flex space-x-2 items-end">
            <h1 class="font-semibold text-text text-xl"><span class="text-subtext0">Editing</span> {{ form.title }}</h1>
            <p class="text-subtext0">View and edit your teams.</p>
        </div>

        <div class="flex items-center justify-between mb-6">
            <nav aria-label="Breadcrumb" class="flex">
                <ol class="flex space-x-4 bg-surface0 px-6 shadow-md shadow-crust" role="list">
                    <li class="flex">
                        <div class="flex items-center">
                            <x-link :href="route('backend.index')" class="text-text hover:text-subtext0">
                                <home-modern-icon class="size-5" />
                                <span class="sr-only">Home</span>
                            </x-link>
                        </div>
                    </li>
                    <li class="flex">
                        <div class="flex items-center">
                            <svg aria-hidden="true" class="h-full w-6 shrink-0 text-overlay0"
                                 fill="currentColor"
                                 preserveAspectRatio="none" viewBox="0 0 24 44">
                                <path d="M.293 0l22 22-22 22h1.414l22-22-22-22H.293z" />
                            </svg>
                            <x-link :href="route('backend.teams.index')"
                                    class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Team
                            </x-link>
                        </div>
                    </li>
                    <li class="flex">
                        <div class="flex items-center">
                            <svg aria-hidden="true" class="h-full w-6 shrink-0 text-overlay0"
                                 fill="currentColor"
                                 preserveAspectRatio="none" viewBox="0 0 24 44">
                                <path d="M.293 0l22 22-22 22h1.414l22-22-22-22H.293z" />
                            </svg>
                            <x-link :href="route('backend.teams.index')"
                                    class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                {{ team.name }}
                            </x-link>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="">
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

                        <div v-if="team.logo !== null" class="p-4">
                            <img v-if="!logoPreview" :alt="`Logo for ${team.name}`"
                                 :src="team.logo"
                                 class="size-32 border-2 border-blue rounded-sm" />

                            <span v-else
                                  :style="'background-image: url(\'' + logoPreview + '\');'"
                                  class="block rounded-sm border-2 border-blue size-32 bg-cover bg-no-repeat bg-center"
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
                                  class="block rounded-sm border-2 border-blue size-32 bg-cover bg-no-repeat bg-center"
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
                    <div class="bg-mantle shadow shadow-crust px-6 py-4">
                        <h1 class="uppercase text-sm text-subtext2 font-bold">Manage</h1>

                        <div class="my-4">
                            <danger-button class="space-x-1" type="button" @click="confirmTeamDeletion">
                                <trash-icon class="size-4 shrink-0" />
                                <span>Delete Team</span>
                            </danger-button>

                            <modal :show="confirmingTeamDeletion" @close="closeModal">
                                <div class="p-6">
                                    <h2 class="text-lg font-medium text-gray-900">
                                        Are you sure you want to delete this team?
                                    </h2>

                                    <p class="mt-1 text-sm text-gray-600">
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

                    <div class="bg-mantle shadow shadow-crust px-6 py-4">
                        <primary-button :aria-disabled="form.processing"
                                        :class="[form.processing ? 'bg-blue/60 cursor-not-allowed disabled:bg-blue/60 disabled:text-mantle' : '']"
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
    </admin-layout>
</template>

