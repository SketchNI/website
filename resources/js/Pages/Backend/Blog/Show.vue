<script setup>
import { HomeModernIcon } from "@heroicons/vue/24/outline";
import { useForm, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { TrashIcon } from "@heroicons/vue/16/solid/index";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import { SwitchGroup, Switch, SwitchLabel } from "@headlessui/vue";
import moment from "moment/moment";
import useApp from "@/Composables/useApp.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Divider from "@/Components/divider.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { ref } from "vue";
import { MdEditor } from "md-editor-v3";
import 'md-editor-v3/lib/style.css';

const props = defineProps({
    post: {
        id: Number,
        slug: String,
        title: String,
        content: String,
        excerpt: String,
        categories: Array,
        published_at: String,
        created_at: String,
        updated_at: String,
        author: {
            id: Number,
            name: String,
            email: String,
        }
    },
    categories: Object,
})

const app = useApp();
const page = usePage();

const updatePost = () => {
    form.put(route('backend.blog.update', { id: props.post.id }));
};

const form = useForm({
    title: props.post.title,
    slug: props.post.slug,
    excerpt: props.post.excerpt,
    content: props.post.content,
    published_at: props.post.published_at,
    created_at: props.post.created_at,
    published: !!props.post.published_at,
    categories: [],
});

const handleUpload = (event) => {
    console.log(event)
}

const confirmingPostDeletion = ref(false);

const confirmPostDeletion = () => {
    confirmingPostDeletion.value = true;
};

const closeModal = () => {
    confirmingPostDeletion.value = false;
};

const deletePost = () => {
    form.delete(route('backend.blog.destroy', { id: props.post.id }), {
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <x-head :title="`Editing ${post.title} // Blog Post Manager`" />

    <admin-layout>
        <div class="mb-6 inline-flex space-x-2 items-end">
            <h1 class="font-semibold text-text text-xl"><span class="text-subtext0">Editing</span> {{ form.title }}</h1>
            <p class="text-subtext0">View and edit your blog post.</p>
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
                            <x-link :href="route('backend.blog.index')"
                                    class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Blog
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
                            <x-link :href="route('backend.blog.index')"
                                    class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                {{ post.title }}
                            </x-link>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="">
            <form class="flex items-start space-x-6" @submit.prevent="updatePost">
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
                        <input-label for="title" value="Title" />
                        <text-input id="title" v-model="form.title" class="mt-1 block w-full" type="text" />
                        <input-error :message="form.errors.title" class="mt-2" />
                    </div>

                    <div>
                        <input-label for="excerpt" value="Summary" />
                        <text-area-input id="excerpt" v-model="form.excerpt" class="mt-1 block w-full" />
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
                        <ul class="space-y-2 mt-2">
                            <li v-for="(cat, i) in categories" :key="i">
                                <label :for="cat.slug" class="flex items-center">
                                    <Checkbox :id="cat.slug" v-model:checked="form.categories" :name="cat.slug"
                                              :value="cat.slug" />
                                    <span class="ms-2 text-subtext1">{{ cat.name }}</span>
                                </label>
                            </li>
                            <li>
                                <p class="mt-2 text-red">{{ form.errors.categories }}</p>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-mantle shadow shadow-crust px-6 py-4">
                        <h1 class="uppercase text-sm text-subtext2 font-bold">Manage</h1>

                        <div class="my-4">
                            <SwitchGroup as="div" class="flex items-center">
                                <Switch v-model="form.published"
                                        :class="[form.published ? 'bg-blue/60' : 'bg-surface1', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue focus:ring-offset-mantle focus:ring-offset-2']">
                                    <span :class="[form.published ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-base shadow-lg ring-0 transition duration-200 ease-in-out']"
                                          aria-hidden="true" />
                                </Switch>
                                <SwitchLabel as="span" class="ml-3 text select-none">
                                    <span class="font-medium text-subtext2">Publish</span>
                                </SwitchLabel>
                            </SwitchGroup>

                            <p v-if="form.published_at !== null" class="mt-4 text-sm">
                                <span class="text-subtext0">Published at </span>
                                <time :class="[app.theme === 'latte' ? 'text-black' : 'text-white']"
                                      :datetime="post.published_at"
                                      :title="moment(post.published_at).format('Do MMM YYYY [at] hh:mma')"
                                      class="font-semibold no-underline">
                                    {{ moment(post.published_at).format('Do MMM YYYY [at] hh:mma') }}
                                </time>
                            </p>

                            <divider class="my-6" />

                            <danger-button class="space-x-1" type="button" @click="confirmPostDeletion">
                                <trash-icon class="size-4 shrink-0" />
                                <span>Delete Post</span>
                            </danger-button>

                            <modal :show="confirmingPostDeletion" @close="closeModal">
                                <div class="p-6">
                                    <h2 class="text-lg font-medium text-gray-900">
                                        Are you sure you want to delete this post?
                                    </h2>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Once this post is deleted, you'll have 180 days to restore it before it is
                                        permanently deleted.
                                    </p>

                                    <div class="mt-6 flex justify-end">
                                        <secondary-button @click="closeModal">
                                            Cancel
                                        </secondary-button>

                                        <danger-button
                                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                                            class="ms-3"
                                            @click="deletePost">
                                            Delete Post
                                        </danger-button>
                                    </div>
                                </div>
                            </modal>
                        </div>
                    </div>

                    <div class="bg-mantle shadow shadow-crust px-6 py-4">
                        <primary-button :aria-disabled="form.processing" :class="[form.processing ? 'bg-blue/60 cursor-not-allowed disabled:bg-blue/60 disabled:text-mantle' : '']"
                                        :disabled="form.processing"
                                        class="w-full text-xl text-center justify-center space-x-1"
                                        type="submit">
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
    </admin-layout>
</template>

<style scoped>

</style>
