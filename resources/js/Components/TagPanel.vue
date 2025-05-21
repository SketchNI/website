<script setup>
import TagItem from "@/Components/TagItem.vue";
import { RssIcon } from "@heroicons/vue/24/solid/index.js";

defineProps({
    tags: Object,
    type: String,
    selected: Array | null,
})
</script>

<template>
    <div class="w-full lg:w-2/6 space-y-3">
        <div class="bg-zinc-800 p-4 shadow shadow-black">
            <TagItem :tags="tags" :type="type" />

            <a class="flex items-center mt-4 text-primary transition duration-150 ease-in-out group"
               href="/feed">
                <span class="group group-hover:bg-orange/40 px-1 py-1">
                    <rss-icon class="size-5 text-orange group" />
                </span>
                <span class="group group-hover:bg-primary/40 px-2 py-0.5">RSS Feed</span>
            </a>
        </div>
    </div>
</template>

<style scoped lang="postcss">

</style>
