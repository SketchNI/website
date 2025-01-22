<script setup>
import CategoryItem from "@/Components/CategoryItem.vue";

defineProps({
    categories: Array,
    selected: Array|null,
})
</script>

<template>
    <div class="bg-mantle w-full lg:w-2/6 p-4 shadow shadow-crust">
        <category-item :categories="categories" />
    </div>
</template>

<style scoped lang="postcss">

</style>
