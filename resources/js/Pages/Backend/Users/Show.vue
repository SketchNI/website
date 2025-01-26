<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    ArrowPathIcon,
    CheckIcon,
    ChevronUpDownIcon,
    HomeModernIcon,
    XMarkIcon
} from "@heroicons/vue/20/solid/index.js";
import { UserCircleIcon } from '@heroicons/vue/24/outline';
import { useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import useApp from "@/Composables/useApp.js";
import {
    Listbox,
    ListboxButton,
    ListboxOption,
    ListboxOptions,
    Switch,
    SwitchGroup,
    SwitchLabel
} from "@headlessui/vue";
import Checkbox from "@/Components/Checkbox.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Divider from "@/Components/divider.vue";
import { ref } from "vue";

const props = defineProps({
    user: {
        id: Number,
        name: String,
        email: String,
        email_verified: Boolean,
        created_at: String,
        role: String,
        permissions: Array,
        permissions_count: Number,
        comments_count: Number,
        votes_count: Number,
        roles_count: Number,
        posts_count: Number,
    },
    roles: Array,
    permissions: Array,
});

const app = useApp();
const page = usePage();

const permissions = ref(props.permissions);

const form = useForm({
    id: props.user.id,
    name: props.user.name,
    email: props.user.email,
    email_verified: props.user.email_verified,
    created_at: props.user.created_at,
    role: props.user.role,
});

props.roles.forEach((role, i) => {
    if (props.user.role === role.display_name) {
        form.role = props.roles[i];
    }
});

const toggleSelection = (name) => {
    if (!checkAndRemoveSelection(name)) {
        permissions.value.push(name);
    }
}

const checkAndRemoveSelection = (name) => {
    if (permissions.value.includes(name)) {
        permissions.value = permissions.value.filter(names => names !== name);

        return true;
    }

    return false;
}


props.user.permissions.forEach((permission) => {
    toggleSelection(permission.name);
})

const stats = [
    { name: 'Posts', stat: props.user.posts_count },
    { name: 'Permissions', stat: props.user.permissions_count },
    { name: 'Comments', stat: props.user.comments_count },
    { name: 'Votes', stat: props.user.votes_count }
];

const updateUser = () => {
    form.put(route('backend.users.update', { user: props.user }));
}

</script>

<template>
    <x-head title="Edit User" />

    <admin-layout>
        <div class="mb-6 inline-flex space-x-2 items-end">
            <h1 class="font-semibold text-white">
                User Manager
            </h1>
            <p class="text-sm text-subtext0">View and update a user.</p>
        </div>

        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <nav aria-label="Breadcrumb" class="flex space-x-4 items-center">
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
                                <x-link :href="route('backend.users.index')"
                                        class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                    Users
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
                                <x-link :href="route('backend.users.show', { user })"
                                        class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                    Edit {{ user.name }}
                                </x-link>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="">
            <div class="flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div v-if="app.flash !== null"
                             class="my-4">
                            <div v-if="app.flash.type === 'success'"
                                 class="bg-green shadow shadow-crust text-base px-6 py-4">
                                {{ app.flash.message }}
                            </div>
                            <div v-else-if="app.flash.type === 'error'"
                                 class="bg-red shadow shadow-crust text-base px-6 py-4">
                                {{ app.flash.message }}
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <form @submit.prevent="updateUser" class="flex items-start space-x-6 mb-12">
                        <div class="w-4/5">
                            <div class="w-full bg-mantle shadow shadow-crust px-6 py-4 space-y-4">
                                <div>
                                    <input-label for="name" value="Name" />
                                    <text-input id="title" v-model="form.name" class="mt-1 block w-full" type="text" />
                                    <input-error :message="form.errors.name" class="mt-2" />
                                </div>

                                <div>
                                    <input-label for="email" value="Email Address" />
                                    <text-input id="email" v-model="form.email" class="mt-1 block w-full"
                                                type="email" />
                                    <input-error :message="form.errors.email" class="mt-2" />
                                </div>

                                <div>
                                    <input-label for="role" value="Role" />
                                    <listbox as="div" v-model="form.role" name="role" class="mt-1 block w-full">
                                        <listbox-button class="button">
                                            <span>{{ form.role.display_name }}</span>
                                            <chevron-up-down-icon class="size-5 text-gray-400" />
                                        </listbox-button>
                                        <listbox-options class="options">
                                            <listbox-option
                                                v-for="(role, i) in roles"
                                                :key="i"
                                                :value="role"
                                                as="ul"
                                                v-slot="{ active, selected }">
                                                <li :class="[active ? 'active' : '', selected ? 'selected' : '', 'option']">
                                                    <span>{{ role.display_name }}</span>
                                                    <check-icon v-show="selected" />
                                                </li>
                                            </listbox-option>
                                        </listbox-options>
                                    </listbox>
                                    <input-error :message="form.errors.role" class="mt-2" />
                                </div>
                            </div>

                            <div
                                class="w-full bg-mantle shadow shadow-crust px-6 py-4 space-y-4 flex justify-between items-center">
                                <div>
                                    <input-label for="email" value="Email Verified" />
                                    <SwitchGroup as="div" class="flex items-center mt-1">
                                        <Switch v-model="form.email_verified"
                                                :class="[form.email_verified ? 'bg-green/60' : 'bg-red/60', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue focus:ring-offset-mantle focus:ring-offset-2']">
                                            <div
                                                :class="[form.email_verified ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-base shadow-lg ring-0 transition duration-200 ease-in-out']"
                                                aria-hidden="true">
                                                <div class="flex items-center justify-center mt-1">
                                                    <component v-if="form.email_verified" :is="CheckIcon"
                                                               class="size-3 text-green" />
                                                    <component v-else :is="XMarkIcon" class="size-3 text-red" />
                                                </div>
                                            </div>
                                        </Switch>
                                        <SwitchLabel as="span" class="ml-3 text select-none">
                                            <span
                                                :class="[form.email_verified ? 'text-green' : 'text-red', 'font-medium text-sm']"
                                                v-text="form.email_verified ? 'Verified' : 'Unverified'" />
                                        </SwitchLabel>
                                    </SwitchGroup>
                                </div>

                                <primary-button type="submit">
                                    <div v-if="form.processing" class="inline-flex items-center space-x-1.5 w-[8.6rem]">
                                        <arrow-path-icon class="size-5 animate-spin-slow" />
                                        <span class="loading">Saving</span>
                                    </div>
                                    <div v-if="!form.processing" class="inline-flex items-center space-x-1.5">
                                        <user-circle-icon class="size-5" />
                                        <span>Update User</span>
                                    </div>
                                </primary-button>
                            </div>
                        </div>

                        <div class="w-1/5 bg-mantle shadow shadow-crust px-6 py-4 space-y-4 max-h-96 overflow-x-auto">
                            <h1 class="uppercase text-sm text-subtext2 font-bold">
                                Permissions
                                <span class="font-normal text-overlay1 normal-case">(Read-Only)</span>
                            </h1>
                            <ul class="space-y-2 text-lg">
                                <li v-for="permission in permissions" :key="permissions.name" class="space-y-2">
                                    <label :for="`checkbox.${permission.id}`" class="flex items-center space-x-2">
                                        <checkbox :id="`checkbox.${permission.name}`"
                                                  disabled="disabled"
                                                  :checked="permissions.includes(permission.name)"
                                                  :value="permission.id" />
                                        <span class="font-bold text-sm text-text">
                                            {{ permission.name }}
                                        </span>
                                        <span class="font-normal text-sm text-overlay1">
                                            ({{ permission.type }})
                                        </span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </form>

                    <divider class="my-6" />

                    <h3 class="text-text font-semibold mb-5">Last 30 days</h3>

                    <div class="w-full bg-mantle shadow shadow-crust p-4 space-y-4">
                        <div class="grid grid-cols-4">
                            <div v-for="item in stats" :key="item.name" class="px-4 py-5 sm:p-6">
                                <dt class="text-normal font-normal text-overlay1">{{ item.name }}</dt>
                                <dd class="mt-1 flex items-baseline text-2xl font-semibold justify-between md:block lg:flex text-text">
                                    {{ item.stat }}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<style scoped>
.button {
    @apply w-full block border border-overlay1 focus:border-blue bg-crust text-text shadow-sm shadow-surface1;
    @apply text-left inline-flex space-x-2 items-center px-3 py-2;
    @apply transition duration-150 ease-in;
}

.selected {
    @apply bg-blue text-crust px-3 -mx-3;
}

.active {
    @apply bg-blue text-crust -mx-3 px-3;
}

.option {
    @apply py-2 text-left flex items-center space-x-2;

    & svg {
        @apply size-4;
    }
}

.options {
    @apply absolute z-10 cursor-pointer px-3 max-h-60 w-72 overflow-auto bg-crust py-1 text-left text-text shadow-sm
    shadow-surface2 focus:outline-none sm:text-sm;
}
</style>
