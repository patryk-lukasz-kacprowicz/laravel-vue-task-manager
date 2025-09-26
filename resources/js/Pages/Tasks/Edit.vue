<script setup>
import {Head, router} from "@inertiajs/vue3";
import {ref} from "vue";
import Navbar from "@/Components/Custom/Navbar.vue";

const props = defineProps({
    task: {
        type: Object,
        required: true,
        default: {},
    }
})

const task = props.task.data

const validationErrors = ref({});
const statusMessage = ref({ message: '', type: '' });

const form = ref({
    is_completed: task.is_completed,
    title: task.title,
    description: task.description,
});

const submitForm = async () => {
    try {
        const payload = {
            ...form.value,
            is_completed: form.value.is_completed ? true : false
        }

        const response = await axios.patch(route('tasks.update', task.id), payload)

        statusMessage.value = {
            message: response.data.message || 'Task successfully updated!',
            type: 'success'
        }
    } catch (error) {
        if (error.response) {
            const responseData = error.response.data;

            if (error.response.status === 422 && responseData && responseData.errors) {
                validationErrors.value = responseData.errors

                statusMessage.value = {
                    message: responseData.message || 'There were validation errors. Please check form.',
                    type: 'error'
                }
            } else if (responseData && responseData.message) {
                statusMessage.value = {
                    message: responseData.message,
                    type: 'error'
                }
            } else {
                statusMessage.value = {
                    message: `A server error has occurred. Error code: ${error.response.status}`,
                    type: 'error'
                }
            }
        } else {
            statusMessage.value = {
                message: 'Unable to connect to the server. Please try again later.',
                type: 'error'
            };
        }
    }
}
</script>

<template>
    <Head title="TODOapp - Edit a task" />

    <Navbar />

    <div class="dark-bg min-h-screen">
        <div class="bg-black bg-opacity-85 flex-grow py-8">
            <div class="container mx-auto p-4 sm:p-6 lg:p-8">
                <div class="max-w-xl lg:max-w-3xl mx-auto">
                    <div class="mb-6 mt-6">
                        <h3 class="text-3xl font-bold text-gray-200">
                            Edit Task
                        </h3>
                    </div>

                    <div class="p-6 bg-neutral-900 rounded-lg shadow-2xl mt-4 mb-2">
                        <div class="mb-3" v-if="statusMessage.message">
                            <div class="p-4 rounded" :class="{
                                'bg-green-500 text-white': statusMessage.type === 'success',
                                'bg-red-500 text-white': statusMessage.type === 'error',
                                'bg-blue-500 text-white': statusMessage.type === 'info',
                            }">
                                {{ statusMessage.message }}
                            </div>
                        </div>

                        <form @submit.prevent="submitForm" class="space-y-6">
                            <div>
                                <label for="title" class="block mb-2 text-sm font-medium text-gray-200">Task title</label>
                                <input type="text" id="title" v-model="form.title" class="form-input block w-full bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 rounded-lg" placeholder="Update app" required />
                                <p v-if="validationErrors.title" class="mt-2 text-sm text-red-500">{{ validationErrors.title }}</p>
                            </div>

                            <div>
                                <label for="description" class="block mb-2 text-sm font-medium text-gray-200">Task description</label>
                                <textarea id="description" v-model="form.description" rows="4" class="form-textarea block w-full bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 rounded-lg" placeholder="Application details..." />
                                <p v-if="validationErrors.description" class="mt-2 text-sm text-red-500">{{ validationErrors.description }}</p>
                            </div>

                            <div class="grid gap-6 md:grid-cols-2">

                                <div>
                                    <label for="deadline_date" class="block mb-2 text-sm font-medium text-gray-200">Deadline date</label>
                                    <input type="date" id="deadline_date" v-model="form.deadline_date" class="form-input block w-full bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 rounded-lg" />
                                    <p v-if="validationErrors.deadline_date" class="mt-2 text-sm text-red-500">{{ validationErrors.deadline_date }}</p>
                                </div>

                            </div>

                            <div class="flex items-center pt-2">
                                <input id="is_completed" v-model="form.is_completed" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2" />
                                <label for="is_completed" class="ms-2 text-sm font-medium text-gray-300">Is task complete?</label>
                                <p v-if="validationErrors.is_completed" class="mt-2 text-sm text-red-500">{{ validationErrors.is_completed }}</p>
                            </div>

                            <button type="submit" :disabled="form.processing" class="w-full sm:w-auto px-5 py-2.5 text-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm transition duration-150 ease-in-out disabled:opacity-50">
                                Update task
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .dark-bg {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
</style>
