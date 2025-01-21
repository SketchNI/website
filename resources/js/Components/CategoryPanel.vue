<template>
    <div class="bg-mantle w-2/6 p-4 shadow shadow-crust">
        <ul class="text-lg">
            <li v-for="category in categories" :key="category.id">
                <x-link :href="route('category.show', { category })" class="group parent category">
                    <span v-text="category.name" />
                    <span class="group cat-count" v-text="`(${category.posts_count} posts)`" />
                </x-link>
                <ul v-if="category.children.length > 0">
                    <li v-for="(child, i) in category.children" :key="i">
                        <x-link :href="route('category.show', { category: child })"
                                class="group child category">
                            <span v-text="child.name" />
                            <span class="group cat-count" v-text="`(${child.posts_count} posts)`" />
                        </x-link>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</template>

<script setup>
defineProps({
    categories: Array
})
</script>

<style scoped lang="postcss">
.category {
    @apply font-bold font-mono text-blue w-full text-normal cursor-pointer inline-flex
    items-center hover:bg-blue hover:text-base px-2 py-1 space-x-2
    transition duration-150 ease-in;

    &::before {
        content: ">";
        @apply pr-2 text-sm;
    }
}

.parent {
    @apply font-bold;
}

.child {
    @apply pl-6;
}

.cat-count {
    @apply text-sm font-normal text-subtext1 group-hover:text-black;
    @apply transition duration-150 ease-in;
}

</style>
