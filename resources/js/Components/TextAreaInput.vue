<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <textarea
        class="border w-full max-h-52 border-blue-600/40 focus:border-blue bg-gray-700 text-text shadow-sm shadow-black"
        v-model="model"
        ref="input" />
</template>
