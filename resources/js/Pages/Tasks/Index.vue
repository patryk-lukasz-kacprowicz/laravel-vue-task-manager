<script setup>
    import {Head, router} from "@inertiajs/vue3";
    import {ref} from "vue";

    const props = defineProps({
        tasks: {
            type: Array,
            required: true,
            default: () => [],
        },
    })
    const tasks = props.tasks.data

    const truncateText = (text, maxLength) => {
        if (text && text.length > maxLength) {
            return text.substring(0, maxLength) + '...';
        }
        return text;
    }

    const svgIcons = {
        check: `<svg class="w-5 h-5 inline-block text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`,
        times: `<svg class="w-5 h-5 inline-block text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`
    }

    const renderIcon = (value) => {
        if (value === true) {
            return svgIcons.check
        }

        return svgIcons.times
    }

    const statusMessage = ref({ message: '', type: '' });

    const deleteTask = async (task) => {
        try {
            const response = await axios.delete(route('tasks.update', task.id))

            statusMessage.value = {
                message: response.data.message || 'Task successfully deleted!',
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
    <Head title="TODOapp - List" />

    <div class="dark-bg">
        <div class="bg-black bg-opacity-85 flex-grow">
            <div class="container mx-auto p-4">
                <div class="mb-6 mt-6">
                    <h3 class="text-3xl font-bold text-gray-200">
                        TODO list
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
                        <table class="w-full text-sm text-left test-gray-400">
                            <thead class="text-xs text-gray-200 uppercase bg-neutral-900">
                            <tr>
                                <th scope="col" class="py-3 px-6 rounded-tl-lg">#</th>
                                <th scope="col" class="py-3 px-6 text-center">Completed?</th>
                                <th scope="col" class="py-3 px-6 text-center">Title</th>
                                <th scope="col" class="py-3 px-6 text-center">Short description</th>
                                <th scope="col" class="py-3 px-6 text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="task in tasks" class="bg-neutral-800 border-b text-gray-200 border-neutral-800">
                                <td class="py-4 px-6 font-medium text-white whitespace-nowrap">{{ task.id }}</td>
                                <td class="py-4 px-6 text-center" v-html="renderIcon(task.is_completed)"></td>
                                <td class="py-4 px-6 text-center">{{ task.title }}</td>
                                <td class="py-4 px-6 text-center">{{ truncateText(task.description, 50) }}</td>
                                <td class="py-4 px-6 text-center space-x-2">
                                    <a :href="route('tasks.show', task.id)" class="bg-blue-600 p-2 rounded text-gray-200 font-bold hover:bg-blue-700 transition">View</a>
                                    <a :href="route('tasks.edit', task.id)" class="bg-orange-600 p-2 rounded text-gray-200 font-bold hover:bg-orange-700 transition">Edit</a>
                                    <button @click="deleteTask(task)" class="bg-red-800 p-2 rounded text-gray-200 font-bold hover:bg-red-900 transition">Delete</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
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
