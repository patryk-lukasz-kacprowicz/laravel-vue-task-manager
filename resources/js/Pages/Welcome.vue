<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="TODOapp - Zarządzaj zadaniami z łatwością" />

    <Navbar />

    <div class="bg-neutral-900 min-h-screen text-white">

        <section class="container mx-auto px-4 py-24 text-center">

            <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight mb-4">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-green-400">
                    Osiągaj swoje cele. Z dnia na dzień.
                </span>
            </h1>

            <p class="text-xl text-gray-400 max-w-3xl mx-auto mb-10">
                TODOapp to najprostsze narzędzie do zarządzania listą zadań. Zaplanuj, priorytetyzuj i zrealizuj wszystko, co masz na głowie.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a
                    :href="route('register')"
                    class="px-8 py-3 text-lg font-semibold rounded-lg shadow-lg bg-blue-600 hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-4 focus:ring-blue-500/50">
                    Rozpocznij za Darmo
                </a>
                <a
                    :href="route('login')"
                    class="px-8 py-3 text-lg font-semibold rounded-lg shadow-md bg-neutral-700 hover:bg-neutral-600 transition duration-300 focus:outline-none focus:ring-4 focus:ring-neutral-500/50">
                    Logowanie
                </a>
            </div>

        </section>

        <section class="container mx-auto px-4 py-16">
            <h2 class="text-4xl font-bold text-center mb-12">
                Dlaczego TODOapp?
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <div class="bg-neutral-800 p-6 rounded-xl shadow-lg border-t-4 border-red-500 hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
                    <div class="text-4xl text-red-500 mb-3">🔥</div>
                    <h3 class="text-xl font-bold mb-3">Priorytetyzacja (High/Medium/Low)</h3>
                    <p class="text-gray-400">Natychmiast oznaczaj najważniejsze zadania. Nie trać czasu na to, co może poczekać.</p>
                </div>

                <div class="bg-neutral-800 p-6 rounded-xl shadow-lg border-t-4 border-blue-500 hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
                    <div class="text-4xl text-blue-500 mb-3">📅</div>
                    <h3 class="text-xl font-bold mb-3">Ustalaj Deadline'y</h3>
                    <p class="text-gray-400">Dodaj datę końcową i nigdy więcej nie przegap ważnego terminu. Wszystko w jednym miejscu.</p>
                </div>

                <div class="bg-neutral-800 p-6 rounded-xl shadow-lg border-t-4 border-green-500 hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
                    <div class="text-4xl text-green-500 mb-3">✅</div>
                    <h3 class="text-xl font-bold mb-3">Oznaczanie Ukończonych</h3>
                    <p class="text-gray-400">Z satysfakcją oznaczaj zadania jako ukończone. Przejrzysta historia Twoich osiągnięć.</p>
                </div>

                <div class="bg-neutral-800 p-6 rounded-xl shadow-lg border-t-4 border-purple-500 hover:shadow-2xl transition duration-300 transform hover:scale-[1.02] lg:col-span-1">
                    <div class="text-4xl text-purple-500 mb-3">📱</div>
                    <h3 class="text-xl font-bold mb-3">Mobilna gotowość</h3>
                    <p class="text-gray-400">Zarządzaj zadaniami z dowolnego urządzenia. Nasza aplikacja wygląda świetnie na telefonach i komputerach.</p>
                </div>

                <div class="bg-neutral-800 p-6 rounded-xl shadow-lg border-t-4 border-yellow-500 hover:shadow-2xl transition duration-300 transform hover:scale-[1.02] lg:col-span-2 md:col-span-2">
                    <div class="text-4xl text-yellow-500 mb-3">🔒</div>
                    <h3 class="text-xl font-bold mb-3">Twoje zadania są bezpieczne</h3>
                    <p class="text-gray-400">Używamy Laravel, aby zapewnić najwyższy poziom bezpieczeństwa i stabilności. Twoja lista to Twoja sprawa.</p>
                </div>

            </div>
        </section>

        <footer class="mt-20 border-t border-neutral-800 py-6 text-center text-gray-500">
            <div class="container mx-auto px-4">
                <p>&copy; {{ new Date().getFullYear() }} TODOapp | Zbudowano z 🔥 przy użyciu Laravel, VueJS i TailwindCSS.</p>
                <div class="mt-2 space-x-4">
                    <a :href="route('login')" class="hover:text-white transition">Logowanie</a>
                    <a :href="route('register')" class="hover:text-white transition">Rejestracja</a>
                </div>
            </div>
        </footer>

    </div>
</template>
