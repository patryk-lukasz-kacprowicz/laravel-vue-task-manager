<script setup>
import {Head} from "@inertiajs/vue3";
import {ref} from "vue";
import Navbar from "@/Components/Custom/Navbar.vue";

const props = defineProps({
    task: {
        type: Object,
        required: true,
        default: {},
    }
})

const validationErrors = ref({});
const statusMessage = ref({ message: '', type: '' });
const task = props.task.data

const updateTaskStatus = async () => {

    try {
        const payload = {
            is_completed: !task.is_completed
        }

        const response = await axios.patch(route('tasks.update', task.id), payload);

        statusMessage.value = {
            message: response.data.message || 'Task status successfully updated!',
            type: 'success'
        }

        window.location.reload()
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
    <Head title="TODOapp - Show task details" />

    <Navbar />

    <div class="dark-bg">
        <div class="bg-black bg-opacity-85 flex-grow">
            <div class="container mx-auto p-4">
                <div class="mb-6 mt-6 content-between">
                    <h3 class="text-3xl font-bold text-gray-200">
                        Show task details
                    </h3>
                    <div class="mt-1">
                        <button
                            @click="updateTaskStatus"
                            class="p-2 text-white bg-blue-800 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ task.is_completed ? 'Make uncompleted' : 'Make completed' }}</button>
                    </div>
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
                        <div class="mt-3">
                            <h3 class="text-3xl font-bold text-gray-200">Title</h3>
                            <div class="mt-1">
                                <p class="text-gray-100">{{ task.title }}</p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <h3 class="text-3xl font-bold text-gray-200">Description</h3>
                            <div class="mt-1">
                                <p class="text-gray-100">{{ task.description }}</p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <h3 class="text-3xl font-bold text-gray-200">Is completed?</h3>
                            <div class="mt-1">
                                <span
                                    class="p-1 bg-blue-600 rounded-lg text-gray-100" :class="{ 'bg-green-500' : task.is_completed, 'bg-red-500' : !task.is_completed }">
                                        {{ task.is_completed ? 'Completed' : 'Uncompleted' }}
                                </span>
                            </div>
                        </div>
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
