<script setup>
import { HomeModernIcon } from "@heroicons/vue/24/outline";
import { useForm, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import { SwitchGroup, Switch, SwitchLabel } from "@headlessui/vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { MdEditor } from 'md-editor-v3';
import 'md-editor-v3/lib/style.css';

const props = defineProps({
    categories: Object,
})

const page = usePage();

const createPost = () => {
    form.post(route('backend.blog.store'));
};

const form = useForm({
    title: '',
    excerpt: '',
    content: '',
    published_at: '',
    published: false,
    categories: [],
});

const toggleSelection = (id) => {
    if (!checkAndRemoveSelection(id)) {
        form.categories.push(id);
    }
}

const checkAndRemoveSelection = (id) => {
    if (form.categories.includes(id)) {
        form.categories = form.categories.filter(ids => ids !== id);

        return true;
    }

    return false;
}

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
    <x-head :title="`Create New Post // Blog Post Manager`" />

    <admin-layout>
        <div class="h-full">
            <div class="mb-6 inline-flex space-x-2 items-end">
                <h1 class="font-semibold text-text text-xl">Create New Post</h1>
                <p class="text-subtext0">View and edit your blog post.</p>
            </div>

            <div class="flex items-center justify-between mb-6">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol role="list" class="flex space-x-4 bg-surface0 px-6 shadow-md shadow-crust">
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
                                <svg class="h-full w-6 shrink-0 text-overlay0" viewBox="0 0 24 44"
                                     preserveAspectRatio="none"
                                     fill="currentColor" aria-hidden="true">
                                    <path d="M.293 0l22 22-22 22h1.414l22-22-22-22H.293z" />
                                </svg>
                                <x-link :href="route('backend.blog.index')"
                                        class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                    Blog
                                </x-link>
                            </div>
                        </li>
                        <li class="flex">
                            <div class="flex items-center">
                                <svg class="h-full w-6 shrink-0 text-overlay0" viewBox="0 0 24 44"
                                     preserveAspectRatio="none"
                                     fill="currentColor" aria-hidden="true">
                                    <path d="M.293 0l22 22-22 22h1.414l22-22-22-22H.293z" />
                                </svg>
                                <x-link :href="route('backend.blog.index')"
                                        class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                    Create Post
                                </x-link>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="">
                <form @submit.prevent="createPost" class="flex items-start space-x-6">
                    <div class="w-4/5 bg-mantle shadow shadow-crust px-6 py-4 space-y-4">
                        <div v-if="page.props.app.hasOwnProperty('flash') && page.props.app.flash !== null"
                             class="my-4">
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
                            <input-label for="title" value="Title" />
                            <text-input type="text" class="mt-1 block w-full" v-model="form.title" id="title" />
                            <input-error class="mt-2" :message="form.errors.title" />
                        </div>

                        <div>
                            <input-label for="excerpt" value="Summary" />
                            <text-area-input class="mt-1 block w-full" v-model="form.excerpt" id="excerpt" />
                            <input-error :message="form.errors.excerpt" class="mt-2 text-red" />
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
                            <h1 class="uppercase text-sm text-subtext2 font-bold">Categories</h1>
                            <p class="mt-2 text-red">{{ form.errors.categories }}</p>
                            <ul class="space-y-2 text-lg">
                                <li v-for="category in categories" :key="category.id" class="space-y-2">
                                    <label :for="`checkbox.${category.id}`" class="flex items-center space-x-2">
                                        <checkbox @click="toggleSelection(category.id)"
                                                  :id="`checkbox.${category.id}`"
                                                  :checked="form.categories.includes(category.id)"
                                                  :value="category.id" />
                                        <span class="font-bold text-sm text-text">{{ category.name }}</span>
                                    </label>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-mantle shadow shadow-crust px-6 py-4">
                            <h1 class="uppercase text-sm text-subtext2 font-bold">Manage</h1>

                            <div class="my-4">
                                <SwitchGroup as="div" class="flex items-center">
                                    <Switch v-model="form.published"
                                            :class="[form.published ? 'bg-blue/60' : 'bg-surface1', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue focus:ring-offset-mantle focus:ring-offset-2']">
                                        <span aria-hidden="true"
                                              :class="[form.published ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-base shadow-lg ring-0 transition duration-200 ease-in-out']" />
                                    </Switch>
                                    <SwitchLabel as="span" class="ml-3 text select-none">
                                        <span class="font-medium text-subtext2">Publish</span>
                                    </SwitchLabel>
                                </SwitchGroup>
                            </div>
                        </div>

                        <div class="bg-mantle shadow shadow-crust px-6 py-4">
                            <primary-button type="submit" class="w-full text-xl text-center justify-center space-x-1"
                                            :class="[form.processing ? 'bg-blue/60 cursor-not-allowed disabled:bg-blue/60 disabled:text-mantle' : '']"
                                            :aria-disabled="form.processing"
                                            :disabled="form.processing">
                                <span v-if="!form.processing" class="space-x-1.5">
                                    <i class="fas fa-save size-5 shrink-0" />
                                    <span>Save Post</span>
                                </span>
                                <span v-else class="space-x-1.5">
                                    <i class="fas fa-circle-notch animate-spin size-5 shrink-0" />
                                    <span>Saving Post</span>
                                </span>
                            </primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </admin-layout>
</template>

<style scoped>

</style>
