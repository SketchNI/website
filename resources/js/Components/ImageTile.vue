<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { PencilIcon } from "@heroicons/vue/16/solid";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import moment from "moment/moment";
import { toast } from "vue3-toastify";

const props = defineProps({
    image: {
        id: String,
        image: String,
        from: String,
        caption: String,
        alt_text: String,
        image_url: String,
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

const isCopied = ref(false);

const copyToClipboard = (path) => {
    alert(path)
    if(!navigator.clipboard) {
        toast.error('You must be connected over HTTPS to use `navigator.clipboard`.', { theme: 'dark' });
        return;
    }
    isCopied.value = true;
    navigator.clipboard.writeText(path).then(() => {
        setTimeout(() => isCopied.value = false, 2500);
    });
}
</script>

<template>
    <div class="relative">
        <img :alt="image.alt_text" :src="image.image" class="w-44" />
        <button
            class="copy-btn"
            type="button"
            @click="copyToClipboard(image.image)">
            <span v-if="isCopied" class="inline-flex items-center space-x-1.5">
                <i class="fas fa-check" />
                <span>Copied!</span>
            </span>
            <span v-else class="inline-flex items-center space-x-1.5">
                <i class="fas fa-copy" />
                <span>Copy</span>
            </span>
        </button>
    </div>

    <div v-if="editImage">
        <form @submit.prevent="updateImage">
            <div>
                <input-label for="caption" value="Caption" />
                <text-input id="caption" v-model="form.caption" class="w-full" />
            </div>

            <div class="mt-2">
                <input-label for="alt_text" value="Alt Text" />
                <text-input id="alt_text" v-model="form.alt_text" class="w-full" />
            </div>

            <div class="flex justify-between mt-2">
                <secondary-button class="text-xs px-1 py-0.5" @click.prevent="editImage = false">Cancel
                </secondary-button>
                <primary-button class="text-xs px-1 py-0.5" type="submit">Save</primary-button>
            </div>
        </form>
    </div>
    <div v-else class="relative flex items-center justify-between">
        <p class="w-2/3" v-text="form.caption === '' ? 'No caption' : form.caption" />
        <button
            class="edit-btn"
            type="button" @click="editImage = true">
            <pencil-icon class="size-4" />
            <span>Edit</span>
        </button>
    </div>
    <p class="text-overlay1 text-xs">
        Uploaded on {{ moment(image.created_at).format('Do MMM YYYY [at] hh:mma') }}<span v-if="image.from !== null"> from {{ image.from }}</span>.
    </p>
</template>

<style scoped>
.copy-btn {
    @apply bg-opacity-65 text-white text-xs px-1.5 py-1 shadow-sm rounded-sm shadow-purple-700/50 absolute
    right-0 top-0 bg-purple-500 hover:bg-purple-600 hover:shadow-purple-800/70;
    @apply transition duration-150 ease-in;
}

.edit-btn {
    @apply inline-flex items-center space-x-1 bg-opacity-65 text-white text-sm px-1.5 py-1 shadow-sm rounded-sm shadow-blue-700/50
    bg-blue-500 hover:bg-blue-600 hover:shadow-blue-800/70;
    @apply transition duration-150 ease-in;
}
</style>
