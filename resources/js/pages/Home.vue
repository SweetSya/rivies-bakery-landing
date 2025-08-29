<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ArrowDown, ArrowLeft, ArrowRight } from 'lucide-vue-next';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

import { useAPI } from '@/composables/useAPI';
import { nextTick, onMounted } from 'vue';

// Register Library
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

const page = usePage();
defineOptions({
    components: {
        AppLayout,
    },
});
const serverData = page.props.serverData as any;
const { getStorage } = useAPI();
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
        <template #pageHead>
            <Head>
                <title></title>
                <meta name="description" content="Home page description" />
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
        <template #pageTitle> Selamat Datang di <span class="rounded bg-primary-600 px-3 py-1 font-semibold">Rivies Bakery</span></template>
        <!-- Description -->
        <template #pageDescription>Made with love and high quality ingredients</template>
        <!-- Content -->
        <template #content>
            <div class="max-w-screen-xl px-4 py-8 sm:py-16 lg:px-6">
                <!-- <div class="mb-6 text-center">Geser untuk Menampilkan</div> -->
                <ArrowDown class="animate__animated animate__fadeInDown animate__infinite animate__slow mx-auto h-10 w-10" />
            </div>
            <section class="mx-auto max-w-screen-xl px-4 py-8 sm:py-16 lg:px-6">
                <div class="mx-auto grid max-w-screen-lg grid-cols-2 gap-4 pb-8 xs:grid-cols-3 sm:pb-16 md:grid-cols-4 lg:grid-cols-6">
                    <Link
                        v-for="value in serverData.categories"
                        :key="value.id"
                        :href="`/products?categories=${value.slug}`"
                        class="relative flex aspect-square cursor-pointer flex-col items-center justify-center gap-1 rounded border border-primary-600 bg-primary-500/40 p-4 hover:bg-transparent"
                    >
                        <img :src="getStorage(value.image)" class="aspect-square h-2/5" alt="" />
                        <h3 class="mb-2 text-base font-bold text-primary-600 md:text-lg lg:text-xl dark:text-primary-500">{{ value.name }}</h3>
                    </Link>
                </div>
                <section class="mx-auto mb-8 flex max-w-screen-md flex-wrap items-center justify-center gap-3 text-center lg:mb-16">
                    <p class="text-gray-500 sm:text-xl dark:text-gray-400">Penasaran dengan produk kami ?</p>
                    <ButtonMain type="button" :extend-class="'!w-fit'" :disabled="false" :href="'/products'">
                        Telusuri Produk <ArrowRight class="h-4 w-4" />
                    </ButtonMain>
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
                    <ButtonMain type="button" :extend-class="'!w-fit'" :disabled="false" :href="'/about'">
                        Mengenai Kami <ArrowRight class="h-4 w-4" />
                    </ButtonMain>
                </div>
            </section>
        </template>
    </AppLayout>
</template>
