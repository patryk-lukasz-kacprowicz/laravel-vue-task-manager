<script setup>
    import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline'

    const navigation = [
        { name: 'Tasks', href: route('tasks.index'), current: false },
    ]
</script>

<template>
    <Disclosure as="nav" class="relative flex bg-neutral-900 fixed-top" v-slot="{ open }">
        <div class="mx-auto w-full px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">

                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <DisclosureButton class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-white/5 hover:text-white focus:outline-2 focus:-outline-offset-1 focus:outline-indigo-500">
                        <span class="absolute -inset-0.5" />
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
                                <a v-if="!item.children" :href="item.href" :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white', 'rounded-md px-3 py-2 text-sm font-medium']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</a>

                                <Menu v-else as="div" class="relative">
                                    <MenuButton class="flex items-center text-gray-300 hover:bg-white/5 hover:text-white rounded-md px-3 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500">
                                        {{ item.name }}
                                        <svg class="ml-2 -mr-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </MenuButton>

                                    <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform scale-100" leave-to-class="transform opacity-0 scale-95">
                                        <MenuItems class="absolute left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-neutral-900 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                            <template v-for="child in item.children" :key="child.name || 'separator'">
                                                <div v-if="child.separator" class="my-1 h-px bg-neutral-800" />
                                                <MenuItem v-else v-slot="{ active }">
                                                    <a :href="child.href" :class="[active ? 'bg-neutral-800' : '', 'block px-4 py-2 text-sm text-gray-400 transition']">{{ child.name }}</a>
                                                </MenuItem>
                                            </template>
                                        </MenuItems>
                                    </transition>
                                </Menu>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <DisclosurePanel class="sm:hidden">
            <div class="space-y-1 px-2 pt-2 pb-3">
                <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="item.href" :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white', 'block rounded-md px-3 py-2 text-base font-medium text-left']" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</DisclosureButton>
            </div>
        </DisclosurePanel>
    </Disclosure>
</template>

<style scoped>

</style>
