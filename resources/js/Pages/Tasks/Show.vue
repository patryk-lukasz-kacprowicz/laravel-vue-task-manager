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

    <div class="dark-bg min-h-screen">
        <div class="bg-black bg-opacity-85 flex-grow py-8">
            <div class="container mx-auto p-4 sm:p-6 lg:p-8">
                <div class="max-w-4xl mx-auto">
                    <div class="mb-6 mt-6 flex flex-col sm:flex-row sm:justify-between sm:items-center">
                        <h3 class="text-3xl font-bold text-gray-200 mb-4 sm:mb-0">
                            Task Details
                        </h3>
                        <div class="w-full sm:w-auto">
                            <button
                                @click="updateTaskStatus"
                                :class="{
                                    'bg-red-700 hover:bg-red-800 focus:ring-red-300': task.is_completed,
                                    'bg-blue-600 hover:bg-blue-700 focus:ring-blue-300': !task.is_completed
                                }"
                                class="p-2 text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center transition duration-150 ease-in-out">
                                {{ task.is_completed ? 'Mark as Uncompleted' : 'Mark as Completed' }}
                            </button>
                        </div>
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

                        <div class="space-y-6">
                            <div class="border-b border-neutral-700 pb-4">
                                <h4 class="text-lg font-semibold text-gray-400 uppercase tracking-wider mb-1">Title</h4>
                                <p class="text-2xl font-bold text-gray-50">{{ task.title }}</p>
                            </div>

                            <div class="border-b border-neutral-700 pb-4">
                                <h4 class="text-lg font-semibold text-gray-400 uppercase tracking-wider mb-1">Description</h4>
                                <p class="text-gray-200 whitespace-pre-wrap">{{ task.description }}</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2 border-b border-neutral-700 pb-4">

                                <div>
                                    <h4 class="text-sm font-semibold text-gray-400 uppercase mb-1">Deadline Date</h4>
                                    <p class="text-base text-gray-200 font-medium">{{ task.deadline_date || 'N/A' }}</p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-semibold text-gray-400 uppercase mb-1">Status</h4>
                                    <span
                                        class="py-1 px-3 rounded-full text-sm font-bold text-white transition duration-150"
                                        :class="{ 'bg-green-600' : task.is_completed, 'bg-yellow-600' : !task.is_completed }">
                                        {{ task.is_completed ? 'Completed' : 'Pending' }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-500 pt-2">
                                <p><strong>Created:</strong> {{ task.created_at || 'N/A' }}</p>
                                <p><strong>Completed On:</strong> {{ task.completion_date || 'N/A' }}</p>
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
