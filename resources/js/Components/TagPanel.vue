<script setup>
import TagItem from "@/Components/TagItem.vue";

defineProps({
    tags: Object,
    selected: Array|null,
})
</script>

<template>
    <div class="bg-mantle w-full lg:w-2/6 p-4 shadow shadow-crust">
        <TagItem :tags="tags" />
    </div>
</template>

<style scoped lang="postcss">

</style>
