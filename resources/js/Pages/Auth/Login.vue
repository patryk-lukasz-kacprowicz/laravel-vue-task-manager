<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Navbar from "@/Components/Custom/Navbar.vue";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="TODOapp - Logowanie" />

    <Navbar />

    <div class="dark-bg min-h-screen">
        <div class="bg-black bg-opacity-85 flex-grow py-8">
            <div class="container mx-auto p-4 sm:p-6 lg:p-8">

                <div class="mb-6 mt-6">
                    <h3 class="text-3xl font-bold text-gray-200">
                        Sign In
                    </h3>
                </div>

                <div class="max-w-xl mx-auto p-6 bg-neutral-900 rounded-lg shadow-2xl mt-4 mb-2">

                    <div v-if="status" class="mb-4 p-3 text-sm font-medium text-green-400 bg-green-900/50 rounded-lg">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">

                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-200">Email</label>

                            <input
                                id="email"
                                type="email"
                                class="form-input block w-full bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 rounded-lg"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                            />

                            <p v-if="form.errors.email" class="mt-2 text-sm text-red-500">{{ form.errors.email }}</p>
                        </div>

                        <div class="mt-4">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-200">Password</label>

                            <input
                                id="password"
                                type="password"
                                class="form-input block w-full bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 rounded-lg"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                            />

                            <p v-if="form.errors.password" class="mt-2 text-sm text-red-500">{{ form.errors.password }}</p>
                        </div>

                        <div class="block">
                            <label class="flex items-center">
                                <input name="remember" v-model="form.remember" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-2" />
                                <span class="ms-2 text-sm text-gray-300">Remember me</span>
                            </label>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center justify-end pt-4 gap-4">

                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="rounded-md text-sm text-blue-500 hover:text-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-neutral-900 transition w-full sm:w-auto text-center"
                            >
                                Forgot your password?
                            </Link>

                            <button
                                type="submit"
                                :class="{ 'opacity-50': form.processing }"
                                :disabled="form.processing"
                                class="w-full sm:w-auto px-5 py-2.5 text-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm transition duration-150 ease-in-out disabled:opacity-50"
                            >
                                Log in
                            </button>
                        </div>

                    </form>
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
