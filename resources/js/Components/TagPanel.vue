<script setup>
import TagItem from "@/Components/TagItem.vue";

defineProps({
    tags: Object,
    type: String,
    selected: Array|null,
})
</script>

<template>
    <div class="bg-mantle w-full lg:w-2/6 p-4 shadow shadow-crust">
        <TagItem :tags="tags" :type="type" />
    </div>
</template>

<style scoped lang="postcss">

</style>
