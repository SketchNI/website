<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    page: {
        id: String,
        author: {
            id: Number,
            name: String,
            email: String,
        },
        title: String,
        slug: String,
        url: String,
        content: String,
        is_published: Boolean,
        published_at: Date|null,
        is_deleted: Boolean,
        deleted_at: Date|null,
        created_at: Date,
        updated_at: Date,
    }
})
</script>

<template>
    <x-head>
        <title>{{ page.title }}</title>
        <meta :content="page.title" property="og:title" />
        <meta :content="page.raw_content.substring(0, 140)" property="og:description" />
        <meta :content="page.url" property="og:url" />
        <meta content="en_GB" property="og:locale" />
        <meta content="sketchni.uk" property="og:site_name" />
        <meta content="article" property="og:type" />
        <meta :content="page.raw_content.substring(0, 140)" name="description" />
        <meta content="Written by" name="twitter:label1" />
        <meta :content="page.author.name" name="twitter:data1" />
        <link :href="page.url" rel="canonical" />
    </x-head>
    <app-layout>
        <article class="article prose max-w-[82.5ch] prose-blue prose-invert">
            <div class="flex flex-col space-y-2 mb-4">
                <h1 class="text-5xl mb-2 w-full border-b border-blue/60 pb-2" v-text="page.title" />
            </div>

            <div v-html="page.content" class="w-full" />
        </article>
    </app-layout>
</template>

<style scoped>

</style>
