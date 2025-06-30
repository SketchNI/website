<script setup>
import moment from "moment/moment";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { EllipsisVerticalIcon } from "@heroicons/vue/16/solid";
import { nextTick, ref } from "vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import hljs from "highlight.js/lib/core";
import json from "highlight.js/lib/languages/json";
import '@/../css/felipec.css';

const props = defineProps({
    item: Object,
})

const showingModal = ref(false);
hljs.registerLanguage('json', json);

const toggleProperties = () => {
    showingModal.value = !showingModal.value;
    nextTick(() => {
        hljs.highlightAll();
    })
}
</script>

<template>
    <td class="w-80 pl-4 pr-3 text-sm font-medium">
        <p v-text="item.user.name" />
        <p class="text-gray-300 text-xs font-normal" v-text="item.user.email" />
    </td>
    <td class="w-32 pl-4 pr-3 text-sm font-medium">
        <div v-if="item.subject_data.subject_type !== null">
            <p v-text="item.subject_data.subject_type.replace('App\\Models\\', '')" />
        </div>
    </td>
    <td class="w-1/12 text-sm font-medium">
        <div v-if="item.subject_data.subject_id !== null" class="flex items-center">
            <x-link v-if="item.target.url !== false" :href="item.target.url" class="target-url"
                    v-text="item.target.label" />
            <span v-else v-text="item.target.label" class="target-url" />

            <x-link v-if="item.target.doRestore"
                    :href="route('backend.blog.restore', item.target.id)"
                    class="underline text-primary hover:text-white">
                Restore
            </x-link>
        </div>
    </td>
    <td class="w-24 whitespace-nowrap px-3 text-sm font-medium">
        <p v-text="item.type" />
    </td>
    <td class="whitespace-nowrap px-3 text-sm font-medium">
        <p v-text="item.description" />
    </td>
    <td class="whitespace-nowrap px-3 text-sm font-medium">
        <p v-text="item.event ?? '- none -'" />
    </td>
    <td class="w-44 whitespace-nowrap px-3 text-sm"
        v-text="moment(item.created_at).format('Do MMM YYYY [at] hh:mm:ssa')" />
    <td class="w-24 px-3">
        <primary-button class="text-xs bg-primary normal-case space-x-1.5" type="button" @click="toggleProperties">
            <ellipsis-vertical-icon class="size-4" />
            <span>Properties</span>
        </primary-button>

        <modal :closeable="true" :show="showingModal" @close="toggleProperties">
            <div class="p-6">
                <h2 class="text-lg font-medium text-white">
                    Additional Properties
                </h2>

                <div class="mt-1 text-sm -mx-6 -my-6 overflow-y-auto max-h-[48rem]">
                    <div v-for="(items, name) in item.properties">
                        <div class="">
                            <h3 class="px-6 py-4 text-lg font-medium bg-gray-800 border-y border-primary mt-4 block">
                                {{ name.toUpperCase() }}</h3>

                            <table class="min-w-full">
                                <thead>
                                <tr class="divide-x divide-gray-700 bg-gray-900">
                                    <th class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold" scope="col">
                                        Key
                                    </th>
                                    <th class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold" scope="col">
                                        Value
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-600">
                                <tr v-for="(v, k, i) in items" :key="i"
                                    :class="[i % 2 ? 'bg-gray-700' : 'bg-gray-800']">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium">
                                        {{ k }}
                                    </td>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm text-gray-300 text-wrap">
                                        <div v-if="typeof v !== 'object'">{{ v }}</div>
                                        <div v-else>
                                            <pre><code>{{ JSON.stringify(v, null, 2) }}</code></pre>
                                        </div>

                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="mt-6 flex -mx-6 justify-end -mb-8 py-6 px-6 border-t border-primary/40">
                    <secondary-button @click="toggleProperties">
                        Close
                    </secondary-button>
                </div>
            </div>
        </modal>
    </td>
</template>

<style scoped>
.target-url {
    @apply pr-3 block py-4 hover:bg-base text-primary pl-4 transition duration-150 ease-in text-ellipsis truncate w-[25ch];
}
</style>
