<script setup lang="ts">
import logo from '~/assets/images/chibinobg.png';

const links = [
  { label: 'Home', href: '/', },
  { label: 'Blog', href: '/blog' },
  { label: 'Teams', href: '/teams' },
];
</script>

<template>
    <div>
        <nav>
            <div class="nav-container">
                <div>
                    <nuxt-link to="/" class="text-3xl logo">
                        <span class="text-blue">Ske</span>
                        <span class="text-red">tch</span>
                        <span class="text-green">NI</span>
                    </nuxt-link>

                    <div class="nav-wrapper">
                        <nuxt-link class="link" :to="link.href" :key="i" v-for="(link, i) in links">{{ link.label }}
                        </nuxt-link>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            <slot />
        </main>
    </div>
</template>
