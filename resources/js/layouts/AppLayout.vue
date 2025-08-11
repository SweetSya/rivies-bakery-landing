<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { Link } from '@inertiajs/vue3';
import { ChevronDown, DoorOpen, Menu, Moon, Sun, X } from 'lucide-vue-next';

const { updateAppearance, appearance } = useAppearance();

const toggleAppereance = () => {
    const newAppearance = appearance.value === 'dark' ? 'light' : 'dark';
    updateAppearance(newAppearance);
};
</script>

<template>
    <nav class="border-gray-200 bg-gradient-to-r from-primary-500 to-secondary-400 dark:border-gray-700 dark:bg-gray-800">
        <div class="mx-auto flex max-w-screen-xl flex-wrap items-center justify-between px-4 py-1">
            <div class="relative -top-8 z-50">
                <Link
                    href="https://flowbite.com"
                    class="absolute top-0 flex h-36 w-36 items-center justify-center rounded-b-2xl bg-background shadow dark:bg-background"
                >
                    <img src="/placeholder-assets/logo.png" class="h-24 w-24" alt="Flowbite Logo" />
                </Link>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative flex items-center justify-center">
                    <label class="inline-flex cursor-pointer items-center">
                        <input @change="toggleAppereance" type="checkbox" :checked="appearance === 'light' ? false : true" class="peer sr-only" />
                        <div
                            class="peer relative h-6 w-11 rounded-full bg-base-50 peer-focus:outline-none after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-secondary-400 after:transition-all after:content-[''] peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full"
                        >
                            <Moon
                                fill="currentColor"
                                class="absolute right-[3px] bottom-[4px] z-10 h-4 w-4 text-base-50 opacity-0 dark:opacity-100"
                            />
                            <Sun fill="currentColor" class="absolute bottom-[4px] left-[4px] z-10 h-4 w-4 text-base-50 opacity-100 dark:opacity-0" />
                        </div>
                        <span class="ms-3 text-sm font-medium text-foreground"></span>
                    </label>
                </div>
                <Link
                    href="#"
                    class="inline-flex items-center rounded-lg border border-border bg-background px-5 py-2 text-center text-xs font-medium text-foreground hover:bg-muted focus:ring-4 focus:ring-ring/20 focus:outline-none"
                >
                    Sistem <DoorOpen class="ms-2 h-4 w-4" />
                </Link>
            </div>
        </div>
    </nav>
    <nav class="sticky top-0 z-20 mx-auto hidden bg-background shadow-md lg:flex">
        <div class="mx-auto px-4">
            <div class="flex items-center">
                <ul class="mt-0 flex flex-row text-sm font-medium rtl:space-x-reverse">
                    <li class="group/nav-link cursor-pointer border-b-2 border-transparent px-4 py-4 hover:border-foreground">
                        <Link href="#" class="text-foreground" aria-current="page">Beranda</Link>
                    </li>

                    <li class="group/nav-link cursor-pointer border-b-2 border-transparent px-4 py-4 hover:border-foreground">
                        <Link href="#" class="text-foreground">Profil</Link>
                    </li>
                    <li class="group/nav-link cursor-pointer border-b-2 border-transparent px-4 py-4 hover:border-foreground">
                        <Link href="#" class="text-foreground">Cabor</Link>
                    </li>
                    <li class="group/nav-link cursor-pointer border-b-2 border-transparent px-4 py-4 hover:border-foreground">
                        <Link href="#" class="text-foreground">Event</Link>
                    </li>
                    <li class="group/nav-link relative cursor-pointer border-b-2 border-transparent px-4 py-4 hover:border-foreground">
                        <Link href="#" class="flex gap-2 text-foreground group-hover/nav-link:opacity-80"
                            >Publikasi <ChevronDown class="h-5 w-5" />
                        </Link>
                        <div
                            id="dropdownHover"
                            class="absolute left-1/2 z-10 mt-4.5 w-44 -translate-x-1/2 divide-y divide-border overflow-hidden rounded-b-lg bg-card opacity-0 shadow-sm group-hover/nav-link:opacity-100"
                        >
                            <ul class="py-2 text-sm text-card-foreground" aria-labelledby="dropdownHoverButton">
                                <li>
                                    <Link href="#" class="block px-4 py-2 hover:bg-foreground/10">Fotografi</Link>
                                </li>
                                <li>
                                    <Link href="#" class="block px-4 py-2 hover:bg-foreground/10">Berita</Link>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="group/nav-link cursor-pointer border-b-2 border-transparent px-4 py-4 hover:border-foreground">
                        <Link href="#" class="text-foreground">Kontak</Link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <nav class="sticky top-0 z-20 mx-auto flex justify-end bg-background shadow-md lg:hidden">
        <div class="me-8 rounded-lg py-4 text-center">
            <Menu
                data-drawer-target="drawer-navigation"
                data-drawer-toggle="drawer-navigation"
                data-drawer-placement="right"
                aria-controls="drawer-navigation"
                class="h-8 w-8 cursor-pointer text-muted-foreground hover:text-foreground"
            />
        </div>
    </nav>
    <!-- Navigation drawer components -->
    <div
        id="drawer-navigation"
        class="fixed top-0 right-0 z-[60] h-screen w-80 translate-x-full overflow-y-auto bg-background p-4 transition-transform duration-500"
        tabindex="-1"
        aria-labelledby="drawer-navigation-label"
    >
        <button
            type="button"
            data-drawer-hide="drawer-navigation"
            aria-controls="drawer-navigation"
            class="absolute end-2.5 top-2.5 inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-muted-foreground hover:bg-muted hover:text-foreground"
        >
            <X class="h-5 w-5 font-thin" />
            <span class="ms-1 font-semibold">Tutup</span>
        </button>
        <div class="mt-4 overflow-y-auto py-4">
            <ul class="space-y-2 font-medium">
                <li>
                    <Link href="#" class="group flex items-center rounded-lg p-2 text-foreground hover:bg-base-100">
                        <span class="ms-3">Beranda</span>
                    </Link>
                </li>
                <li>
                    <Link href="#" class="group flex items-center rounded-lg p-2 text-foreground hover:bg-base-100">
                        <span class="ms-3">Profil</span>
                    </Link>
                </li>
                <li>
                    <Link href="#" class="group flex items-center rounded-lg p-2 text-foreground hover:bg-base-100">
                        <span class="ms-3">Cabor</span>
                    </Link>
                </li>
                <li>
                    <Link href="#" class="group flex items-center rounded-lg p-2 text-foreground hover:bg-base-100">
                        <span class="ms-3">Event</span>
                    </Link>
                </li>
                <li>
                    <button
                        type="button"
                        class="group flex w-full items-center rounded-lg p-2 text-base text-foreground transition duration-75 hover:bg-base-100"
                        aria-controls="dropdown-example"
                        data-collapse-toggle="dropdown-example"
                    >
                        <span class="ms-3 flex-1 text-left whitespace-nowrap rtl:text-right">Publikasi</span>
                        <ChevronDown class="h-5 w-5 flex-shrink-0 text-muted-foreground transition duration-75 group-hover:text-foreground" />
                    </button>
                    <ul id="dropdown-example" class="hidden space-y-2 py-2">
                        <li>
                            <Link
                                href="#"
                                class="group flex w-full items-center rounded-lg p-2 pl-11 text-foreground transition duration-75 hover:bg-base-100"
                                >Fotografi</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="#"
                                class="group flex w-full items-center rounded-lg p-2 pl-11 text-foreground transition duration-75 hover:bg-base-100"
                                >Berita</Link
                            >
                        </li>
                    </ul>
                </li>
                <li>
                    <Link href="#" class="group flex items-center rounded-lg p-2 text-foreground hover:bg-base-100">
                        <span class="ms-3">Kontak</span>
                    </Link>
                </li>
            </ul>
        </div>
    </div>

    <main class="app">
        <div class="-mt-3 h-[70vh] max-h-[600px] w-full">
            <div class="h-full w-full">
                <div class="relative h-full w-full">
                    <slot name="pageBackground" />
                    <div class="relative mx-auto h-full w-full max-w-screen-xl px-4">
                        <div class="absolute top-1/2 left-0 max-w-screen-xl -translate-y-1/2 px-4">
                            <nav class="mb-2 flex" aria-label="Breadcrumb">
                                <slot name="pageBreadcrumb" />
                            </nav>
                            <h1 class="mb-2 text-3xl font-extrabold text-base-50 md:text-5xl"><slot name="pageTitle" /></h1>
                            <p class="mb-6 text-lg font-normal text-base-50">
                                <slot name="pageDescription" />
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative -mt-20 rounded-t-4xl bg-background p-5">
            <div class="mx-auto max-w-screen-xl min-h-80">
                <slot name="content" />
            </div>
        </div>
    </main>
</template>
