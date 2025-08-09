<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ArrowRight, ChevronLeftCircle, ChevronRightCircle } from 'lucide-vue-next';
import Swiper from 'swiper/bundle';
import { onMounted } from 'vue';

defineOptions({
    components: {
        AppLayout,
    },
});

onMounted(() => {
    // init Swiper:
    const swiper = new Swiper('.swiper', {
        // Optional parameters
        direction: 'horizontal',
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>Home</title>
            <meta name="description" content="Home page description" />
        </Head>
        <div class="-mt-3 h-[60vh] max-h-[600px] w-full">
            <div class="swiper h-full w-full">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->
                    <div
                        class="swiper-slide bg-black/50 bg-[url('/placeholder-assets/storage/jumbotron/bg-1.jpg')] bg-cover bg-no-repeat bg-blend-darken"
                    >
                        <div class="h-full w-full relative">
                            <div class="absolute left-1/2 top-1/2 -translate-1/2  max-w-screen-xl px-4 text-center">
                                <h1 class="mb-4 text-4xl leading-none font-extrabold tracking-tight text-white">
                                    We invest in the world’s potential
                                </h1>
                                <p class="mb-8 text-lg font-normal text-gray-300">
                                    Here at Flowbite we focus on markets where technology, innovation, and capital can unlock long-term value and
                                    drive economic growth.
                                </p>
                                <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
                                    <a
                                        href="#"
                                        class="inline-flex items-center justify-center rounded-lg bg-blue-700 px-5 py-3 text-center text-base font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900"
                                    >
                                        Get started
                                        <ArrowRight class="ms-2 h-4 w-4" />
                                    </a>
                                    <a
                                        href="#"
                                        class="inline-flex items-center justify-center rounded-lg border border-white px-5 py-3 text-center text-base font-medium text-white hover:bg-gray-100 hover:text-gray-900 focus:ring-4 focus:ring-gray-400 sm:ms-4"
                                    >
                                        Learn more
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="swiper-slide bg-black/50 bg-[url('/placeholder-assets/storage/jumbotron/bg-2.jpg')] bg-cover bg-no-repeat bg-blend-darken"
                    >
                        Slide 2
                    </div>
                </div>
                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>

                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"><ChevronLeftCircle class="w-8 h-8" /> </div>
                <div class="swiper-button-next"><ChevronRightCircle class="w-8 h-8" /> </div>
            </div>
        </div>
        <div class="h-screen"></div>
    </AppLayout>
</template>
