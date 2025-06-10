<script setup>
import TagItem from "@/Components/TagItem.vue";

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
        </div>
    </div>
</template>

<style scoped lang="postcss">

</style>
