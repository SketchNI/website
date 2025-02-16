<script setup>
import AppLayout from '@/Layouts/AppLayout'

defineProps({
    ticket: {
        id: String,
        user: Object,
        status: {
            value: String,
            label: String,
        },
        subject: String,
    }
})
</script>

<template>
    <x-head :title="`Viewing Ticket #${ticket.id}`" />
    <app-layout>

    </app-layout>
</template>
