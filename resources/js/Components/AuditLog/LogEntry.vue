<script setup>
import moment from "moment/moment";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { EllipsisVerticalIcon } from "@heroicons/vue/16/solid";
import { nextTick, ref } from "vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import hljs from "highlight.js/lib/core";
import json from "highlight.js/lib/languages/json";
import '@/../css/a11y-dark.css';

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

//const hl = (data) => hljs.highlight(data, { language: "json" });
</script>

<template>
    <td class="w-80 pl-4 pr-3 text-sm font-medium">
        <p class="text-text" v-text="item.user.name" />
        <p class="text-subtext0 text-xs font-normal" v-text="item.user.email" />
    </td>
    <td class="w-32 pl-4 pr-3 text-sm font-medium">
        <div v-if="item.subject_data.subject_type !== null">
            <p class="text-text" v-text="item.subject_data.subject_type.replace('App\\Models\\', '')" />
        </div>
    </td>
    <td class="w-1/12 text-sm font-medium">
        <div v-if="item.subject_data.subject_id !== null">
            <x-link :href="item.target.url" class="pr-3 block py-4 hover:bg-base text-blue pl-4 apply transition duration-150 ease-in"
                    v-text="item.target.label" />
        </div>
    </td>
    <td class="w-24 whitespace-nowrap px-3 text-sm font-medium text-text">
        <p class="text-text" v-text="item.type" />
    </td>
    <td class="whitespace-nowrap px-3 text-sm font-medium text-text">
        <p class="text-text" v-text="item.description" />
    </td>
    <td class="whitespace-nowrap px-3 text-sm font-medium text-text">
        <p class="text-text" v-text="item.event ?? '- none -'" />
    </td>
    <td class="w-44 whitespace-nowrap px-3 text-sm"
        v-text="moment(item.created_at).format('Do MMM YYYY [at] hh:mma')" />
    <td class="w-24 px-3">
        <primary-button class="text-xs normal-case space-x-1.5" type="button" @click="toggleProperties">
            <ellipsis-vertical-icon class="size-4" />
            <span>Properties</span>
        </primary-button>

        <modal :closeable="true" :show="showingModal" @close="toggleProperties">
            <div class="p-6">
                <h2 class="px-6 text-lg font-medium text-subtext1">
                    Additional Properties
                </h2>

                <div class="mt-1 text-sm -mx-6 -my-6 overflow-y-auto max-h-[48rem]">
                    <div v-for="(items, name) in item.properties">
                        <div class="">
                            <h3 class="px-6 py-4 text-lg font-medium text-subtext1 bg-crust border-y border-blue mt-4 block">
                                {{ name.toUpperCase() }}</h3>

                            <table class="min-w-full">
                                <thead>
                                <tr class="divide-x divide-surface1 bg-mantle">
                                    <th class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-text" scope="col">
                                        Key
                                    </th>
                                    <th class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-text" scope="col">
                                        Value
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-surface1">
                                <tr v-for="(v, k, i) in items" :key="i"
                                    :class="[i % 2 ? 'bg-surface0/50' : 'bg-surface1/50 px-6']">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900">
                                        {{ k }}
                                    </td>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm text-gray-500 text-wrap">
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

                <div class="mt-6 flex -mx-6 justify-end -mb-8 py-6 px-6 border-t border-blue/40">
                    <secondary-button class="bg-surface2" @click="toggleProperties">
                        Close
                    </secondary-button>
                </div>
            </div>
        </modal>
    </td>
</template>

<style scoped>

</style>
