<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineProps({
    user: {
        id: Number,
        name: String,
        email: String,
        email_verified: Boolean,
        created_at: String,
        role: String,
        permissions: Array,
        permissions_count: Number,
        roles_count: Number,
        posts_count: Number,
    }
});

</script>

<template>
    <x-head title="Edit User" />

    <admin-layout>
        <pre class="bg-mantle shadow-sm shadow-surface0 p-4">{{ JSON.stringify(user, null, 4) }}</pre>
    </admin-layout>
</template>

<style scoped>

</style>
