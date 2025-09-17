<script setup>
import { ArrowPathIcon, CheckIcon, ChevronUpDownIcon, } from "@heroicons/vue/20/solid/index.js";
import { UserCircleIcon } from '@heroicons/vue/24/outline';
import { useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import useApp from "@/Composables/useApp.js";
import { Listbox, ListboxButton, ListboxOption, ListboxOptions, } from "@headlessui/vue";
import Checkbox from "@/Components/Checkbox.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Divider from "@/Components/divider.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

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
        reactions_count: Number,
        roles_count: Number,
        posts_count: Number,
    },
    roles: Array,
    permissions: Array,
    breadcrumbs: Array,
});

const page = usePage();
const app = useApp();

const form = useForm({
    id: props.user.id,
    name: props.user.name,
    email: props.user.email,
    created_at: props.user.created_at,
    role: props.user.role,
    permissions: props.user.permissions,
});

props.roles.forEach((role, i) => {
    if (props.user.role === role.display_name) {
        form.role = props.roles[i];
    }
});

const toggleSelection = (name) => {
    if (!checkAndRemoveSelection(name)) {
        form.permissions.push(name);
    }
}

const checkAndRemoveSelection = (name) => {
    if (form.permissions.includes(name)) {
        form.permissions = form.permissions.filter(names => names !== name);

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
    { name: 'Reactions', stat: props.user.reactions_count }
];

const updateUser = () => {
    form.put(route('backend.users.update', { user: props.user }));
}

</script>

<template>
    <x-head title="Edit User" />

    <div class="mb-6 inline-flex space-x-2 items-end">
        <h1 class="font-semibold text-white">
            User Manager
        </h1>
        <p class="text-sm text-subtext0">View and update a user.</p>
    </div>

    <breadcrumb :breadcrumbs="breadcrumbs" />

    <div class="mt-6">
        <div class="flow-root">
            <div>
                <form @submit.prevent="updateUser" class="flex items-start space-x-6 mb-12">
                    <div class="w-4/5">
                        <div class="w-full bg-gray-800 px-6 py-4 space-y-4">
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
                            class="w-full bg-gray-800 px-6 py-4 space-y-4 flex justify-end items-center">

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

                    <div class="w-1/5 bg-gray-800 px-6 py-4 space-y-4 max-h-96 overflow-x-auto">
                        <h1 class="uppercase text-sm text-gray-200 font-bold">
                            Permissions
                            <span class="font-normal text-gray-400 normal-case">(Read-Only)</span>
                        </h1>
                        <ul class="space-y-2 text-lg">
                            <li v-for="permission in permissions" :key="permissions.name" class="space-y-2">
                                <label :for="`checkbox.${permission.id}`" class="flex items-center space-x-2">
                                    <checkbox :id="`checkbox.${permission.name}`"
                                              disabled="disabled"
                                              :checked="form.permissions.includes(permission.name)"
                                              :value="permission.id" />
                                    <span class="font-bold text-sm text-gray-200">
                                        {{ permission.name }}
                                    </span>
                                    <span class="font-normal text-sm text-gray-400">
                                        ({{ permission.type }})
                                    </span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </form>

                <divider class="my-6 from-gray-800/0 to-gray-800/0" />

                <h3 class="text-gray-200 font-semibold mb-5">Stats</h3>

                <div class="w-full bg-gray-800 p-4 space-y-4">
                    <div class="grid grid-cols-4">
                        <div v-for="item in stats" :key="item.name" class="px-4 py-5 sm:p-6">
                            <dt class="text-normal font-normal text-gray-400">{{ item.name }}</dt>
                            <dd class="mt-1 flex items-baseline text-2xl font-semibold justify-between md:block lg:flex text-gray-200">
                                {{ item.stat }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.button {
    @apply w-full block border border-gray-600 focus:border-blue bg-gray-700 text-gray-200;
    @apply text-left inline-flex space-x-2 items-center px-3 py-2;
    @apply transition duration-150 ease-in;
}

.selected {
    @apply bg-blue text-gray-600 px-3 -mx-3;
}

.active {
    @apply bg-blue text-gray-600 -mx-3 px-3;
}

.option {
    @apply py-2 text-left flex items-center space-x-2;

    & svg {
        @apply size-4;
    }
}

.options {
    @apply absolute z-10 cursor-pointer px-3 max-h-60 w-72 overflow-auto bg-gray-700 py-1 text-left text-gray-200
    focus:outline-none sm:text-sm;
}
</style>
