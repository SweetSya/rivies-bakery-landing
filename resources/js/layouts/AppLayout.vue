<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { Link } from '@inertiajs/vue3';
import { DoorOpen, Menu, Moon, Sun, X } from 'lucide-vue-next';
import SimpleParallax from 'simple-parallax-js/vanilla';
import { onMounted, ref } from 'vue';

const { updateAppearance, appearance } = useAppearance();

const toggleAppereance = () => {
    const newAppearance = appearance.value === 'dark' ? 'light' : 'dark';
    updateAppearance(newAppearance);
};
const parallaxElement = ref<Element | null>(null);
onMounted(() => {
    parallaxElement.value = document.querySelector('.page-background img');
    if (parallaxElement.value) {
        new SimpleParallax(parallaxElement.value, {
            delay: 0.5,
            transition: 'cubic-bezier(0,0,0,1)',
            scale: 1.4,
            overflow: true,
        });
    }
});
</script>

<template>
    <nav class="border-gray-200 bg-gradient-to-r from-primary-400 to-primary-600 dark:border-gray-700 dark:bg-gray-800">
        <div class="mx-auto flex max-w-screen-xl flex-wrap items-center justify-between px-4 py-1">
            <div class="relative -top-8 z-50">
                <Link href="/" class="absolute top-0 flex h-36 w-36 items-center justify-center">
                    <img src="/storage/images/logo.png" class="h-24 w-24 rounded-full border-4 border-primary-500" alt="Logo" />
                </Link>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative flex items-center justify-center">
                    <label class="inline-flex cursor-pointer items-center">
                        <input @change="toggleAppereance" type="checkbox" :checked="appearance === 'light' ? false : true" class="peer sr-only" />
                        <div
                            class="peer relative h-6 w-11 rounded-full bg-base-50 peer-focus:outline-none after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-base-900 after:transition-all after:content-[''] peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full"
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
                    Rivies <DoorOpen class="ms-2 h-4 w-4" />
                </Link>
            </div>
        </div>
    </nav>
    <nav class="sticky top-0 z-20 mx-auto hidden border-b border-foreground/20 bg-background lg:flex">
        <div class="mx-auto px-4">
            <div class="flex items-center">
                <ul class="mt-0 flex flex-row text-sm font-medium rtl:space-x-reverse">
                    <li class="group/nav-link cursor-pointer border-b-2 border-transparent px-4 py-4 hover:border-foreground">
                        <Link href="#" class="text-foreground">Produk</Link>
                    </li>
                    <li class="group/nav-link cursor-pointer border-b-2 border-transparent px-4 py-4 hover:border-foreground">
                        <Link href="#" class="text-foreground">Tentang</Link>
                    </li>
                    <li class="group/nav-link cursor-pointer border-b-2 border-transparent px-4 py-4 hover:border-foreground">
                        <Link href="#" class="text-foreground">Galeri</Link>
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
                        <span class="ms-3">Produk</span>
                    </Link>
                </li>
                <li>
                    <Link href="#" class="group flex items-center rounded-lg p-2 text-foreground hover:bg-base-100">
                        <span class="ms-3">Tentang</span>
                    </Link>
                </li>
                <li>
                    <Link href="#" class="group flex items-center rounded-lg p-2 text-foreground hover:bg-base-100">
                        <span class="ms-3">Galeri</span>
                    </Link>
                </li>
                <li>
                    <Link href="#" class="group flex items-center rounded-lg p-2 text-foreground hover:bg-base-100">
                        <span class="ms-3">Kontak</span>
                    </Link>
                </li>
            </ul>
        </div>
    </div>

    <main class="app overflow-x-hidden">
        <div class="-mt-3 h-[70vh] max-h-[600px] w-full">
            <div class="h-full w-full">
                <div class="relative h-full w-full">
                    <div class="page-background">
                        <slot name="pageBackground" />
                    </div>
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
            <div class="mx-auto min-h-80 max-w-screen-xl">
                <slot name="content" />
            </div>
        </div>
    </main>
</template>
