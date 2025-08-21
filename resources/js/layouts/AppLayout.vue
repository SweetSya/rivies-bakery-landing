<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { useCart } from '@/composables/useCart';
import { Link, usePage } from '@inertiajs/vue3';
import { initFlowbite } from 'flowbite';
import { DoorOpen, Menu, Moon, ShoppingBag, ShoppingCart, Sun, User, X } from 'lucide-vue-next';
import { darkTheme, lightTheme, Notification, Notivue } from 'notivue';
import SimpleParallax from 'simple-parallax-js/vanilla';
import { onMounted, ref, watch } from 'vue';

const { updateAppearance, appearance } = useAppearance();
const page = usePage();
const { getCartTotalItem, initializeCart } = useCart();

const toggleAppereance = () => {
    const newAppearance = appearance.value === 'dark' ? 'light' : 'dark';
    updateAppearance(newAppearance);
};
// Reset body overflow when page changes (after drawer navigation)
watch(
    () => page.url,
    () => {
        if (document.body.classList.contains('overflow-hidden')) {
            document.body.classList.remove('overflow-hidden');
        }
    },
    { immediate: true },
);

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
    initializeCart();
    initFlowbite();
});
</script>

<template>
    <slot name="pageHead" />
    <nav class="border-gray-200 bg-gradient-to-r from-primary-600 to-primary-400 dark:border-gray-700 dark:bg-gray-800">
        <div class="mx-auto flex max-w-screen-xl flex-wrap items-center justify-between px-4 py-1">
            <div class="relative mx-5.5 font-bold text-background">Rivies Bakery</div>
            <div class="flex items-center gap-3">
                <div class="relative flex items-center justify-center">
                    <label class="inline-flex cursor-pointer items-center">
                        <input @change="toggleAppereance" type="checkbox" :checked="appearance === 'light' ? false : true" class="peer sr-only" />
                        <div
                            class="peer relative h-6 w-11 rounded-full bg-background peer-focus:outline-none after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-base-900 after:transition-all after:content-[''] peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full"
                        >
                            <Moon
                                fill="currentColor"
                                class="absolute right-[3px] bottom-[4px] z-10 h-4 w-4 text-foreground opacity-0 dark:opacity-100"
                            />
                            <Sun
                                fill="currentColor"
                                class="absolute bottom-[4px] left-[4px] z-10 h-4 w-4 text-background opacity-100 dark:opacity-0"
                            />
                        </div>
                        <span class="ms-3 text-sm font-medium text-background"></span>
                    </label>
                </div>
            </div>
        </div>
    </nav>
    <nav class="sticky top-0 z-20 mx-auto hidden border-b border-foreground/20 bg-background md:flex">
        <div class="mx-auto w-full max-w-screen-xl px-4">
            <div class="flex items-center justify-between">
                <ul class="mt-0 flex flex-row text-sm font-medium rtl:space-x-reverse">
                    <li class="group/nav-link cursor-pointer border-b-2 border-transparent px-2 py-3 hover:border-foreground">
                        <Link href="/" class="px-4 py-4 text-foreground">Beranda</Link>
                    </li>
                    <li class="group/nav-link cursor-pointer border-b-2 border-transparent px-2 py-3 hover:border-foreground">
                        <Link href="/products" class="px-4 py-4 text-foreground">Produk</Link>
                    </li>
                    <li class="group/nav-link cursor-pointer border-b-2 border-transparent px-2 py-3 hover:border-foreground">
                        <Link href="/about" class="px-4 py-4 text-foreground">Tentang</Link>
                    </li>
                    <li class="group/nav-link cursor-pointer border-b-2 border-transparent px-2 py-3 hover:border-foreground">
                        <Link href="/gallery" class="px-4 py-4 text-foreground">Galeri</Link>
                    </li>
                    <li class="group/nav-link cursor-pointer border-b-2 border-transparent px-2 py-3 hover:border-foreground">
                        <Link href="/contact" class="px-4 py-4 text-foreground">Kontak</Link>
                    </li>
                </ul>
                <div class="flex gap-2">
                    <Link
                        href="/cart"
                        type="button"
                        class="relative inline-flex cursor-pointer items-center rounded-lg border border-border bg-background p-2 text-center text-xs font-medium text-foreground hover:bg-muted hover:opacity-80 focus:ring-4 focus:ring-ring/20 focus:outline-none"
                    >
                        <ShoppingBag class="me-2 h-4 w-4" /><span class="font-bold">{{ getCartTotalItem() }}</span>
                    </Link>
                    <Link
                        href="/login"
                        class="inline-flex items-center rounded-lg border border-border bg-background px-5 py-2 text-center text-xs font-medium text-foreground hover:bg-muted hover:opacity-80 focus:ring-4 focus:ring-ring/20 focus:outline-none"
                    >
                        Login <DoorOpen class="ms-2 h-4 w-4" />
                    </Link>
                    <Link
                        href="/account-settings"
                        class="inline-flex items-center rounded-lg border border-border bg-background px-5 py-2 text-center text-xs font-medium text-foreground hover:bg-muted hover:opacity-80 focus:ring-4 focus:ring-ring/20 focus:outline-none"
                    >
                        <p class="overflow-hidden text-nowrap text-ellipsis max-w-24 whitespace-nowrap">Sultan Hakim Herrysan</p>
                        <User class="ms-2 h-4 w-4" />
                    </Link>
                </div>
            </div>
        </div>
    </nav>
    <nav class="sticky top-0 z-10 mx-auto flex justify-end bg-background shadow-md md:hidden">
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
    <!-- Bottom Navigation for Phone -->

    <div class="fixed bottom-0 left-0 z-20 block h-16 w-full rounded-t-xl border-t border-foreground/20 bg-background/80 backdrop-blur-lg md:hidden">
        <div class="mx-auto grid h-full max-w-lg grid-cols-3 font-medium">
            <Link
                href="/cart"
                type="button"
                class="group inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800"
            >
                <div class="relative">
                    <ShoppingBag class="mb-2 h-5 w-5 text-base-500 group-hover:text-primary-600" />
                    <div class="absolute -top-1 -right-2 text-xs font-light text-primary-700">{{ getCartTotalItem() }}</div>
                </div>
                <span class="text-sm text-base-500 group-hover:text-primary-600 dark:text-gray-400 dark:group-hover:text-primary-500">Keranjang</span>
            </Link>
            <Link
                href="/products"
                type="button"
                class="group inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800"
            >
                <ShoppingCart class="mb-2 h-5 w-5 text-base-500 group-hover:text-primary-600" />
                <span class="text-sm text-base-500 group-hover:text-primary-600 dark:text-gray-400 dark:group-hover:text-primary-500">Produk</span>
            </Link>
            <Link
                href="/account-settings"
                type="button"
                class="group inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800"
            >
                <img class="mb-2 h-5 w-5 rounded-full border border-foreground/20" src="/storage/images/logo.png" alt="" />
                <span
                    class="w-16 overflow-hidden text-sm text-nowrap text-ellipsis whitespace-nowrap text-base-500 group-hover:text-primary-600 xs:w-24 dark:text-gray-400 dark:group-hover:text-primary-500"
                    >Sultan Hakim Herrysan</span
                >
            </Link>
            <!-- <Link
                href="login"
                type="button"
                class="group inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800"
            >
                <UserCircle class="mb-2 h-5 w-5 text-base-500 group-hover:text-primary-600" />
                <span class="text-sm text-base-500 group-hover:text-primary-600 dark:text-gray-400 dark:group-hover:text-primary-500">Login</span>
            </Link> -->
        </div>
    </div>

    <!-- Navigation drawer components -->
    <div
        id="drawer-navigation"
        class="fixed top-0 right-0 z-[60] h-screen w-80 translate-x-full overflow-y-auto border-l border-foreground/20 bg-background/50 p-4 backdrop-blur-lg transition-transform duration-500"
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
                    <Link href="/products" class="group flex items-center rounded-lg p-2 text-foreground hover:bg-foreground/10">
                        <span class="ms-3">Produk</span>
                    </Link>
                </li>
                <li>
                    <Link href="/about" class="group flex items-center rounded-lg p-2 text-foreground hover:bg-foreground/10">
                        <span class="ms-3">Tentang</span>
                    </Link>
                </li>
                <li>
                    <Link href="/gallery" class="group flex items-center rounded-lg p-2 text-foreground hover:bg-foreground/10">
                        <span class="ms-3">Galeri</span>
                    </Link>
                </li>
                <li>
                    <Link href="/contact" class="group flex items-center rounded-lg p-2 text-foreground hover:bg-foreground/10">
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
    <footer class="border-t border-base-500/50 p-4 md:p-8 lg:p-10">
        <div class="mx-auto max-w-screen-xl text-center">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400"
                >© 2025 <a href="#" class="hover:underline">Rivies Bakery</a>. All Rights Reserved.</span
            >
        </div>
    </footer>
    <Notivue v-slot="item">
        <Notification :item="item" :theme="appearance === 'dark' ? darkTheme : lightTheme"> </Notification>
    </Notivue>
</template>
