<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

// Register Library
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

defineOptions({
    components: {
        AppLayout,
    },
});
</script>

<template>
    <AppLayout>
        <template #pageHead>
            <Head>
                <title>Daftar</title>
                <meta name="description" content="Halaman daftar Rivies Bakery" />
            </Head>
        </template>

        <!-- Top Background -->
        <template #pageBackground>
            <img src="/storage/images/jumbotron/1.jpg" class="absolute h-full w-full object-cover brightness-50" />
        </template>
        <!-- Breadcrumb -->
        <template #pageBreadcrumb> </template>
        <!-- Title -->
        <template #pageTitle>Daftar sebagai <span class="rounded bg-primary-600 px-3 py-1 font-semibold">Pelanggan</span></template>
        <!-- Description -->
        <template #pageDescription>Gunakan kredensial yang dapat kamu ingat dengan baik</template>
        <!-- Content -->
        <template #content>
            <form class="mx-auto max-w-sm py-5">
                <div class="mb-5">
                    <label for="name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Nama Lengkap *</label>
                    <div>
                        <input
                            type="text"
                            id="name"
                            class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            placeholder=""
                            required
                        />
                    </div>
                </div>
                <div class="mb-5">
                    <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Email *</label>
                    <div>
                        <input
                            type="email"
                            id="email"
                            class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            placeholder=""
                            required
                        />
                    </div>
                </div>
                <div class="mb-5">
                    <label for="phone" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">No Telepon *</label>
                    <div class="relative">
                        <span class="absolute rounded-s-lg bg-primary-600 p-2.5 text-[14px] font-bold text-foreground">+62</span>
                        <input
                            type="text"
                            id="phone"
                            class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 ps-14 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            placeholder=""
                            required
                        />
                    </div>
                </div>
                <div class="mb-5">
                    <label for="password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input
                        type="password"
                        class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                        placeholder=""
                        required
                    />
                </div>
                <div class="mb-5">
                    <label for="confirm_password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Konfirmasi Password</label>
                    <input
                        type="confirm_password"
                        class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                        placeholder=""
                        required
                    />
                </div>

                <div class="mb-5 flex items-start">
                    <div class="flex h-5 items-center">
                        <input
                            id="remember"
                            type="checkbox"
                            value=""
                            class="h-4 w-4 rounded-sm border border-border bg-background checked:border-primary-500 checked:bg-primary-500 focus:ring-3 focus:ring-primary-300 dark:border-border dark:bg-background dark:ring-offset-background dark:checked:border-primary-600 dark:checked:bg-primary-600 dark:focus:ring-primary-600 dark:focus:ring-offset-background"
                            required
                        />
                    </div>
                    <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ingat perangkat</label>
                </div>
                <button
                    type="submit"
                    class="w-full cursor-pointer rounded-lg border border-primary-500 bg-primary-500 px-5 py-2 text-center text-xs font-medium text-foreground hover:bg-primary-700 focus:z-10 focus:ring-4 focus:ring-primary-200 focus:outline-none md:text-base dark:border-primary-600 dark:bg-primary-600"
                >
                    Masuk
                </button>
                <p class="mt-6 text-center text-xs font-normal text-gray-500 md:text-base dark:text-gray-400">
                    Sudah memiliki akun pelanggan Rivie's Bakery?
                    <Link href="/login" class="text-primary-600 underline hover:opacity-80 dark:text-primary-500">Masuk disini</Link>
                </p>
            </form>
        </template>
    </AppLayout>
</template>
