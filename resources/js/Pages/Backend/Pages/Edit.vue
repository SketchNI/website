<script setup>
import moment from "moment";
import Divider from "@/Components/divider.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { Switch, SwitchGroup, SwitchLabel } from "@headlessui/vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { TrashIcon } from "@heroicons/vue/16/solid/index.js";
import InputError from "@/Components/InputError.vue";
import useApp from "@/Composables/useApp.js";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { MdEditor } from "md-editor-v3";
import 'md-editor-v3/lib/style.css';
import Breadcrumb from "@/Components/Breadcrumb.vue";

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
    },
    breadcrumbs: Object
});

const app = useApp();

const updatePage = () => {
    form.put(route('backend.pages.update', { id: props.page.id }));
};

const form = useForm({
    id: props.page.id,
    title: props.page.title,
    slug: props.page.slug,
    content: props.page.raw_content,
    created_at: props.page.created_at,
    is_published: !!props.page.is_published,
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

const confirmingPageDeletion = ref(false);

const confirmPageDeletion = () => {
    confirmingPageDeletion.value = true;
};

const closeModal = () => {
    confirmingPageDeletion.value = false;
};

const deletePage = () => {
    form.delete(route('backend.pages.destroy', { page: props.page }), {
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <x-head :title="`Editing ${page.title}`" />

    <div class="mb-6 inline-flex space-x-2 items-end">
        <h1 class="font-semibold text-text text-xl"><span class="text-subtext0">Editing</span> {{ form.title }}</h1>
        <p class="text-subtext0">View and edit your page.</p>
    </div>

    <breadcrumb :breadcrumbs="breadcrumbs" />

    <div class="mt-6">
        <form class="flex items-start space-x-6" @submit.prevent="updatePage">
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

                        <p v-if="form.published_at !== null" class="mt-4 text-sm">
                            <span class="text-subtext0">Published at </span>
                            <time :class="[app.theme === 'latte' ? 'text-black' : 'text-white']"
                                  :datetime="page.published_at"
                                  :title="moment(page.published_at).format('Do MMM YYYY [at] hh:mma')"
                                  class="font-semibold no-underline">
                                {{ moment(page.published_at).format('Do MMM YYYY [at] hh:mma') }}
                            </time>
                        </p>

                        <divider class="my-6" />

                        <a :href="route('page.preview', { id: page.id })" target="_blank">
                            <secondary-button>Preview</secondary-button>
                        </a>

                        <divider class="my-6" />

                        <danger-button class="space-x-1" type="button" @click="confirmPageDeletion">
                            <trash-icon class="size-4 shrink-0" />
                            <span>Delete Page</span>
                        </danger-button>

                        <modal :show="confirmingPageDeletion" @close="closeModal">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900">
                                    Are you sure you want to delete this page?
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Once this page is deleted, you'll have 180 days to restore it before it is
                                    permanently deleted.
                                </p>

                                <div class="mt-6 flex justify-end">
                                    <secondary-button @click="closeModal">
                                        Cancel
                                    </secondary-button>

                                    <danger-button
                                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                                        class="ms-3"
                                        @click="deletePage">
                                        Delete Post
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
</template>
