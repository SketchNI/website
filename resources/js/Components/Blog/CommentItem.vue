<script setup>
import moment from "moment";
import useRole from "@/Composables/useRole.js";
import DangerButton from "@/Components/DangerButton.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import useUser from "@/Composables/useUser.js";

const props = defineProps({
    post_id: Number,
    comment: {
        id: Number,
        author: {
            name: String,
            email: String,
        },
        comment: String,
        created_at: String,
    }
})

const role = useRole();
const user = useUser();
const showingModal = ref(false);

const showDeleteModal = () => {
    showingModal.value = !showingModal.value;
}

const form = useForm({
    post: props.post_id,
    comment: props.comment.id
});

const deleteComment = () => {
    form.delete(route('blog.comment.destroy', { post: props.post_id, comment: props.comment.id }), {
        onSuccess: () => {
            showingModal.value = false;
        }
    })
}

</script>

<template>
    <div role="listitem" class="bg-gray-800 p-5 shadow-sm shadow-black font-display">
        <div class="inline-flex justify-between w-full">
            <h4 class="text-xl m-0 p-0 text-white font-black">{{ comment.author.name }}</h4>
            <div v-if="comment.user_id === user.id || ['super-admin', 'admin', 'mod'].includes(role)">
                <button
                    class="delete-btn font-sans"
                    type="button"
                    @click="showDeleteModal">
                    <span class="inline-flex items-center space-x-1.5">
                        <i class="fas fa-trash" />
                        <span>Delete</span>
                    </span>
                </button>
            </div>
        </div>
        <div class="inline-flex space-x-2 items-baseline">
            <span v-if="['super-admin', 'admin', 'mod'].includes(role)"
                  class="space-x-2">
                <span class="text-gray-400 text-sm">
                    {{ comment.author.email }}
                </span>
                <span class="text-gray-500">|</span>
            </span>
            <time v-if="moment().diff(comment.created_at, 'days') <= 7"
                  class="text-gray-400 font-semibold text-sm"
                  :datetime="comment.created_at"
                  :title="moment(comment.created_at).format('Do MMM YYYY [at] hh:mma')">
                {{ moment(new Date()).from(comment.created_at, true) }} ago
            </time>
        </div>
        <div role="article" class="mt-4 prose prose-blue prose-invert font-medium font-display" v-html="comment.comment" />
    </div>

    <div v-if="comment.user_id === user.id || ['super-admin', 'admin', 'mod'].includes(role)">
        <form @submit.prevent="deleteComment">
            <modal @close="showDeleteModal" :show="showingModal" :closeable="true">
                <div class="px-6">
                    <div class="flex items-center justify-between py-6">
                        <h2 class="text-2xl font-medium text-red">
                            Delete Comment
                        </h2>
                    </div>

                    <div class="text-red inline-flex space-x-4 items-center">
                        <div class="text-xl bg-secondary/60 px-3 py-2 rounded-full">
                            <i class="fa fa-triangle-exclamation text-white" />
                        </div>
                        <span class="text-gray-100 text-xl">Are you sure you want to delete this comment?</span>
                    </div>

                    <div class="my-6 flex justify-end space-x-3">
                        <secondary-button @click="showDeleteModal">
                            Cancel
                        </secondary-button>
                        <danger-button type="submit" class="space-x-1.5">
                            <i class="fas fa-trash" />
                            <span>Delete</span>
                        </danger-button>
                    </div>
                </div>
            </modal>
        </form>
    </div>
</template>

<style scoped>
.delete-btn {
    @apply bg-opacity-65 text-white text-xs px-1.5 py-1 shadow-sm rounded-sm shadow-red-700/50
    bg-red-500 hover:bg-red-600 hover:shadow-red-800/70;
    @apply transition duration-150 ease-in;
}
</style>
