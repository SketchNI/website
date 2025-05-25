<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref } from "vue";

const props = defineProps({
    post: Object,
})

const form = useForm({
    blog: props.post,
    type: 'post',
    comment: ''
});

const page = usePage();
const showFlash = ref(true);

const sendComment = () => {
    form.post(route('blog.comment.create', { post: props.post }),  {
        onSuccess: () => {
            form.reset('comment')
            setTimeout(() => {
                showFlash.value = false
            }, 2500)
        }
    });
}
</script>

<template>
    <div class="bg-gray-800 p-5 shadow-sm shadow-black mb-4 w-full">
        <form @submit.prevent="sendComment" class="space-y-2">
            <div>
                <input-label for="comment" value="Leave a comment..." />

                <text-area-input v-model="form.comment" class="mt-1 mb-1 bg-base" />

                <input-error :message="form.errors.comment" />
            </div>

            <div class="flex justify-between items-center w-full">
                <div class="text-sm text-gray-400">Limited Markdown is supported.</div>
                <primary-button class="text-xs">Comment</primary-button>
            </div>
        </form>
    </div>
</template>

<style scoped>

</style>
