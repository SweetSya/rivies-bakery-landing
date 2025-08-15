<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ArrowDown, ArrowLeft, ArrowRight } from 'lucide-vue-next';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

import { nextTick, onMounted } from 'vue';

// Register Library
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

defineOptions({
    components: {
        AppLayout,
    },
});

onMounted(() => {
    nextTick(() => {
        new Swiper('.swiper-banner', {
            pagination: {
                el: '.swiper-pagination',
                type: 'fraction',
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>Home</title>
            <meta name="description" content="Home page description" />
        </Head>

        <!-- Top Background -->
        <template #pageBackground>
            <img src="/storage/images/jumbotron/1.jpg" class="absolute h-full w-full object-cover brightness-50" />
        </template>
        <!-- Breadcrumb -->
        <template #pageBreadcrumb>
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="#" class="inline-flex items-center text-sm font-medium text-base-100 hover:text-primary">
                        <svg class="me-2.5 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
                            />
                        </svg>
                        Home
                    </a>
                </li>
                <!-- <li aria-current="page">
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
                        <span class="ms-1 text-sm font-medium text-base-100/70 md:ms-2">Current</span>
                    </div>
                </li> -->
            </ol>
        </template>
        <!-- Title -->
        <template #pageTitle>Selamat Datang di <span class="rounded bg-primary-600 px-3 py-1 font-semibold">Rivies Bakery</span></template>
        <!-- Description -->
        <template #pageDescription>Made with love and high quality ingredients</template>
        <!-- Content -->
        <template #content>
            <div class="max-w-screen-xl px-4 py-8 sm:py-16 lg:px-6">
                <!-- <div class="mb-6 text-center">Geser untuk Menampilkan</div> -->
                <ArrowDown class="animate__animated animate__fadeInDown animate__infinite animate__slow mx-auto h-10 w-10" />
            </div>
            <section class="mx-auto max-w-screen-xl px-4 py-8 sm:py-16 lg:px-6">
                <div class="grid grid-cols-2 gap-4 pb-8 sm:pb-16 md:grid-cols-3 lg:grid-cols-6">
                    <Link
                        href="#"
                        class="relative flex aspect-square cursor-pointer flex-col items-center justify-center gap-1 rounded border border-primary-600 bg-primary-500/40 p-4 hover:bg-transparent"
                    >
                        <img src="/assets/svg/bread.svg" class="aspect-square h-1/2" alt="" />
                        <h3 class="mb-2 text-xl font-bold text-primary-600 dark:text-primary-500">Bread</h3>
                    </Link>
                    <Link
                        href="#"
                        class="relative flex aspect-square cursor-pointer flex-col items-center justify-center gap-1 rounded border border-primary-600 bg-primary-500/40 p-4 hover:bg-transparent"
                    >
                        <img src="/assets/svg/bread.svg" class="aspect-square h-1/2" alt="" />
                        <h3 class="mb-2 text-xl font-bold text-primary-600 dark:text-primary-500">Bread</h3>
                    </Link>
                    <Link
                        href="#"
                        class="relative flex aspect-square cursor-pointer flex-col items-center justify-center gap-1 rounded border border-primary-600 bg-primary-500/40 p-4 hover:bg-transparent"
                    >
                        <img src="/assets/svg/bread.svg" class="aspect-square h-1/2" alt="" />
                        <h3 class="mb-2 text-xl font-bold text-primary-600 dark:text-primary-500">Bread</h3>
                    </Link>
                    <Link
                        href="#"
                        class="relative flex aspect-square cursor-pointer flex-col items-center justify-center gap-1 rounded border border-primary-600 bg-primary-500/40 p-4 hover:bg-transparent"
                    >
                        <img src="/assets/svg/bread.svg" class="aspect-square h-1/2" alt="" />
                        <h3 class="mb-2 text-xl font-bold text-primary-600 dark:text-primary-500">Bread</h3>
                    </Link>
                    <Link
                        href="#"
                        class="relative flex aspect-square cursor-pointer flex-col items-center justify-center gap-1 rounded border border-primary-600 bg-primary-500/40 p-4 hover:bg-transparent"
                    >
                        <img src="/assets/svg/bread.svg" class="aspect-square h-1/2" alt="" />
                        <h3 class="mb-2 text-xl font-bold text-primary-600 dark:text-primary-500">Bread</h3>
                    </Link>
                    <Link
                        href="#"
                        class="relative flex aspect-square cursor-pointer flex-col items-center justify-center gap-1 rounded border border-primary-600 bg-primary-500/40 p-4 hover:bg-transparent"
                    >
                        <img src="/assets/svg/bread.svg" class="aspect-square h-1/2" alt="" />
                        <h3 class="mb-2 text-xl font-bold text-primary-600 dark:text-primary-500">Bread</h3>
                    </Link>
                </div>
                <section class="mx-auto mb-8 flex max-w-screen-md flex-wrap items-center justify-center gap-3 text-center lg:mb-16">
                    <p class="text-gray-500 sm:text-xl dark:text-gray-400">Penasaran dengan produk kami ?</p>
                    <Link
                        href="/products"
                        class="inline-flex items-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900"
                    >
                        Telusuri Produk
                        <ArrowRight class="ms-2 h-4 w-4" />
                    </Link>
                </section>
                <div class="swiper swiper-banner h-80 w-full rounded-lg shadow-xl shadow-foreground/10">
                    <!-- Carousel wrapper -->
                    <div class="swiper-wrapper">
                        <!-- Item 1 -->
                        <div class="swiper-slide h-full">
                            <img src="/storage/images/jumbotron/1.jpg" class="h-full w-full object-cover" alt="..." />
                        </div>
                        <!-- Item 2 -->
                        <div class="swiper-slide h-full">
                            <img src="/storage/images/jumbotron/2.jpg" class="h-full w-full object-cover" alt="..." />
                        </div>
                        <!-- Item 3 -->
                        <div class="swiper-slide h-full">
                            <img src="/storage/images/jumbotron/3.jpg" class="h-full w-full object-cover" alt="..." />
                        </div>
                    </div>
                    <span class="swiper-button-next">
                        <ArrowRight class="h-6 w-6 text-white" />
                    </span>
                    <span class="swiper-button-prev">
                        <ArrowLeft class="h-6 w-6 text-white" />
                    </span>
                    <div class="swiper-pagination !text-base-50"></div>
                </div>
            </section>

            <section class="home-section-one mx-auto max-w-screen-xl items-center gap-8 px-4 py-8 sm:py-16 md:grid md:grid-cols-2 lg:px-6 xl:gap-16">
                <img class="logo mx-auto h-full rounded-full" src="/storage/images/logo.png" alt="dashboard image" />
                <div class="text mt-4 md:mt-0">
                    <h2 class="mb-4 text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">Rasakan Kehangatan dalam Setiap Gigitan</h2>
                    <p class="mb-6 font-light text-gray-500 md:text-lg dark:text-gray-400">
                        Di Rivies Bakery, setiap roti, kue, dan pastry dibuat sepenuh hati dengan bahan pilihan terbaik. Nikmati aroma segar yang
                        menggoda dan rasa yang memanjakan lidah. Saatnya bawa pulang kebahagiaan, satu gigitan demi satu
                    </p>
                    <Link
                        href="/about"
                        class="inline-flex items-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900"
                    >
                        Mengenai
                        <ArrowRight class="ms-2 h-4 w-4" />
                    </Link>
                </div>
            </section>
        </template>
    </AppLayout>
</template>
