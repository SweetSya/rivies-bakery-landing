<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ArrowLeft, ArrowRight, Bookmark, ShoppingBag } from 'lucide-vue-next';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

import { nextTick, onMounted, ref } from 'vue';

import { useCart, type CartItem } from '@/composables/useCart';
import { formatRupiah } from '@/composables/useHelperFunctions';

// Register Library
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

const { addToCart } = useCart();

const product = ref<CartItem>({
    id: Date.now().toString(),
    name: `-`,
    price: 0,
    image: '/storage/images/logo.png',
    slug: '-',
    discount: 0,
    status: {
        label: '-',
        isReady: true,
    },
    category: {
        name: '-',
    },
    quantity: 1,
});

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
    product.value = {
        id: Date.now().toString(),
        name: `Nama Produk (${Date.now().toString()})`,
        price: 100000,
        image: '/storage/images/logo.png',
        slug: 'nama-produk-1',
        discount: 10,
        status: {
            label: 'Tersedia',
            isReady: true,
        },
        category: {
            name: 'Kategori Produk',
        },
        quantity: 1,
    };
});
</script>

<template>
    <AppLayout>
        <template #pageHead>
            <Head>
                <title>{{ product.name }}</title>
                <meta name="description" content="Halaman detail produk Rivies Bakery" />
            </Head>
        </template>

        <!-- Top Background -->
        <template #pageBackground>
            <img src="/storage/images/jumbotron/1.jpg" class="absolute h-full w-full object-cover brightness-50" />
        </template>
        <!-- Breadcrumb -->
        <template #pageBreadcrumb>
            <nav class="flex" aria-label="Breadcrumb">
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
                    <li class="inline-flex items-center">
                        <Link href="/products" class="inline-flex items-center text-sm font-medium text-base-100 hover:text-primary">
                            <svg
                                class="mx-1 h-3 w-3 text-base-100/50 rtl:rotate-180"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 6 10"
                            >
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm font-medium md:ms-2">Produk</span>
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
                            <span class="ms-1 text-sm font-medium text-base-100/70 md:ms-2">Detail Produk</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </template>
        <!-- Title -->
        <template #pageTitle>Detail produk</template>
        <!-- Description -->
        <template #pageDescription>{{ product.category.name }}</template>
        <!-- Content -->
        <template #content>
            <section class="home-section-one mx-auto max-w-screen-xl items-center gap-8 px-4 py-8 sm:py-16 md:grid md:grid-cols-2 lg:px-6 xl:gap-16">
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
                <div class="text mt-4 md:mt-0">
                    <div class="mb-3 w-fit rounded bg-primary-600 px-2.5 py-0.5 text-lg font-medium text-background">Kategori Produk</div>

                    <h2 class="mb-1 text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">Nama Produk 1</h2>
                    <p class="mb-4 text-xl font-light text-foreground">
                        {{ formatRupiah(product.price - (product.price * (product.discount ?? 0)) / 100) }}
                        <sup class="text-xs line-through"> {{ formatRupiah(product.price) }}</sup>
                    </p>
                    <p class="mb-6 font-light text-gray-500 md:text-lg dark:text-gray-400">
                        Di Rivies Bakery, setiap roti, kue, dan pastry dibuat sepenuh hati dengan bahan pilihan terbaik. Nikmati aroma segar yang
                        menggoda dan rasa yang memanjakan lidah. Saatnya bawa pulang kebahagiaan, satu gigitan demi satu
                    </p>
                    <div class="flex w-full gap-2">
                        <ButtonMain type="button" :outline="true" :extend-class="'!grow !rounded-l-lg '" @click="product && addToCart(product)">
                            <ShoppingBag class="h-5 w-5" />
                            Tambah ke Keranjang
                        </ButtonMain>
                        <ButtonMain type="button" :outline="true" :extend-class="'!rounded-r-lg'"> <Bookmark class="h-5 w-5" /></ButtonMain>
                    </div>
                </div>
            </section>
        </template>
    </AppLayout>
</template>
