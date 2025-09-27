<script setup>
import {Head, router} from '@inertiajs/vue3';
    import Navbar from "@/Components/Custom/Navbar.vue";
    import { priorityToString } from "@/utilities/taskHelper.js";

    const props = defineProps({
        tasksToday: {
            type: Array,
            required: true,
            default: []
        }
    })

    const tasks = props.tasksToday.data

    const redirectToTaskShow = (task) => {
        router.get(route('dashboard.tasks.show', task.id))
    }
</script>

<template>
    <Head title="TODOapp - Dashboard" />

    <Navbar />

    <div class="dark-bg min-h-screen">
        <div class="bg-black bg-opacity-85 flex-grow py-8">
            <div class="container mx-auto p-4 sm:p-6 lg:p-8">
                <div class="mb-8 mt-6">
                    <h3 class="text-3xl font-bold text-gray-200">
                        Dashboard
                    </h3>
                    <p class="text-gray-400 mt-1">Review of your progress and tasks for today.</p>
                </div>

                <section class="mb-10">
                    <h4 class="text-xl font-semibold text-gray-300 mb-4">Statistics</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-neutral-900 p-6 rounded-lg shadow-lg h-48 flex items-center justify-center">
                            <p class="text-lg text-gray-500 font-medium">Placeholder: Wykres Postępu</p>
                        </div>

                        <div class="bg-neutral-900 p-6 rounded-lg shadow-lg h-48 flex items-center justify-center">
                            <p class="text-lg text-gray-500 font-medium">Placeholder: Wykres Priorytetów</p>
                        </div>

                        <div class="bg-neutral-900 p-6 rounded-lg shadow-lg h-48 flex items-center justify-center">
                            <p class="text-lg text-gray-500 font-medium">Placeholder: Wykres Terminów</p>
                        </div>
                    </div>
                </section>

                <section>
                    <h4 class="text-xl font-semibold text-gray-300 mb-4">Tasks to be completed today</h4>

                    <div class="grid grid-cols-1 gap-6">
                        <div class="bg-neutral-900 p-6 rounded-lg shadow-lg min-h-64">
                            <ul class="space-y-3">
                                <li @click="redirectToTaskShow(task)" v-for="task in tasks" class="cursor-pointer p-3 bg-neutral-800 rounded-md border-l-4 flex justify-between items-center" :class="{
                                    'border-red-500' : task.priority === 1,
                                    'border-orange-500' : task.priority === 2,
                                    'border-green-500' : task.priority === 3
                                }">
                                    <span class="text-gray-100">{{ task.title }}</span>
                                    <span class="p-1 rounded-lg uppercase font-bold text-gray-100" :class="{
                                        'bg-red-500' : task.priority === 1,
                                        'bg-orange-500' : task.priority === 2,
                                        'bg-green-500' : task.priority === 3
                                    }">{{ priorityToString(task.priority) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>
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
