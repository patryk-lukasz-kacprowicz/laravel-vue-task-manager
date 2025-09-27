<script setup>
    import {Head, router} from "@inertiajs/vue3";
    import {computed, ref} from "vue";
    import Navbar from "@/Components/Custom/Navbar.vue";
    import { priorityToString } from "@/utilities/taskHelper.js";

    const props = defineProps({
        tasks: {
            type: Object,
            required: true,
            default: () => ({ data: [] }),
        },
        filters: {
            type: Object,
            required: false,
            default: () => ({})
        }
    })

    const tasksData = computed(() => props.tasks.data || []);

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
            const response = await axios.delete(route('dashboard.tasks.destroy', task.id))

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

    const filters = ref({
        status: props.filters.status || '',
        priority: props.filters.priority || ''
    })

    const sorting = ref({
        column: props.filters.sort_column || 'created_at',
        direction: props.filters.sort_direction || 'desc',
    })

    const applyFilters = () => {
        const params = {
            status: filters.value.status,
            priority: filters.value.priority,
            sort_column: sorting.value.column,
            sort_direction: sorting.value.direction,
        }

        Object.keys(params).forEach(key => {
            if (params[key] === '' || params[key] === null || params[key] === undefined) {
                delete params[key];
            }
        });

        router.get(route('dashboard.tasks.index'), params, {
            preserveState: true,
            replace: true,
        });
    }

    const applySort = () => {
        applyFilters()
    }

    const resetFilters = () => {
        filters.value.status = ''
        filters.value.priority = ''

        sorting.value.column = 'created_at'
        sorting.value.direction = 'desc'

        router.get(route('dashboard.tasks.index'), {}, {
            preserveState: true,
            replace: true,
        })
    }
</script>

