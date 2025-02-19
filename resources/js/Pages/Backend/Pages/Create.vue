<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { Switch, SwitchGroup, SwitchLabel } from "@headlessui/vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { HomeModernIcon } from "@heroicons/vue/24/outline/index.js";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { MdEditor } from "md-editor-v3";
import 'md-editor-v3/lib/style.css';

const props = defineProps({
    page: {
        id: String,
        author: {
            id: Number,
            name: String,
            email: String,
        },
        title: String,
        slug: String,
        content: String,
        is_published: Boolean,
        published_at: Date | null,
        is_deleted: Boolean,
        deleted_at: Date | null,
        created_at: Date,
        updated_at: Date,
    }
});

const createPage = () => {
    form.post(route('backend.pages.store'));
};

const form = useForm({
    title: '',
    slug: '',
    content: '',
    is_published: false,
});

const handleUpload = async (files, func) => {
    const res = await Promise.all(files.map(file => {
        return new Promise((rev, rej) => {
            const form = new FormData();
            form.append('image', file);
            window.axios
                .post(route('backend.images.store'), form, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                })
                .then(response => rev(response))
                .catch(err => rej(err))
        })
    }));

    func(res.map(img => ({
        url: img.data.image,
        alt: img.data.alt_text,
        title: img.data.caption,
    })));
}
</script>

<template>
    <x-head title="Create Page" />
    <admin-layout>
        <div class="mb-6 inline-flex space-x-2 items-end">
            <h1 class="font-semibold text-text text-xl"><span class="text-subtext0">Create Page</span></h1>
            <p class="text-subtext0">Create your page.</p>
        </div>

        <div class="flex items-center justify-between mb-6">
            <nav aria-label="Breadcrumb" class="flex">
                <ol class="flex space-x-4 bg-surface0 px-6 shadow-md shadow-crust" role="list">
                    <li class="flex">
                        <div class="flex items-center">
                            <x-link class="text-text hover:text-subtext0" href="{{ route('backend.index') }}">
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
                            <x-link :href="route('backend.pages.index')"
                                    class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Pages
                            </x-link>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="">
            <form class="flex items-start space-x-6" @submit.prevent="createPage">
                <div class="w-4/5 bg-mantle shadow shadow-crust px-6 py-4 space-y-4">
                    <div>
                        <input-label for="title" value="Title" />
                        <text-input id="title" v-model="form.title" class="mt-1 block w-full" type="text" />
                        <input-error :message="form.errors.title" class="mt-2" />
                    </div>

                    <div>
                        <input-label for="slug" value="Slug" />
                        <text-input id="slug" v-model="form.slug" class="mt-1 block w-full" />
                        <input-error :message="form.errors.slug" class="mt-2 text-red" />
                    </div>

                    <div>
                        <input-label for="content" value="Post Content" />

                        <md-editor
                            v-model="form.content"
                            language="en-US"
                            :show-code-row-number="true"
                            preview-theme="github"
                            :noKatex="true"
                            :noMermaid="true"
                            code-theme="ally"
                            :show-toolbar-name="false"
                            theme="dark"
                            class="mt-2"
                            :on-upload-img="handleUpload"
                        />

                        <input-error :message="form.errors.content" class="text-red mt-2" />
                    </div>
                </div>

                <div class="w-1/5 space-y-4">
                    <div class="bg-mantle shadow shadow-crust px-6 py-4">
                        <h1 class="uppercase text-sm text-subtext2 font-bold">Manage</h1>

                        <div class="my-4">
                            <SwitchGroup as="div" class="flex items-center">
                                <Switch v-model="form.is_published"
                                        :class="[form.is_published ? 'bg-blue/60' : 'bg-surface1', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue focus:ring-offset-mantle focus:ring-offset-2']">
                                    <span
                                        :class="[form.is_published ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-base shadow-lg ring-0 transition duration-200 ease-in-out']"
                                        aria-hidden="true" />
                                </Switch>
                                <SwitchLabel as="span" class="ml-3 text select-none">
                                    <span class="font-medium text-subtext2">Publish</span>
                                </SwitchLabel>
                            </SwitchGroup>
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
                                <span>Save Page</span>
                            </span>
                            <span v-else class="space-x-1.5">
                                <i class="fas fa-circle-notch animate-spin size-5 shrink-0" />
                                <span>Saving Page</span>
                            </span>
                        </primary-button>
                    </div>
                </div>
            </form>
        </div>
    </admin-layout>
</template>
