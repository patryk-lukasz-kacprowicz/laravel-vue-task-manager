<script setup>
import { Head } from "@inertiajs/vue3";
import { ref } from 'vue';

const validationErrors = ref({});
const statusMessage = ref({ message: '', type: '' });

const form = ref({
    is_completed: null,
    title: '',
    description: '',
});

const submitForm = async () => {

    try {
        const payload = {
            ...form.value,
            is_completed: form.value.is_completed ? true : false
        }

        const response = await axios.post(route('tasks.store'), payload)

        statusMessage.value = {
            message: response.data.message || 'Task successfully created!',
            type: 'success'
        }

        Object.keys(form.value).forEach(key => form.value[key] = null);
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
};
</script>

<template>
    <Head title="TODOapp - Create task" />

    <div class="dark-bg">
        <div class="bg-black bg-opacity-85 flex-grow">
            <div class="container mx-auto p-4">
                <div class="mb-6 mt-6">
                    <h3 class="text-3xl font-bold text-gray-200">
                        Create task
                    </h3>
                </div>
                <div class="p-6 bg-neutral-900 rounded-lg shadow-md mt-4 mb-2">
                    <div class="mb-3" v-if="statusMessage.message">
                        <div v-if="statusMessage.message" class="mt-4 p-4 rounded" :class="{
                            'bg-green-500 text-white': statusMessage.type === 'success',
                            'bg-red-500 text-white': statusMessage.type === 'error',
                            'bg-blue-500 text-white': statusMessage.type === 'info',
                        }">
                            {{ statusMessage.message }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <form @submit.prevent="submitForm">
                            <div class="grid gap-6 mb-6 md:grid-cols-1">
                                <div>
                                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Task title</label>
                                    <input type="text" id="title" v-model="form.title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Create app" required />
                                    <p v-if="validationErrors.title" class="mt-2 text-sm text-red-500">{{ validationErrors.title }}</p>
                                </div>

                                <div>
                                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Task description</label>
                                    <textarea id="description" v-model="form.description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Application must be well made!" required />
                                    <p v-if="validationErrors.description" class="mt-2 text-sm text-red-500">{{ validationErrors.description }}</p>
                                </div>

                                <div class="flex items-start mb-6">
                                    <div class="flex items-center h-5">
                                        <input id="is_completed" v-model="form.is_completed" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" />
                                    </div>
                                    <label for="is_completed" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Is task complete?</label>
                                    <p v-if="validationErrors.is_completed" class="mt-2 text-sm text-red-500">{{ validationErrors.is_completed }}</p>
                                </div>
                            </div>

                            <button type="submit" class="p-2 text-white bg-blue-800 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Create task</button>
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
