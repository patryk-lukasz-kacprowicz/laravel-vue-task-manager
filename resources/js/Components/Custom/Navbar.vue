<script setup>
    import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline'
    import {router, usePage} from '@inertiajs/vue3'

    const user = usePage().props.auth.user;
    const userLoggedIn = !!user;

    const navigation = [
        { name: 'Dashboard', href: route('dashboard'), current: route().current('home'), auth: true },
        { name: 'Tasks', href: route('dashboard.tasks.index'), current: route().current('dashboard.tasks.index'), auth: true },
    ];

    const guestLinks = [
        { name: 'Home', href: route('welcome'), current: route().current('welcome') },
        { name: 'Login', href: route('login'), current: route().current('login') },
        { name: 'Register', href: route('register'), current: route().current('register') },
    ];

    const logout = () => {
        router.post(route('logout'));
    };
</script>

<template>
    <Disclosure as="nav" class="relative flex bg-neutral-900 fixed-top" v-slot="{ open }">
        <div class="mx-auto w-full px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">

                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <DisclosureButton class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-white/5 hover:text-white focus:outline-2 focus:-outline-offset-1 focus:outline-indigo-500">
                        <span class="sr-only">Menu</span>
                        <Bars3Icon v-if="!open" class="block size-6" aria-hidden="true" />
                        <XMarkIcon v-else class="block size-6" aria-hidden="true" />
                    </DisclosureButton>
                </div>

                <div class="flex flex-1 items-center justify-start sm:items-stretch sm:justify-start">

                    <div class="flex shrink-0 items-center ml-10 sm:ml-0">
                        <a href="/">
                            <img class="h-8 w-auto" src="https://i.imgur.com/zoeZC8v.png" alt="CubeStorm.pl" />
                        </a>
                    </div>

                    <div class="hidden sm:ml-6 sm:block">
                        <div class="flex space-x-4">
                            <template v-for="item in navigation" :key="item.name">
                                <template v-if="!item.auth || userLoggedIn">
                                    <a :href="item.href" :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white', 'rounded-md px-3 py-2 text-sm font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</a>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">

                    <div v-if="!userLoggedIn" class="hidden sm:flex sm:space-x-4">
                        <template v-for="item in guestLinks" :key="item.name">
                            <a :href="item.href" :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white', 'rounded-md px-3 py-2 text-sm font-medium']">{{ item.name }}</a>
                        </template>
                    </div>

                    <Menu v-else as="div" class="relative ml-3">
                        <div>
                            <MenuButton class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                                <span class="sr-only">Open user menu</span>
                                <span class="inline-block h-8 w-8 rounded-full overflow-hidden bg-gray-700 flex items-center justify-center">
                                    <span class="text-sm font-medium leading-none text-white">{{ user.name.charAt(0) }}</span>
                                </span>
                            </MenuButton>
                        </div>
                        <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform scale-100" leave-to-class="transform opacity-0 scale-95">
                            <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-neutral-900 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <MenuItem>
                                    <div class="block px-4 py-2 text-sm text-gray-400 font-bold border-b border-neutral-700 truncate">{{ user.email }}</div>
                                </MenuItem>
                                <MenuItem v-slot="{ active }">
                                    <a :href="route('profile.edit')" :class="[active ? 'bg-neutral-800' : '', 'block px-4 py-2 text-sm text-gray-400']">Your Profile</a>
                                </MenuItem>
                                <MenuItem v-slot="{ active }">
                                    <button @click="logout" :class="[active ? 'bg-neutral-800' : '', 'block w-full text-left px-4 py-2 text-sm text-red-400']">
                                        Sign out
                                    </button>
                                </MenuItem>
                            </MenuItems>
                        </transition>
                    </Menu>

                </div>

            </div>
        </div>

        <DisclosurePanel class="sm:hidden">
            <div class="space-y-1 px-2 pt-2 pb-3">
                <template v-for="item in navigation" :key="item.name">
                    <template v-if="!item.auth || userLoggedIn">
                        <DisclosureButton as="a" :href="item.href" :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white', 'block rounded-md px-3 py-2 text-base font-medium text-left']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</DisclosureButton>
                    </template>
                </template>

                <template v-if="!userLoggedIn">
                    <hr class="border-neutral-800 my-1"/>
                    <DisclosureButton v-for="item in guestLinks" :key="item.name" as="a" :href="item.href" :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white', 'block rounded-md px-3 py-2 text-base font-medium text-left']">{{ item.name }}</DisclosureButton>
                </template>

                <template v-else>
                    <hr class="border-neutral-800 my-1"/>
                    <DisclosureButton as="button" @click="logout" class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-red-400 hover:bg-white/5">
                        Sign out ({{ user.name }})
                    </DisclosureButton>
                </template>
            </div>
        </DisclosurePanel>
    </Disclosure>
</template>

<style scoped>

</style>
