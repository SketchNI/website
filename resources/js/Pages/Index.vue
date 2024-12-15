<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Divider from "@/Components/divider.vue";
import moment from "moment/moment";

const socials = [
    { name: 'Blue Sky', href: 'https://bsky.app/profile/sketchni.uk', logo: 'bluesky' },
    { name: 'Twitter', href: 'https://x.com/SketchNI', logo: 'twitter' },
    { name: 'Twitch', href: 'https://twitch.tv/SketchNI', logo: 'twitch' },
    { name: 'Discord', href: 'https://discord.com/invite/GQbXUVCneJ', logo: 'discord' },
    { name: 'Steam', href: 'https://steamcommunity.com/id/sketchni/', logo: 'steam' },
    { name: 'Instagram', href: 'https://www.instagram.com/sketchni.uk/', logo: 'instagram' }
]

</script>

<template>
    <x-head title="Home" />

    <app-layout>
        <div class="lg:space-x-6 text-xl mx-6 text-text lg:mx-auto lg:flex lg:flex-row lg:items-center lg:justify-center">
            <div>
                <img src="/images/chibinobg.png" alt="SketchNI Avatar" class="homepage-sketch" />
            </div>
            <div class="lg:w-[40ch] mt-6 lg:mt-0 space-y-6">
                <div>
                    Hello! I'm Sketch 🐍 (aka Denver). I'm a
                    <span class="text-green">{{ moment().subtract(1991, 'years').format("YY ") }}year old</span> web
                    developer
                    and linux tinkerer with over <span class="text-blue font-black">0</span> years experience!
                </div>

                <divider class="lg:hidden" />

                <p class="font-black">
                    I dev mainly with
                    <span class="text-red">Laravel</span> and
                    <span class="text-green">VueJS</span>.
                    I daily drive
                    <span class="text-blue">Windows 11</span> and
                    <span class="text-green">Linux Mint</span>.
                </p>

                <divider class="lg:hidden" />

                <div>
                    <p>
                        <span class="text-red">HTML: </span>
                        <span class="text-green">{{ moment().subtract(2007, 'years').format("YY ") }}</span> years
                    </p>
                    <p>
                        <span class="text-red">PHP: </span>
                        <span class="text-green">{{ moment().subtract(2010, 'years').format("YY ") }}</span> years
                    </p>
                    <p>
                        <span class="text-red">CSS: </span>
                        <span class="text-green">{{ moment().subtract(2010, 'years').format("YY ") }}</span> years
                    </p>
                    <p>
                        <span class="text-red">Linux: </span>
                        <span class="text-green">{{ moment().subtract(2009, 'years').format("YY ") }}</span> years
                    </p>
                    <p>
                        <span class="text-red">Javascript: </span>
                        <span class="text-green">{{ moment().subtract(2017, 'years').format("YY ") }}</span> years
                    </p>
                </div>

                <divider class="lg:hidden" />

                <p>
                    You can find me on these social networks:
                </p>
                <ul class="inline-flex space-x-2">
                    <li v-for="(item, i) in socials" :key="i">
                        <a :href="item.href" class="social-link">
                            <i :class="`fa-brands fa-${item.logo}`"></i>
                            <span class="sr-only" v-text="item.name" />
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </app-layout>
</template>
