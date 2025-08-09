<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { ChevronDown, DoorOpen, Moon, Sun } from 'lucide-vue-next';

const { updateAppearance, appearance } = useAppearance();

const toggleAppereance = () => {
    const newAppearance = appearance.value === 'dark' ? 'light' : 'dark';
    updateAppearance(newAppearance);
};
</script>

<template>
    <nav class="border-gray-200 bg-gray-50 py-2 dark:bg-gray-900">
        <div class="mx-auto flex max-w-screen-xl flex-wrap items-center justify-between px-4 py-1">
            <div class="relative -top-8 z-50">
                <a
                    href="https://flowbite.com"
                    class="absolute top-0 flex h-32 w-32 items-center justify-center rounded-b-2xl bg-white shadow dark:bg-gray-900"
                >
                    <img src="/placeholder-assets/logo.png" class="h-16 w-16" alt="Flowbite Logo" />
                </a>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative flex items-center justify-center">
                    <label class="inline-flex cursor-pointer items-center">
                        <input @change="toggleAppereance" type="checkbox" :checked="appearance === 'light' ? false : true" class="peer sr-only" />
                        <div
                            class="peer relative h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-blue-600 peer-focus:ring-4 peer-focus:ring-blue-300 peer-focus:outline-none after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white rtl:peer-checked:after:-translate-x-full dark:border-gray-600 dark:bg-gray-700 dark:peer-checked:bg-blue-600 dark:peer-focus:ring-blue-800"
                        >
                            <Moon
                                fill="text-gray-900"
                                class="absolute right-[3px] bottom-[4px] z-10 h-4 w-4 text-gray-900 opacity-0 dark:opacity-100"
                            />
                            <Sun
                                fill="text-gray-900"
                                class="absolute bottom-[3.5px] left-[3.5px] z-10 h-4 w-4 text-gray-900 opacity-100 dark:opacity-0"
                            />
                        </div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"></span>
                    </label>
                </div>
                <a
                    href="#"
                    class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-5 py-2 text-center text-xs font-medium text-gray-900 hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                >
                    Connect <DoorOpen class="ms-2 h-4 w-4" />
                </a>
            </div>
        </div>
    </nav>
    <nav class="sticky top-0 z-20 mx-auto flex bg-white dark:bg-gray-800 shadow-md">
        <div class="mx-auto px-4 py-4">
            <div class="flex items-center">
                <ul class="mt-0 flex flex-row space-x-8 text-sm font-medium rtl:space-x-reverse">
                    <li class="hover:opacity-70">
                        <a href="#" class="text-gray-900 dark:text-white" aria-current="page">Home</a>
                    </li>

                    <li class="hover:opacity-70">
                        <a href="#" class="text-gray-900 dark:text-white">Profil</a>
                    </li>
                    <li class="hover:opacity-70">
                        <a href="#" class="text-gray-900 dark:text-white">Cabor</a>
                    </li>
                    <li class="hover:opacity-70">
                        <a href="#" class="text-gray-900 dark:text-white">Event</a>
                    </li>
                    <li class="hover:opacity-70">
                        <a href="#" class="text-gray-900 dark:text-white">Kontak</a>
                    </li>
                    <li class="hover:opacity-70">
                        <a
                            href="#"
                            data-popover-placement="bottom"
                            data-popover-target="popover-nav-publication"
                            class="flex gap-2 text-gray-900 dark:text-white"
                            >Publikasi <ChevronDown class="h-5 w-5" />
                        </a>
                    </li>
                </ul>
            </div>
            <div class="popovers">
                <div
                    data-popover
                    id="popover-nav-publication"
                    role="tooltip"
                    class="invisible absolute z-10 inline-block w-96 rounded-lg border border-gray-200 bg-white text-sm text-gray-500 opacity-0 shadow-xs transition-opacity duration-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400"
                >
                    <div
                        class="w-full max-w-sm rounded-lg border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-700 dark:bg-gray-800"
                    >
                        <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                            <li class="pb-3 sm:pb-4">
                                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium text-gray-900 dark:text-white">Neil Sims</p>
                                    </div>
                                </div>
                            </li>
                            <li class="py-3 sm:py-4">
                                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium text-gray-900 dark:text-white">Bonnie Green</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div data-popper-arrow></div>
                </div>
            </div>
        </div>
    </nav>

    <main class="app">
        <slot />
    </main>
</template>
