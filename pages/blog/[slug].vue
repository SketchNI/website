<script setup>
import moment from "moment/moment";

const route = useRoute();
const { data } = await useAsyncData('page-data', () => queryContent(route.path).findOne())
const article = data;
</script>

<template>
    <div class="flex items-start space-x-4">
        <div class="w-3/4">
            <div>
                <ContentRenderer :value="article">
                    <div class="flex items-center justify-between">
                        <h2 class="my-0 text-3xl">{{ article.title }}</h2>
                    </div>
                    <p class="text-sm text-gray-400 my-4">
                        Published {{ moment(article.created).fromNow() }}
                        <time itemprop="datePublished" :title="moment(article.created).toISOString()" class="text-gray-500">
                            ({{ moment(article.created).format('Do MMM YYYY, h:mma') }})
                        </time>
                    </p>
                    <div>
                        <div class="tag-bar">
                            <span v-for="(item, i) in article.categories" :key="i" class="tag">
                                {{ item }}
                            </span>
                        </div>
                    </div>
                    <div class="prose-xl prose-blue">
                        <ContentRendererMarkdown :value="article" />
                    </div>
                </ContentRenderer>
            </div>
        </div>
    </div>
</template>

<style scoped>
.tag-bar {
    @apply space-x-5 mb-6 bg-gray-900 px-4 py-3 rounded-full;
}

.tag {
    @apply inline-flex text-xs bg-blue-900 px-3 py-1.5 font-sans rounded-full;
}
</style>
