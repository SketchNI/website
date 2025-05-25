<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { HomeModernIcon, PlusCircleIcon } from "@heroicons/vue/20/solid";
import NoInfoPager from "@/Components/NoInfoPager.vue";
import moment from "moment";
import { Deferred, router, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { ListBulletIcon } from "@heroicons/vue/24/outline";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";
import DangerButton from "@/Components/DangerButton.vue";

defineProps({
    posts: {
        data: Object,
        meta: Object,
        links: Object,
    },
    counts: {
        posts: Number,
        deleted: Number,
        unpublished: Number,
    },
    tags: {
        data: Object,
        meta: Object,
        links: Object,
    }
});


const showingCategoryModal = ref(false);
const showingViewCategoryModal = ref(false);

const form = useForm({
    name: '',
    parent_id: Number,
});


const createCategory = () => {
    form.post(route('backend.category.store'), {
        onSuccess: () => {
            showingCategoryModal.value = false;
            showingViewCategoryModal.value = false;
        }
    });
}

const deleteCategory = (category) => {
    form.delete(route('backend.category.destroy', { category }), {
        onSuccess: () => {
            router.reload({ only: ['tags'] });
            showViewCategoryModal();
            showingCategoryModal.value = false;
            showingViewCategoryModal.value = false;
        }
    });
}

const showCategoryModal = () => {
    showingCategoryModal.value = true;
}

const showViewCategoryModal = () => {
    showingViewCategoryModal.value = true;
}
</script>

<template>
    <x-head title="Blog Posts Manager" />

    <admin-layout>
        <div class="mb-6 inline-flex space-x-2 items-end">
            <h1 class="font-semibold text-white">
                Blog Posts
            </h1>
            <p class="text-sm text-gray-400">View and edit your blog posts.</p>
        </div>

        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <nav aria-label="Breadcrumb" class="flex space-x-4 items-center">
                    <ol class="flex space-x-4 bg-gray-800 px-6 shadow-md shadow-black" role="list">
                        <li class="flex">
                            <div class="flex items-center">
                                <x-link :href="route('backend.index')" class="text-gray-200 hover:text-white">
                                    <home-modern-icon class="size-5" />
                                    <span class="sr-only">Home</span>
                                </x-link>
                            </div>
                        </li>
                        <li class="flex">
                            <div class="flex items-center">
                                <svg aria-hidden="true" class="h-full w-6 shrink-0 text-gray-500"
                                     fill="currentColor"
                                     preserveAspectRatio="none" viewBox="0 0 24 44">
                                    <path d="M.293 0l22 22-22 22h1.414l22-22-22-22H.293z" />
                                </svg>
                                <x-link :href="route('backend.blog.index')"
                                        class="ml-4 text-sm font-medium text-gray-300 hover:text-gray-100">Blog
                                </x-link>
                            </div>
                        </li>
                    </ol>
                </nav>

                <Deferred data="counts">
                    <div class="flex items-center">
                        <x-link :href="route('backend.blog.index')"
                                class="group filter-link">
                            <span>Posts</span>
                            <span class="filter-counter text-green">{{ counts.posts }}</span>
                        </x-link>

                        <div class="h-8 border-r mx-3 border-surface2"></div>

                        <x-link :href="route('backend.blog.index', { filter: 'unpublished' })"
                                class="group filter-link">
                            <span>Unpublished</span>
                            <span class="filter-counter text-yellow">{{ counts.unpublished }}</span>
                        </x-link>

                        <div class="h-8 border-r mx-3 border-surface2"></div>

                        <x-link :href="route('backend.blog.index', { filter: 'deleted' })" class="group filter-link">
                            <span>Deleted</span>
                            <span class="filter-counter text-red">{{ counts.deleted }}</span>
                        </x-link>
                    </div>
                    <template #fallback></template>
                </Deferred>
            </div>

            <div class="flex space-x-4 items-center">
                <secondary-button class="shadow-sm shadow-crust space-x-1.5 text-sm"
                                  @click.prevent="showViewCategoryModal">
                    <list-bullet-icon class="size-5 shrink-0" />
                    <span class="text-sm normal-case">Categories</span>
                </secondary-button>
                <div>
                    <x-link :href="route('backend.blog.create')" class="inline-flex space-x-1 text-overlay2">
                        <primary-button class="space-x-1.5 normal-case text-sm" type="button">
                            <plus-circle-icon class="size-5 shrink-0" />
                            <span class="text-sm">New Post</span>
                        </primary-button>
                    </x-link>
                </div>
            </div>
        </div>

        <div class="">
            <div class="flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow shadow-black ring-1 ring-primary/5">
                            <table class="min-w-full divide-y divide-gray-500">
                                <thead class="bg-gray-800">
                                <tr>
                                    <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-200 sm:pl-6 md:w-3/5"
                                        scope="col">
                                        Title
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-200" scope="col">
                                        Published
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-200" scope="col">
                                        Created
                                    </th>
                                    <th class="relative py-3.5 pl-3 pr-4 sm:pr-6" scope="col">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-surface0 bg-surface2">
                                <Deferred data="posts">
                                    <tr v-if="posts.data.length === 0" class="bg-mantle">
                                        <td colspan="4">
                                            <div class="text-3xl text-blue flex justify-center items-center h-32">
                                                No data to show
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-else v-for="(post, i) in posts.data" :key="i"
                                        :class="[i % 2 === 0 ? 'bg-gray-700' : 'bg-gray-800', 'hover:bg-gray-900 select-none cursor-default transition duration-150 ease-in']">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium md:w-3/5">
                                            <p class="text-white" v-text="post.title" />
                                            <p class="text-gray-300 font-normal" v-text="post.author.name" />
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm space-x-2">
                                            <div class="inline-flex items-center text-gray-300 space-x-2">
                                                <span :class="post.published_at === null ? 'bg-secondary' : 'bg-accent'"
                                                      class="size-2.5 rounded-full inline-block" />
                                                <span v-if="post.published_at !== null"
                                                      v-text="moment(post.published_at).format('Do MMM YYYY [at] hh:mma')" />
                                                <span v-else class="italic">- Not Published -</span>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-gray-300 text-sm"
                                            v-text="moment(post.created_at).format('Do MMM YYYY [at] hh:mma')" />
                                        <td class="relative whitespace-nowrap text-right text-sm font-medium pr-4">
                                            <x-link v-if="post.deleted_at === null"
                                                    :href="route('backend.blog.edit', { post })"
                                                    class="bg-primary text-black hover:bg-primary/60 hover:text-white py-3 px-4 transition duration-150 ease-in">
                                                Edit
                                            </x-link>

                                            <x-link v-else
                                                    :href="route('backend.blog.restore', { post })"
                                                    class="bg-secondary text-black hover:bg-secondary/60 hover:text-white py-3 px-4 transition duration-150 ease-in">
                                                Restore
                                            </x-link>
                                        </td>
                                    </tr>
                                    <template #fallback>
                                        <tr>
                                            <td class="text-center py-8 space-x-2 text-xl" colspan="4">
                                                <i class="fas fa-circle-notch animate-spin" />
                                                <span class="">Loading</span>
                                            </td>
                                        </tr>
                                    </template>
                                </Deferred>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <Deferred data="posts">
                <div v-if="posts.meta.total > posts.meta.per_page" class="flex justify-end">
                    <no-info-pager :pagination="posts.meta" />
                </div>
                <template #fallback></template>
            </Deferred>
        </div>

        <form @submit.prevent="deleteCategory">
            <modal @close="showingViewCategoryModal = false" :show="showingViewCategoryModal" :closeable="true">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-white">
                            Viewing Categories
                        </h2>
                        <primary-button class="shadow-sm shadow-crust space-x-1.5 text-sm"
                                        @click.prevent="showCategoryModal">
                            <plus-circle-icon class="size-5 shrink-0" />
                            <span class="text-sm normal-case">New Category</span>
                        </primary-button>
                    </div>

                    <div class="my-6">
                        <table class="min-w-full divide-y divide-overlay0">
                            <thead class="bg-surface0">
                            <tr>
                                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-text sm:pl-6 md:w-3/5"
                                    scope="col">
                                    Name
                                </th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-text"
                                    scope="col">
                                    Posts
                                </th>
                                <th class="relative py-3.5 pl-3 pr-4 sm:pr-6" scope="col">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-surface0 bg-surface2">
                            <Deferred data="posts">
                                <tr v-for="(category, i) in tags.data" :key="i"
                                    :class="[i % 2 === 0 ? 'bg-surface0' : 'bg-surface1', 'hover:bg-surface2 select-none cursor-default']">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium md:w-3/5">
                                        <p class="text-text" v-text="category.name" />
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-text">
                                        <p class="text-text">{{ category.posts_count }}</p>
                                    </td>
                                    <td class="relative whitespace-nowrap text-right text-sm font-medium pr-4">
                                        <danger-button @click.prevent="deleteCategory(category)"
                                                       class="text-xs px-1.5 py-2">
                                            Delete
                                        </danger-button>
                                    </td>
                                </tr>
                                <template #fallback>
                                    <tr>
                                        <td class="text-center py-8 space-x-2 text-xl" colspan="4">
                                            <i class="fas fa-circle-notch animate-spin" />
                                            <span class="">Loading</span>
                                        </td>
                                    </tr>
                                </template>
                            </Deferred>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <secondary-button @click="showingViewCategoryModal = false">
                            Cancel
                        </secondary-button>
                    </div>
                </div>
            </modal>
        </form>

        <form @submit.prevent="createCategory">
            <modal @close="showingCategoryModal = false" :show="showingCategoryModal" :closeable="true">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-white">
                        Create A Category
                    </h2>

                    <div class="mt-6">
                        <div class="mb-6">
                            <input-label for="name" value="Category" />

                            <text-input id="name" v-model="form.name"
                                        class="mt-1 block w-3/4" placeholder="Category Name" type="text" />

                            <input-error :message="form.errors.name" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <secondary-button @click="showingCategoryModal = false">
                            Cancel
                        </secondary-button>

                        <primary-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                                        class="ms-3">
                            Create Category
                        </primary-button>
                    </div>
                </div>
            </modal>
        </form>
    </admin-layout>
</template>

<style scoped>
.filter-link {
    @apply flex items-center space-x-2 px-2 py-1 text-overlay2 hover:text-text hover:bg-surface2/50
    hover:shadow-sm hover:shadow-crust transition duration-150 ease-in;
}

.filter-counter {
    @apply bg-surface0 font-black font-mono group-hover:bg-surface2 rounded-full px-2 py-0.5 text-sm;
    @apply transition duration-150 ease-in;
}
</style>
