<script setup>
import logo from "assets/images/chibinobg.png";

const socials = [
  { name: 'Blue Sky', href: 'https://bsky.app/profile/sketchni.uk', logo: 'bluesky' },
  { name: 'Twitter', href: 'https://x.com/SketchNI', logo: 'twitter' },
  { name: 'Twitch', href: 'https://twitch.tv/SketchNI', logo: 'twitch' },
  { name: 'Discord', href: 'https://discord.com/invite/GQbXUVCneJ', logo: 'discord' },
  { name: 'Steam', href: 'https://steamcommunity.com/id/sketchni/', logo: 'steam' },
  { name: 'Instagram', href: 'https://www.instagram.com/sketchni.uk/', logo: 'instagram' },
]
</script>

<template>
    <div class="space-x-6 text-xl text-white mx-auto flex items-center justify-center">
        <div>
            <img :src="logo" alt="SketchNI Avatar" class="logo" />
        </div>
        <div class="w-[40ch] space-y-6">
            <p>
                Hello! I'm Sketch 🐍 (aka Denver). I'm a <span class="text-green">{{ new Date().getFullYear() - 1991 }}
                year old</span> web developer and linux tinkerer with over 0 years experience!
            </p>

            <p class="font-black">
                I dev mainly with
                <span class="text-red">Laravel</span> and
                <span class="text-green">VueJS</span>.
                I daily drive
                <span class="text-blue">Windows 11</span> and
                <span class="text-green">Linux Mint</span>.
            </p>

            <div>
                <p><span class="text-red">HTML:</span> <span class="text-green">{{ new Date().getFullYear() - 2007 }}</span> years</p>
                <p><span class="text-red">PHP:</span> <span class="text-green">{{ new Date().getFullYear() - 2010 }}</span> years</p>
                <p><span class="text-red">CSS:</span> <span class="text-green">{{ new Date().getFullYear() - 2010 }}</span> years</p>
                <p><span class="text-red">Linux:</span> <span class="text-green">{{ new Date().getFullYear() - 2009 }}</span> years</p>
                <p><span class="text-red">Javascript:</span> <span class="text-green">{{ new Date().getFullYear() - 2019 }}</span> years</p>
            </div>

            <p>
                You can find me on these social networks:
            </p>
            <ul class="inline-flex space-x-2">
                <li v-for="(item, i) in socials" :key="i">
                    <nuxt-link :to="item.href" class="social-link">
                        <i :class="['fa-brands', `fa-${item.logo}`]"></i> <span class="sr-only">{{ item.name }}</span>
                    </nuxt-link>
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped>
.logo {
    @apply bg-black p-2 border-2 border-primary w-64 h-64;
}

.social-link {
    @apply text-4xl px-3 py-2 hover:shadow hover:shadow-c-darkest hover:bg-c-dark hover:text-white;
    @apply transition duration-150 ease-in;
}
</style>
