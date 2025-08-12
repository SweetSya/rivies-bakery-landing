<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ArrowDown, ArrowRight } from 'lucide-vue-next';
import { onMounted } from 'vue';

// Register GSAP
gsap.registerPlugin(ScrollTrigger);

defineOptions({
    components: {
        AppLayout,
    },
});

onMounted(() => {
    // gsap.to('.home-section-one .logo', {
    //     scrollTrigger: {
    //         trigger: '.home-section-one',
    //         start: 'top+=150 center',
    //         end: 'bottom center',
    //         scrub: 1.5,
    //         markers: true,
    //     },
    //     opacity: 0,
    //     yPercent: '200',
    //     scale: .1,
    //     ease: 'none',
    // });
    // gsap.to('.home-section-one .text', {
    //     scrollTrigger: {
    //         trigger: '.home-section-one .text',
    //         start: 'top+=150 center',
    //         end: 'bottom center',
    //         scrub: 1.5,
    //         markers: true,
    //     },
    //     xPercent: '100',
    //     skewY: 20,
    //     scale: 1.2,
    //     opacity: 0,
    //     ease: 'none',
    // });
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
            <img src="/storage/images/jumbotron/jumbotron1.jpg" class="absolute h-full w-full object-cover brightness-50" />
        </template>
        <!-- Breadcrumb -->
        <template #pageBreadcrumb>
            <!-- <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
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
                        <span class="ms-1 text-sm font-medium text-base-100/70 md:ms-2">Current</span>
                    </div>
                </li>
            </ol> -->
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
            <section class="home-section-one mx-auto max-w-screen-xl items-center gap-8 px-4 py-8 sm:py-16 md:grid md:grid-cols-2 lg:px-6 xl:gap-16">
                <img class="logo mx-auto h-full rounded-full" src="/storage/images/logo.png" alt="dashboard image" />
                <div class="text mt-4 md:mt-0">
                    <h2 class="mb-4 text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">Rasakan Kehangatan dalam Setiap Gigitan</h2>
                    <p class="mb-6 font-light text-gray-500 md:text-lg dark:text-gray-400">
                        Di Rivies Bakery, setiap roti, kue, dan pastry dibuat sepenuh hati dengan bahan pilihan terbaik. Nikmati aroma segar yang
                        menggoda dan rasa yang memanjakan lidah. Saatnya bawa pulang kebahagiaan, satu gigitan demi satu
                    </p>
                    <Link
                        href="#"
                        class="inline-flex items-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900"
                    >
                        Lebih Lanjut
                        <ArrowRight class="ms-2 h-4 w-4" />
                    </Link>
                </div>
            </section>
            <div class="home-section-two h-screen"></div>
            <!-- <section class="mx-auto max-w-screen-xl px-4 pb-8 sm:pb-16 lg:px-6">
                <div class="mt-4 md:mt-0">
                    <h2 class="mb-4 text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">Rasakan Kehangatan dalam Setiap Gigitan</h2>
                    <p class="mb-6 font-light text-gray-500 md:text-lg dark:text-gray-400">
                        Di Rivies Bakery, setiap roti, kue, dan pastry dibuat sepenuh hati dengan bahan pilihan terbaik. Nikmati aroma segar yang
                        menggoda dan rasa yang memanjakan lidah. Saatnya bawa pulang kebahagiaan, satu gigitan demi satu
                    </p>
                    <Link
                        href="#"
                        class="inline-flex items-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900"
                    >
                        Lebih Lanjut
                        <ArrowRight class="ms-2 h-4 w-4" />
                    </Link>
                </div>
            </section> -->
            <!-- <div class="mx-auto max-w-screen-xl px-4 pb-8 sm:pb-16 lg:px-6">
                <div class="mx-auto mb-8 max-w-screen-md text-center lg:mb-16">
                    <h2 class="mb-4 text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">Kategori Produk</h2>
                    <p class="text-gray-500 sm:text-xl dark:text-gray-400">
                        Kami memberikan produk yang berkualitas tinggi dengan bahan-bahan terbaik dan rasa yang lezat. Temukan berbagai kategori
                        produk kami yang siap memanjakan anda.
                    </p>
                </div>
                <div class="space-y-8 pb-8 sm:pb-16 md:grid md:grid-cols-2 md:gap-12 md:space-y-0 lg:grid-cols-3">
                    <Link href="#">
                        <div
                            class="cursor-pointer rounded-l-xl bg-base-900/70 bg-[url('/storage/images/jumbotron/jumbotron1.jpg')] bg-cover bg-center bg-no-repeat p-4 bg-blend-darken shadow-xs shadow-primary hover:bg-base-900/50 hover:shadow-md"
                        >
                            <div
                                class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 lg:h-12 lg:w-12 dark:bg-primary-900"
                            >
                                <svg
                                    class="h-5 w-5 text-primary-600 lg:h-6 lg:w-6 dark:text-primary-300"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                            </div>
                            <h3 class="mb-2 text-xl font-bold text-primary-600 dark:text-primary-500">Pastry</h3>
                            <p class="text-foreground">
                                Plan it, create it, launch it. Collaborate seamlessly with all the organization and hit your marketing goals every
                                month with our marketing plan.
                            </p>
                        </div>
                    </Link>
                    <Link href="#">
                        <div
                            class="cursor-pointer rounded-l-xl bg-base-900/70 bg-[url('/storage/images/jumbotron/jumbotron1.jpg')] bg-cover bg-center bg-no-repeat p-4 bg-blend-darken shadow-xs shadow-primary hover:bg-base-900/50 hover:shadow-md"
                        >
                            <div
                                class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 lg:h-12 lg:w-12 dark:bg-primary-900"
                            >
                                <svg
                                    class="h-5 w-5 text-primary-600 lg:h-6 lg:w-6 dark:text-primary-300"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                            </div>
                            <h3 class="mb-2 text-xl font-bold text-primary-600 dark:text-primary-500">Cake</h3>
                            <p class="text-foreground">
                                Plan it, create it, launch it. Collaborate seamlessly with all the organization and hit your marketing goals every
                                month with our marketing plan.
                            </p>
                        </div>
                    </Link>
                    <Link href="#">
                        <div
                            class="cursor-pointer rounded-l-xl bg-base-900/70 bg-[url('/storage/images/jumbotron/jumbotron1.jpg')] bg-cover bg-center bg-no-repeat p-4 bg-blend-darken shadow-xs shadow-primary hover:bg-base-900/50 hover:shadow-md"
                        >
                            <div
                                class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 lg:h-12 lg:w-12 dark:bg-primary-900"
                            >
                                <svg
                                    class="h-5 w-5 text-primary-600 lg:h-6 lg:w-6 dark:text-primary-300"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                            </div>
                            <h3 class="mb-2 text-xl font-bold text-primary-600 dark:text-primary-500">Bakery</h3>
                            <p class="text-foreground">
                                Plan it, create it, launch it. Collaborate seamlessly with all the organization and hit your marketing goals every
                                month with our marketing plan.
                            </p>
                        </div>
                    </Link>
                </div>
                <div class="mx-auto mb-8 max-w-screen-md text-center lg:mb-16">
                    <p class="text-gray-500 sm:text-xl dark:text-gray-400">Belum menemukan produk yang diinginkan? Kunjungi lebih lengkap</p>
                </div>
            </div> -->
        </template>
    </AppLayout>
</template>
