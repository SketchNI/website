<script setup>
import moment from 'moment';

const activeTag = ref(null);

const setActiveTag = (tag) => {
  activeTag.value = tag;
}
</script>

<template>
    <div>
        <Suspense>
            <div>
                <!-- Active tag display -->
                <div class="mb-2 flex items-center space-x-4" v-if="!!activeTag">
                    <p>Filtered: <span class="text-primary">{{ activeTag }}</span></p>
                    <div>
                        <button type="button" @click.prevent="activeTag = null"
                                class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-xs font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                            <span class="relative px-3 py-1.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                                Clear Filter
                            </span>
                        </button>
                    </div>
                </div>

                <!-- list of articles -->
                <div class="flex items-start space-x-4 space-y-3">
                    <div class="w-3/4">
                        <ContentList as="section" path="/" v-slot="{ list }" v-if="activeTag === null">
                            <nuxt-link :to="article._path" v-for="article in list.slice().reverse()"
                                       :key="article._path">
                                <article class="entry group mb-6">
                                    <div class="flex flex-col space-y-2">
                                        <h2 class="my-0 text-xl">{{ article.title }}</h2>

                                        <div class="tag-bar">
                                            <span v-for="(item, i) in article.categories" :key="i" class="tag">
                                                {{ item }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-400 my-3">
                                        Published {{ moment(article.created).fromNow() }}
                                        <time itemprop="datePublished" :title="moment(article.created).toISOString()" class="text-gray-500">
                                            ({{ moment(article.created).format('Do MMM YYYY, h:mma') }})
                                        </time>
                                    </p>
                                    <p class="text-base text-white line-clamp-2">{{ article.summary }}</p>
                                </article>
                            </nuxt-link>
                        </ContentList>

                        <ContentList path="/" v-slot="{ list }" v-else>
                            <nuxt-link :to="article._path"
                                       v-for="article in list.filter(i => i.categories.includes(activeTag)).slice().reverse()"
                                       :key="article._path">
                                <article class="prose prose-invert prose-blue prose-xl entry">
                                    <div class="flex flex-col space-y-2 mb-2">
                                        <h2 class="my-0">{{ article.title }}</h2>

                                        <div class="not-prose">
                                            <div class="tag-bar">
                                                <span v-for="(item, i) in article.categories" :key="i" class="tag">
                                                    {{ item }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-400 mt-3">
                                        Published {{ moment(article.created).fromNow() }}
                                        <time itemprop="datePublished" :title="moment(article.created).toISOString()"
                                              class="text-gray-500">
                                            ({{ moment(article.created).format('Do MMM YYYY, h:mma') }})
                                        </time>
                                    </p>
                                    <p class="text-base text-white line-clamp-2">{{ article.summary }}</p>
                                </article>
                            </nuxt-link>
                        </ContentList>
                    </div>

                    <div class="w-1/4">
                        <ContentQuery :path="$route.path" :only="['categories']" :limit="50" v-slot="{ data }">
                            <ContentRenderer :value="data" v-slot="data">
                                <ul class="space-y-2">
                                    <li class="text-sm uppercase font-bold text-gray-300">
                                        Tags
                                    </li>
                                    <li v-for="d in data.value">
                                        <ul class="space-y-2 mt-0">
                                            <li v-for="cat in d.categories" @click.prevent="setActiveTag(cat)"
                                                :class="cat === activeTag ? 'active-tag' : ''"
                                                class="cursor-pointer py-1.5 px-2 hover:bg-primary-dark/70 hover:text-white rounded-md hover:shadow">
                                                {{ cat }}
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </ContentRenderer>
                        </ContentQuery>
                    </div>
                </div>
            </div>
            <template #fallback>
                <div class="py-6 text-center h-56 shadow shadow-c-darkest rounded-md bg-c-dark text-3xl flex items-center justify-center">
                    <span class="animate-pulse">Loading articles</span>
                </div>
            </template>
        </Suspense>
    </div>
</template>

<style scoped>
.entry {
    @apply border border-transparent shadow shadow-c-darkest hover:shadow-c-dark rounded-md bg-c-dark p-6;
    @apply hover:bg-c-darkest hover:border-primary;
    @apply transition duration-150 ease-in;
}

.active-tag {
    @apply bg-primary-dark/70 text-white;
}

.tag-bar {
    @apply space-x-5 mb-6 bg-c-darkest group-hover:bg-c-dark px-4 py-3 rounded-full;
    @apply transition duration-150 ease-in;
}

.tag {
    @apply inline-flex text-xs bg-primary-dark px-3 py-1.5 font-sans rounded-full;
}
</style>
