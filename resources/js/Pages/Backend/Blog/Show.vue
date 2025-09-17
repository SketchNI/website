<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { TrashIcon } from "@heroicons/vue/16/solid/index";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import { Switch, SwitchGroup, SwitchLabel } from "@headlessui/vue";
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
import Breadcrumb from "@/Components/Breadcrumb.vue";

const props = defineProps({
    post: {
        id: Number,
        slug: String,
        title: String,
        content: String,
        excerpt: String,
        tags: Array,
        published_at: String,
        created_at: String,
        updated_at: String,
        author: {
            id: Number,
            name: String,
            email: String,
        }
    },
    tags: Object,
    breadcrumbs: Array,
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
    content: props.post.raw_content,
    published_at: props.post.published_at,
    created_at: props.post.created_at,
    published: !!props.post.published_at,
    tags: [],
});

const toggleSelection = (name) => {
    if (!checkAndRemoveSelection(name)) {
        form.tags.push(name);
    }
}

const checkAndRemoveSelection = (name) => {
    if (form.tags.includes(name)) {
        form.tags = form.tags.filter(names => names !== name);

        return true;
    }

    return false;
}

props.post.tags.forEach((tag) => {
    toggleSelection(tag.name);
})

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

    <div class="mb-6 inline-flex space-x-2 items-end">
        <h1 class="font-semibold text-white text-xl"><span class="text-gray-300">Editing</span> {{ form.title }}</h1>
        <p class="text-gray-400">View and edit your blog post.</p>
    </div>

    <breadcrumb :breadcrumbs="breadcrumbs" />

    <div class="mt-6">
        <form class="flex items-start space-x-6" @submit.prevent="updatePost">
            <div class="w-4/5 bg-gray-800 px-6 py-4 space-y-4">
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
                <div class="bg-gray-800 px-6 py-4">
                    <h1 class="uppercase text-sm text-gray-200 font-bold">Tags</h1>
                    <p class="mt-2 text-secondary">{{ form.errors.tags }}</p>
                    <ul class="space-y-2 text-lg">
                        <li v-for="tag in tags" :key="tag.id" class="space-y-2">
                            <label :for="`checkbox.${tag.id}`" class="flex items-center space-x-2">
                                <checkbox @click="toggleSelection(tag.name)"
                                          :id="`checkbox.${tag.id}`"
                                          :checked="form.tags.includes(tag.name)"
                                          :value="tag.name" />
                                <span class="font-bold text-sm text-white">{{ tag.name }}</span>
                            </label>
                        </li>
                    </ul>
                </div>

                <div class="bg-gray-800 px-6 py-4">
                    <h1 class="uppercase text-sm text-gray-200 font-bold">Manage</h1>

                    <div class="my-4">
                        <SwitchGroup as="div" class="flex items-center">
                            <Switch v-model="form.published"
                                    :class="[form.published ? 'bg-primary' : 'bg-gray-700', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-gray-800 focus:ring-offset-2']">
                                <span
                                    :class="[form.published ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-gray-900 ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true" />
                            </Switch>
                            <SwitchLabel as="span" class="ml-3 text select-none">
                                <span class="font-medium text-gray-300">Publish</span>
                            </SwitchLabel>
                        </SwitchGroup>

                        <p v-if="form.published_at !== null" class="mt-4 text-sm">
                            <span class="text-gray-200">Published at </span>
                            <time class="text-white font-semibold no-underline"
                                  :datetime="post.published_at"
                                  :title="moment(post.published_at).format('Do MMM YYYY [at] hh:mma')">
                                {{ moment(post.published_at).format('Do MMM YYYY [at] hh:mma') }}
                            </time>
                        </p>

                        <divider class="my-6 h-[2px] from-gray-800/0 to-gray-800/0" />

                        <a :href="route('blog.preview', { id: post.id })" target="_blank">
                            <secondary-button>Preview</secondary-button>
                        </a>

                        <divider class="my-6 h-[2px] from-gray-800/0 to-gray-800/0" />

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

                <div class="bg-gray-800 px-6 py-4">
                    <primary-button :aria-disabled="form.processing"
                                    :class="[form.processing ? 'bg-primary/60 cursor-not-allowed disabled:bg-primary/60 disabled:text-gray-800' : '']"
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
</template>

<style scoped>

</style>
