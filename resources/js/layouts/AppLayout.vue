<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { Moon, Sun } from 'lucide-vue-next';

const { updateAppearance, appearance } = useAppearance();

const toggleAppereance = () => {
    const newAppearance = appearance.value === 'dark' ? 'light' : 'dark';
    updateAppearance(newAppearance);
};

</script>

<template>
    <nav class="border-gray-200 bg-white dark:bg-gray-900">
        <div class="mx-auto flex max-w-screen-xl flex-wrap items-center justify-between p-4">
            <a href="https://flowbite.com" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Flowbite</span>
            </a>
            <div class="flex items-center space-x-6 rtl:space-x-reverse">
                <div class="relative">
                    <label class="inline-flex cursor-pointer items-center">
                        <input
                            @change="toggleAppereance"
                            type="checkbox"
                            :checked="appearance === 'light' ? false : true"
                            class="peer sr-only"
                        />
                        <div
                            class="peer relative h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-blue-600 peer-focus:ring-4 peer-focus:ring-blue-300 peer-focus:outline-none after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white rtl:peer-checked:after:-translate-x-full dark:border-gray-600 dark:bg-gray-700 dark:peer-checked:bg-blue-600 dark:peer-focus:ring-blue-800"
                        >
                            <Moon fill="text-gray-900" class="absolute right-[3px] bottom-[4px] z-10 h-4 w-4 text-gray-900 opacity-0 dark:opacity-100" />
                            <Sun fill="text-gray-900" class="absolute bottom-[3.5px] left-[3.5px] z-10 h-4 w-4 text-gray-900 opacity-100 dark:opacity-0" />
                        </div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"></span>
                    </label>
                </div>
                <a href="#" class="text-sm text-blue-600 hover:underline dark:text-blue-500">Login</a>
            </div>
        </div>
    </nav>
    <nav class="bg-gray-50 dark:bg-gray-700">
        <div class="mx-auto max-w-screen-xl px-4 py-3">
            <div class="flex items-center">
                <ul class="mt-0 flex flex-row space-x-8 text-sm font-medium rtl:space-x-reverse">
                    <li>
                        <a href="#" class="text-gray-900 hover:underline dark:text-white" aria-current="page">Home</a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-900 hover:underline dark:text-white">Company</a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-900 hover:underline dark:text-white">Team</a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-900 hover:underline dark:text-white">Features</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="app">
        <slot />
    </main>
</template>
