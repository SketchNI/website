<script setup>
import { reactive } from "vue";

let lastfm = reactive({
    url: '',
    artwork: '',
    trackName: '',
    artist: '',
    album: '',
    show: false,
});

const fetchData = () => window.axios.get(route('lastfm')).then(res => {
    if (res.data !== "") {
        // Directly map the response data to the lastfm object
        lastfm.url = res.data.url || '';
        lastfm.artwork = res.data.artwork || '';
        lastfm.trackName = res.data.trackName || '';
        lastfm.artist = res.data.artist || '';
        lastfm.album = res.data.album || '';
        lastfm.show = true;
    }
});

fetchData();
setInterval(() => {
    fetchData()
}, 4000)
</script>

<template>
    <Suspense>
        <div class="w-full font-sans mt-4">
            <a target="_blank" :href="lastfm.url" v-if="lastfm.show" class="block w-full" :title="`${lastfm.album}`">
                <div class="flex w-full items-center space-x-2">
                    <img :src="lastfm.artwork" class="size-[4.3rem]"
                         :alt="`${lastfm.album} album artwork`" />
                    <div>
                        <p class="w-[20ch] truncate text-ellipsis">
                            <span class="font-bold">{{ lastfm.trackName }}</span>
                        </p>
                        <div>
                            <span class="text-gray-300">by </span>
                            <span class="font-bold">{{ lastfm.artist }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <template #fallback>
            <div class="flex flex-col w-full"></div>
        </template>
    </Suspense>
</template>
