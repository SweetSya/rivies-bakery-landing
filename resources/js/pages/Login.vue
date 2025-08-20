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
                <title>Login</title>
                <meta name="description" content="Halaman kontak Rivies Bakery" />
            </Head>
        </template>

        <!-- Top Background -->
        <template #pageBackground>
            <img src="/storage/images/jumbotron/1.jpg" class="absolute h-full w-full object-cover brightness-50" />
        </template>
        <!-- Breadcrumb -->
        <template #pageBreadcrumb>
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <Link href="/" class="inline-flex items-center text-sm font-medium text-base-100 hover:text-primary">
                        <svg class="me-2.5 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
                            />
                        </svg>
                        Home
                    </Link>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg
                            class="mx-1 h-3 w-3 text-base-100/50 rtl:rotate-180"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 6 10"
                        >
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="ms-1 text-sm font-medium text-base-100/70 md:ms-2">Login</span>
                    </div>
                </li>
            </ol>
        </template>
        <!-- Title -->
        <template #pageTitle>Login di <span class="rounded bg-primary-600 px-3 py-1 font-semibold">Rivies Bakery</span></template>
        <!-- Description -->
        <template #pageDescription>Made with love and high quality ingredients</template>
        <!-- Content -->
        <template #content>
            <section class="mr-auto max-w-screen-md px-4 py-8 lg:py-16">
                <!-- <h2 class="mb-4 text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">Login</h2>
                <p class="mb-8 font-light text-gray-500 sm:text-xl lg:mb-16 dark:text-gray-400">
                    Harap isi kredensial Anda untuk masuk ke akun Anda. Jika belum memiliki akun, silakan
                    <Link href="/register" class="text-primary-600 underline">daftar disini</Link>.
                </p> -->
                <form action="#" class="space-y-4">
                    <div>
                        <label for="email " class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Email</label>
                        <input
                            type="email"
                            id="email"
                            class="dark:shadow-sm-light block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            placeholder="name@riviesbakery.com"
                            required
                        />
                    </div>
                    <div>
                        <label for="subject" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300">Telepon</label>
                        <input
                            type="text"
                            id="subject"
                            class="dark:shadow-sm-light block w-full rounded-lg border border-gray-300 bg-gray-50 p-3 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            placeholder="62875..."
                            required
                        />
                    </div>
                    <div class="sm:col-span-2">
                        <label for="message" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-400">Pesan Anda</label>
                        <textarea
                            id="message"
                            rows="6"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            placeholder="Tinggalkan pesan..."
                        ></textarea>
                    </div>
                    <button
                        type="submit"
                        class="rounded-lg bg-primary-700 px-5 py-3 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 focus:outline-none sm:w-fit dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                    >
                        Send message
                    </button>
                </form>
            </section>
        </template>
    </AppLayout>
</template>
