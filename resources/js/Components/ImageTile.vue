<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { PencilIcon } from "@heroicons/vue/16/solid";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import moment from "moment/moment";

const props = defineProps({
    image: {
        id: String,
        image: String,
        from: String,
        caption: String,
        alt_text: String,
    },
});

const editImage = ref(false);

const form = useForm({
    id: props.image.id,
    alt_text: props.image.alt_text ?? '',
    caption: props.image.caption ?? '',
});

const updateImage = () => {
    window.axios.put(
        route('backend.images.update', { image: props.image }),
        { id: form.id, alt_text: form.alt_text, caption: form.caption }
    )
        .then((res) => {
            window.mitt.emit('image:update', res.data);
        })
        .catch((error) => {
            window.mitt.emit('image:update', error.response.data);
        })
        .finally(() => editImage.value = false);
}
</script>

<template>
    <img :src="image.image" :alt="image.alt_text" class="" />
    <div v-if="editImage">
        <form @submit.prevent="updateImage">
            <div>
                <input-label for="caption" value="Caption" />
                <text-input v-model="form.caption" id="caption" class="w-full" />
            </div>

            <div class="mt-2">
                <input-label for="alt_text" value="Alt Text" />
                <text-input v-model="form.alt_text" id="alt_text" class="w-full" />
            </div>

            <div class="flex justify-between mt-2">
                <secondary-button @click.prevent="editImage = false" class="text-xs px-1 py-0.5">Cancel</secondary-button>
                <primary-button type="submit" class="text-xs px-1 py-0.5">Save</primary-button>
            </div>
        </form>
    </div>
    <div v-else class="flex items-center justify-between">
        <p class="w-2/3" v-text="form.caption === '' ? 'No caption' : form.caption" />
        <button class="flex items-center space-x-1 text-sm text-blue hover:text-blue-400 transition duration-150 ease-in"
                type="button" @click="editImage = true">
            <pencil-icon class="size-4" />
            <span>Edit</span>
        </button>
    </div>
    <p class="text-overlay1 text-sm">
        Uploaded on {{ moment(image.created_at).format('Do MMM YYYY [at] hh:mma') }}
    </p>
</template>

<style scoped>

</style>