<template>
    <Head title="TODOapp - List" />

    <Navbar />

    <div class="dark-bg min-h-screen">
        <div class="bg-black bg-opacity-85 flex-grow">
            <div class="container mx-auto p-4 sm:p-6 lg:p-8">
                <div class="mb-6 mt-6 flex flex-col sm:flex-row sm:justify-between sm:items-center">
                    <h3 class="text-3xl font-bold text-gray-200 mb-4 sm:mb-0">
                        TODO list
                    </h3>
                    <div>
                        <a
                            :href="route('dashboard.tasks.create')"
                            class="p-2 text-white bg-blue-800 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition duration-150 ease-in-out">
                            Create task
                        </a>
                    </div>
                </div>

                <div class="mb-3" v-if="statusMessage.message">
                    <div v-if="statusMessage.message" class="mt-4 p-4 rounded" :class="{
                        'bg-green-500 text-white': statusMessage.type === 'success',
                        'bg-red-500 text-white': statusMessage.type === 'error',
                        'bg-blue-500 text-white': statusMessage.type === 'info',
                    }">
                        {{ statusMessage.message }}
                    </div>
                </div>

                <div class="p-4 bg-neutral-900 rounded-lg shadow-md mt-4 mb-6">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4 md:gap-6">

                        <div>
                            <label for="filter-status" class="block mb-2 text-sm font-medium text-gray-400">Filter by Status</label>
                            <select id="filter-status" v-model="filters.status" @change="applyFilters"
                                    class="w-full bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                <option value="">All</option>
                                <option value="0">Pending</option>
                                <option value="1">Completed</option>
                            </select>
                        </div>

                        <div>
                            <label for="filter-priority" class="block mb-2 text-sm font-medium text-gray-400">Filter by Priority</label>
                            <select id="filter-priority" v-model="filters.priority" @change="applyFilters"
                                    class="w-full bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                <option value="">All</option>
                                <option value="1">{{ priorityToString(1) }}</option>
                                <option value="2">{{ priorityToString(2) }}</option>
                                <option value="3">{{ priorityToString(3) }}</option>
                            </select>
                        </div>

                        <div>
                            <label for="sort-by" class="block mb-2 text-sm font-medium text-gray-400">Sort By</label>
                            <select id="sort-by" v-model="sorting.column" @change="applySort"
                                    class="w-full bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                <option value="created_at">Creation Date</option>
                                <option value="deadline_date">Deadline Date</option>
                                <option value="priority">Priority</option>
                                <option value="title">Title</option>
                            </select>
                        </div>

                        <div>
                            <label for="sort-direction" class="block mb-2 text-sm font-medium text-gray-400">Order</label>
                            <select id="sort-direction" v-model="sorting.direction" @change="applySort"
                                    class="w-full bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                                <option value="asc">Ascending (A-Z, Oldest)</option>
                                <option value="desc">Descending (Z-A, Newest)</option>
                            </select>
                        </div>

                    </div>

                    <div class="mt-4 flex justify-end">
                        <button @click="resetFilters" class="text-sm text-gray-400 hover:text-white transition">Reset Filters</button>
                    </div>
                </div>

                <div class="p-4 bg-neutral-900 rounded-lg shadow-md mt-4 mb-2 hidden md:block overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-200 uppercase bg-neutral-900 border-b border-neutral-800">
                        <tr>
                            <th scope="col" class="py-3 px-6">#</th>
                            <th scope="col" class="py-3 px-6 text-center">Completed?</th>
                            <th scope="col" class="py-3 px-6 text-center">Priority</th>
                            <th scope="col" class="py-3 px-6">Title</th>
                            <th scope="col" class="py-3 px-6">Short description</th>
                            <th scope="col" class="py-3 px-6 text-center">Deadline date</th>
                            <th scope="col" class="py-3 px-6 text-center">Completion date</th>
                            <th scope="col" class="py-3 px-6 text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="task in tasksData" :key="task.id" class="bg-neutral-800 border-b text-gray-200 border-neutral-800 hover:bg-neutral-700 transition duration-150">
                            <td class="py-4 px-6 font-medium text-white whitespace-nowrap">{{ task.id }}</td>
                            <td class="py-4 px-6 text-center" v-html="renderIcon(task.is_completed)"></td>
                            <td class="py-4 px-6 text-center">
                                <span class="p-1 rounded-lg uppercase font-bold" :class="{
                                    'bg-red-500' : task.priority === 1,
                                    'bg-orange-500' : task.priority === 2,
                                    'bg-green-500' : task.priority > 2
                                }">{{ priorityToString(task.priority) }}</span>
                            </td>
                            <td class="py-4 px-6">{{ task.title }}</td>
                            <td class="py-4 px-6">{{ truncateText(task.description, 50) }}</td>
                            <td class="py-4 px-6 text-center">{{ task.deadline_date }}</td>
                            <td class="py-4 px-6 text-center">{{ task.completion_date }}</td>
                            <td class="py-4 px-6 text-center space-x-2 whitespace-nowrap">
                                <a :href="route('dashboard.tasks.show', task.id)" class="bg-blue-600 p-2 rounded text-gray-200 font-bold hover:bg-blue-700 transition">View</a>
                                <a :href="route('dashboard.tasks.edit', task.id)" class="bg-orange-600 p-2 rounded text-gray-200 font-bold hover:bg-orange-700 transition">Edit</a>
                                <button @click="deleteTask(task)" class="bg-red-800 p-2 rounded text-gray-200 font-bold hover:bg-red-900 transition">Delete</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden space-y-4 mt-4 mb-2">
                    <div v-for="task in tasksData" :key="task.id" class="bg-neutral-800 rounded-lg shadow-xl p-4 border-l-4" :class="task.is_completed ? 'border-green-500' : 'border-red-500'">
                        <div class="flex justify-between items-start border-b border-neutral-700 pb-2 mb-2">
                            <h4 class="text-xl font-semibold text-white truncate pr-2">{{ task.title }}</h4>
                            <span class="text-sm font-medium text-gray-400">#{{ task.id }}</span>
                        </div>
                        <p class="text-sm text-gray-300 mb-3">{{ truncateText(task.description, 100) }}</p>

                        <div class="space-y-1 text-sm text-gray-400">
                            <p><strong>Status:</strong> <span class="font-medium text-white" v-html="renderIcon(task.is_completed)"></span></p>
                            <p v-if="task.deadline_date"><strong>Deadline:</strong> {{ task.deadline_date }}</p>
                            <p v-if="task.completion_date"><strong>Completed:</strong> {{ task.completion_date }}</p>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2 justify-center">
                            <a :href="route('dashboard.tasks.show', task.id)" class="flex-1 min-w-[80px] text-center bg-blue-600 py-2 px-3 rounded text-sm text-gray-200 font-bold hover:bg-blue-700 transition">View</a>
                            <a :href="route('dashboard.tasks.edit', task.id)" class="flex-1 min-w-[80px] text-center bg-orange-600 py-2 px-3 rounded text-sm text-gray-200 font-bold hover:bg-orange-700 transition">Edit</a>
                            <button @click="deleteTask(task)" class="flex-1 min-w-[80px] text-center bg-red-800 py-2 px-3 rounded text-sm text-gray-200 font-bold hover:bg-red-900 transition">Delete</button>
                        </div>
                    </div>

                    <div v-if="tasksData.length === 0" class="text-center p-4 text-gray-400">Brak zadań do wyświetlenia.</div>
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
