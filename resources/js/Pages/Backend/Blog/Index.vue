<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import useApp from "@/Composables/useApp.js";
import { HomeModernIcon, PlusCircleIcon } from "@heroicons/vue/20/solid";
import { ArchiveBoxXMarkIcon, TrashIcon, ListBulletIcon } from "@heroicons/vue/24/outline";
import NoInfoPager from "@/Components/NoInfoPager.vue";
import moment from "moment";
import { Deferred, router, useForm, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
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
    categories: {
        data: Object,
        meta: Object,
        links: Object,
    }
});

const app = useApp();
const page = usePage();
const showingCategoryModal = ref(false);
const showingViewCategoryModal = ref(false);
const response = ref(null);
const showResponse = ref(false);

const form = useForm({
    name: '',
    parent_id: Number,
});

const createCategory = () => {
    form.post(route('backend.category.store'));
}

const deleteCategory = (category) => {
    window.axios.delete(route('backend.category.destroy', { category }))
        .then(r => {
            router.reload({ only: ['categories'] });
            showViewCategoryModal();
            response.value = r.data;
            showResponse.value = true;
        })
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
            <h1 :class="['font-semibold', app.theme === 'latte' ? 'text-black' : 'text-white']">
                Blog Posts
            </h1>
            <p class="text-sm text-subtext0">View and edit your blog posts.</p>
        </div>

        <div class="flex items-center justify-between mb-6">
            <div class="flex items-ccenter space-x-4">
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
                                <x-link :href="route('backend.blog.index')"
                                        class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Blog
                                </x-link>
                            </div>
                        </li>
                    </ol>
                </nav>

                <Deferred data="counts">
                    <div class="flex items-center">
                        <x-link :href="route('backend.blog.index', { filter: 'unpublished' })"
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

            <div class="flex items-center space-x-4">
                <secondary-button class="shadow-sm shadow-crust space-x-1.5 text-sm"
                                  @click.prevent="showViewCategoryModal">
                    <list-bullet-icon class="size-5 shrink-0" />
                    <span class="text-sm normal-case">Categories</span>
                </secondary-button>

                <Deferred data="posts">
                    <div v-if="posts.meta.total > posts.meta.per_page">
                        <no-info-pager :pagination="posts.meta" />
                    </div>
                    <template #fallback></template>
                </Deferred>

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
                        <div v-if="page.props.app.hasOwnProperty('flash') && page.props.app.flash !== null"
                             class="my-4">
                            <div v-if="page.props.app.flash.type === 'success'"
                                 class="bg-green shadow shadow-crust text-base px-6 py-4">
                                {{ page.props.app.flash.message }}
                            </div>
                            <div v-else-if="page.props.app.flash.type === 'error'"
                                 class="bg-red shadow shadow-crust text-base px-6 py-4">
                                {{ page.props.app.flash.message }}
                            </div>
                        </div>

                        <div class="overflow-hidden shadow shadow-mantle ring-1 ring-mantle/5">
                            <table class="min-w-full divide-y divide-overlay0">
                                <thead class="bg-surface0">
                                <tr>
                                    <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-text sm:pl-6 md:w-3/5"
                                        scope="col">
                                        Title
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                        Published
                                    </th>
                                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                        Created
                                    </th>
                                    <th class="relative py-3.5 pl-3 pr-4 sm:pr-6" scope="col">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-surface0 bg-surface2">
                                <Deferred data="posts">
                                    <tr v-for="(post, i) in posts.data" :key="i"
                                        :class="[i % 2 === 0 ? 'bg-surface0' : 'bg-surface1', 'hover:bg-surface2 select-none cursor-default']">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium md:w-3/5">
                                            <p class="text-text" v-text="post.title" />
                                            <p class="text-subtext0 font-normal" v-text="post.author.name" />
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm space-x-2">
                                            <div class=" inline-flex items-center space-x-2">
                                                <span :class="post.published_at === null ? 'bg-red' : 'bg-green'"
                                                      class="size-2.5 rounded-full inline-block" />
                                                <span
                                                    v-text="moment(post.published_at).format('Do MMM YYYY [at] hh:mma')" />
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm"
                                            v-text="moment(post.created_at).format('Do MMM YYYY [at] hh:mma')" />
                                        <td class="relative whitespace-nowrap text-right text-sm font-medium pr-4">
                                            <x-link v-if="post.deleted_at === null"
                                                    :href="route('backend.blog.edit', { id: post.id})"
                                                    class="bg-blue text-mantle hover:bg-base hover:text-text py-3 px-4 transition duration-150 ease-in">
                                                Edit
                                            </x-link>

                                            <x-link v-else
                                                    :href="route('backend.blog.edit', { id: post.id })"
                                                    class="bg-red text-mantle hover:bg-base hover:text-text py-3 px-4 transition duration-150 ease-in">
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
        </div>

        <form @submit.prevent="deleteCategory">
            <modal @close="showingViewCategoryModal = false" :show="showingViewCategoryModal" :closeable="true">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-text">
                            Viewing Categories
                        </h2>
                        <primary-button class="shadow-sm shadow-crust space-x-1.5 text-sm"
                                        @click.prevent="showCategoryModal">
                            <plus-circle-icon class="size-5 shrink-0" />
                            <span class="text-sm normal-case">New Category</span>
                        </primary-button>
                    </div>

                    <div v-if="showResponse" class="my-4">
                        <div v-if="response.type === 'success'"
                             class="bg-green shadow shadow-crust text-base px-6 py-4">
                            {{ response.message }}
                        </div>
                        <div v-else-if="response.type === 'error'"
                             class="bg-red shadow shadow-crust text-base px-6 py-4">
                            {{ response.message }}
                        </div>
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
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-text" scope="col">
                                    Parent
                                </th>
                                <th class="relative py-3.5 pl-3 pr-4 sm:pr-6" scope="col">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-surface0 bg-surface2">
                            <Deferred data="posts">
                                <tr v-for="(category, i) in categories.data" :key="i"
                                    :class="[i % 2 === 0 ? 'bg-surface0' : 'bg-surface1', 'hover:bg-surface2 select-none cursor-default']">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium md:w-3/5">
                                        <p class="text-text" v-text="category.name" />
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-text">
                                        <p class="text-text">{{ category.posts_count }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-text">
                                        <p v-if="category.parent !== null">{{ category.parent.name }}</p>
                                        <p v-else class="italic text-xs"> - null -</p>
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
                    <h2 class="text-lg font-medium text-gray-900">
                        Create A Category
                    </h2>

                    <div class="mt-6">
                        <div v-if="page.props.app.hasOwnProperty('flash') && page.props.app.flash !== null"
                             class="my-4">
                            <div v-if="page.props.app.flash.type === 'success'"
                                 class="bg-green shadow shadow-crust text-base px-6 py-4">
                                {{ page.props.app.flash.message }}
                            </div>
                            <div v-else-if="page.props.app.flash.type === 'error'"
                                 class="bg-red shadow shadow-crust text-base px-6 py-4">
                                {{ page.props.app.flash.message }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <input-label for="name" value="Category" />

                            <text-input id="name" v-model="form.name"
                                        class="mt-1 block w-3/4" placeholder="Category Name" type="text" />

                            <input-error :message="form.errors.name" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <input-label for="parent_id" value="Parent Category" />

                            <select id="parent_id" v-model="form.parent_id"
                                    class="mt-1 block w-3/4 border border-overlay1 focus:border-blue bg-crust text-text shadow-sm shadow-surface1">
                                <option selected :value="null">None</option>
                                <option v-for="category in categories.data" v-if="categories.data.length > 0"
                                        :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                                <option v-if="categories.data.length === 0" selected disabled>
                                    No categories
                                </option>
                            </select>

                            <input-error :message="form.errors.parent_id" class="mt-2" />
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
