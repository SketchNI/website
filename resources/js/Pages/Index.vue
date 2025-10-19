<script setup>
import Divider from "@/Components/divider.vue";
import moment from "moment/moment";

const socials = [
    { name: 'Blue Sky', href: 'https://bsky.app/profile/sketchni.uk', logo: 'bluesky', brand: true },
    { name: 'Fediverse', href: 'https://matrix.to/#/@sketchnx:matrix.org', logo: 'hashtag', brand: false },
    { name: 'Twitch', href: 'https://twitch.tv/SketchNI', logo: 'twitch', brand: true },
    { name: 'Discord', href: 'https://discord.com/invite/8RVhHeJH3x', logo: 'discord', brand: true },
    { name: 'Steam', href: 'https://steamcommunity.com/id/sketchni/', logo: 'steam', brand: true },
]

const eightyeights = [
    { name: 'Kaya', href: 'https://tired.moe', picture: 'https://tired.moe/assets/badges/tired.moe.gif' },
    { name: 'robin roses', href: 'https://robinroses.xyz', picture: 'https://robinroses.xyz/badges/me.gif' },
    { name: 'isabel roses', href: 'https://isabelroses.com', picture: 'https://isabelroses.com/badges/me.gif' },
    { name: 'Alyxia', href: 'https://alyxia.dev/', picture: 'https://alyxia.dev/static/img/88x31/self.png' },
    { name: 'thermia', href: 'https://girlthi.ng/~thermia/', picture: 'https://girlthi.ng/~thermia/img/88x31/thermia.gif' },
    { name: 'Elissa', href: 'https://elissa.moe', picture: '/storage/elissa.png' },
    { name: 'diza', href: 'https://dbw.neocities.org', picture: 'https://dbw.neocities.org/assets/me.png' },
    { name: 'tasky', href: 'https://tasky.nuxt.dev', picture: '/storage/tasky.webp' },
    
];
</script>

<template>
    <x-head title="Home" />

    <div
        class="font-display lg:space-x-6 text-xl text-gray-200 lg:flex lg:flex-row lg:justify-center">
        <div class="flex justify-center lg:justify-normal">
            <img alt="SketchNI Avatar" class="homepage-sketch size-60" src="/images/chibinobg.png" />
        </div>
        <div class="lg:w-[40ch] mt-6 lg:mt-0 space-y-6">
            <div>
                Hello! I'm Sketch 🐍. I'm a
                <span class="text-accent">{{ moment().diff("1991-02-04", 'years') }} year old</span> web
                developer
                and linux tinkerer with over
                <span class="text-primary font-black">{{ moment().subtract(2010, 'years').format("YY ") }}</span>
                years experience!
            </div>

            <divider class="lg:hidden" />

            <p class="">
                I dev mainly with
                <span class="text-secondary font-semibold">Laravel</span> and
                <span class="text-accent font-semibold">VueJS</span>.
                I daily drive
                <span class="text-primary font-semibold">Windows 11 Pro</span> and
                <span class="text-accent font-semibold">Arch Linux</span>.
            </p>

            <divider class="lg:hidden" />

            <div class="max-lg:text-center max-lg:w-full">
                <p>
                    <span class="text-primary">HTML: </span>
                    <span class="text-accent">{{ moment().subtract(2007, 'years').format("YY ") }}</span> years
                </p>
                <p>
                    <span class="text-primary">PHP: </span>
                    <span class="text-accent">{{ moment().subtract(2010, 'years').format("YY ") }}</span> years
                </p>
                <p>
                    <span class="text-primary">CSS: </span>
                    <span class="text-accent">{{ moment().subtract(2010, 'years').format("YY ") }}</span> years
                </p>
                <p>
                    <span class="text-primary">Linux: </span>
                    <span class="text-accent">{{ moment().subtract(2009, 'years').format("YY ") }}</span> years
                </p>
                <p>
                    <span class="text-primary">Javascript: </span>
                    <span class="text-accent">{{ moment().subtract(2017, 'years').format("YY ") }}</span> years
                </p>
                <p>
                    <span class="text-primary">Python: </span>
                    <span class="text-accent">{{ moment().subtract(2024, 'years').format('YY ') }}</span> years
                </p>
            </div>

            <divider class="h-px" />

            <p class="max-lg:text-center max-lg:w-full">
                You can find me on these social networks:
            </p>
            <ul class="inline-flex space-x-1 sm:space-x-2 max-lg:justify-center max-lg:w-full">
                <li v-for="(item, i) in socials" :key="i" class="flex justify-between">
                    <a :href="item.href" class="social-link">
                        <i :class="[!item.brand ? 'fa-solid' : 'fa-brands', `fa-${item.logo}`]"></i>
                        <span class="sr-only" v-text="item.name" />
                    </a>
                </li>
            </ul>

            <divider class="h-px" />

            <div class="block">
                <p class="mb-4">
                    <span class="font-display font-bold text-primary">Meet The Weirdos</span>
                </p>
                <ul class="inline-flex space-x-1 sm:space-x-2 max-lg:justify-center max-lg:w-full">
                    <li v-for="(item, i) in eightyeights" :key="i" class="flex justify-between">
                        <a :href="item.href">
                            <img :src="item.picture" :alt="item.name" :title="item.name" class="w-[88px] h-[31px]" />
                            <span class="sr-only" v-text="item.name" />
                        </a>
                    </li>
                </ul>

                <div class="flex items-start text-sm mt-4">
                    <div>
                        <p>Add mine:</p>
                        <img
                        src="https://sketchni.uk/images/sketch.png" alt="SketchNI" title="SketchNI" />
                        <code class="block select-all p-2 !overflow-x-clip leading-6 mt-2">&lt;img
                            src="https://sketchni.uk/images/sketch.png" alt="SketchNI" title="SketchNI" /></code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
