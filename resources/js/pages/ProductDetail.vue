<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ArrowLeft, ArrowRight, Bookmark, ShoppingBag } from 'lucide-vue-next';
import PhotoSwipeLightbox from 'photoswipe/lightbox';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import { computed, nextTick, onMounted, ref } from 'vue';

import { useAPI } from '@/composables/useAPI';
import { useCart, type CartItem } from '@/composables/useCart';
import { formatRupiah } from '@/composables/useHelperFunctions';
import { useNotifications } from '@/composables/useNotifications';

// 📌 Register plugins
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

const { notivueError } = useNotifications();
const { addToCart } = useCart();
const { getStorage } = useAPI();

// 🔹 Types
type PreviewImage = { url: string; width: number; height: number };
type ProductPrice = { id: string; tag: string; price: number; discount?: number };

// 🔹 Props from server
const page = usePage();
const baseProduct = page.props.product as CartItem;
const prices = ref<ProductPrice[]>(page.props.prices as ProductPrice[]);

// 🔹 State
const product = ref<CartItem>({ ...baseProduct });
const previewImages = ref<PreviewImage[]>([]);

// 🔹 Helpers
const backgroundImage = computed(() => product.value.images?.[0] ?? '');

const getImageSize = (url: string) =>
    new Promise<PreviewImage>((resolve, reject) => {
        const img = new Image();
        img.onload = () => resolve({ url, width: img.naturalWidth, height: img.naturalHeight });
        img.onerror = reject;
        img.src = getStorage(url);
    });

// 🔹 Variant handler
const selectPrice = (price: ProductPrice | null) => {
    if (!price) {
        notivueError('Produk atau harga tidak valid');
        return;
    }

    product.value = {
        ...baseProduct,
        id: price.id,
        name: `${baseProduct.name} - ${price.tag}`,
        price: Number(price.price) || 0,
        discount: Number(price.discount) || 0,
        quantity: 1,
    };
};

// 🔹 Lifecycle
onMounted(async () => {
    // Load images
    if (baseProduct.images) {
        previewImages.value = await Promise.all(baseProduct.images.map((image) => getImageSize(image)));
    }

    // Set default selected price
    if (prices.value.length > 0) {
        selectPrice(prices.value[0]);
    }

    nextTick(() => {
        new Swiper('.swiper-banner', {
            pagination: { el: '.swiper-pagination', type: 'fraction' },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        });

        const lightbox = new PhotoSwipeLightbox({
            gallery: '.swiper-wrapper a',
            pswpModule: () => import('photoswipe'),
        });
        lightbox.init();
    });
});
</script>

<template>
    <AppLayout>
        <!-- Page Head -->
        <template #pageHead>
            <Head>
                <title>{{ product.name }}</title>
                <meta name="description" content="Halaman detail produk Rivies Bakery" />
            </Head>
        </template>

        <!-- Background -->
        <template #pageBackground>
            <img :src="getStorage(backgroundImage)" class="absolute h-full w-full object-cover brightness-50" />
        </template>

        <!-- Breadcrumb -->
        <template #pageBreadcrumb>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <Link href="/" class="inline-flex items-center text-sm font-medium text-base-100 hover:text-primary">
                            <svg class="me-2.5 h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
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
        <template #pageDescription>
            <div class="mb-4 flex flex-wrap gap-2 md:gap-3">
                <div
                    v-for="value in product.category"
                    :key="value.id"
                    class="flex w-fit items-center gap-2 rounded border border-primary-600 bg-background px-2.5 py-1.5 text-xs font-medium text-primary-600 md:text-base"
                >
                    <img class="h-4 w-4" :src="getStorage(value.image ?? '')" alt="" />
                    {{ value.name }}
                </div>
            </div>
        </template>

        <!-- Content -->
        <template #content>
            <section class="home-section-one mx-auto max-w-screen-xl gap-8 px-4 py-8 sm:py-16 md:grid md:grid-cols-2 lg:px-6 xl:gap-16">
                <!-- Images -->
                <div class="swiper swiper-banner h-80 w-full rounded-lg shadow-xl shadow-foreground/10">
                    <div class="swiper-wrapper">
                        <div v-for="(image, index) in previewImages" :key="index" class="swiper-slide">
                            <a
                                :href="getStorage(image.url)"
                                :data-pswp-width="image.width"
                                :data-pswp-height="image.height"
                                data-cropped="true"
                                target="_blank"
                                rel="noopener"
                            >
                                <img :src="getStorage(image.url)" class="h-full w-full object-cover" alt="..." />
                            </a>
                        </div>
                    </div>
                    <span class="swiper-button-next"><ArrowRight class="h-6 w-6 text-white" /></span>
                    <span class="swiper-button-prev"><ArrowLeft class="h-6 w-6 text-white" /></span>
                    <div class="swiper-pagination !text-base-50"></div>
                </div>

                <!-- Details -->
                <div class="text mt-6 md:mt-0">
                    <h2 class="mb-2 text-xl font-extrabold tracking-tight text-gray-900 md:text-3xl dark:text-white">
                        {{ product.name }}
                    </h2>
                    <p class="mb-4 text-xl font-light text-foreground">
                        <span
                            :class="product.discount ? 'w-fit rounded border border-primary-500 bg-primary-500/30' : ''"
                            class="overflow-hidden p-1 text-base leading-tight font-bold text-foreground md:text-xl"
                        >
                            {{ formatRupiah(product.price - (product.discount ?? 0)) }}
                        </span>
                        <sup v-if="product.discount" class="ms-1 text-xs line-through md:text-base">
                            {{ formatRupiah(product.price) }}
                        </sup>
                    </p>

                    <p class="mb-6 font-light text-gray-500 md:text-lg dark:text-gray-400">
                        {{ product.description || 'Deskripsi produk tidak tersedia' }}
                    </p>

                    <!-- Variant buttons -->
                    <div class="mb-4 flex flex-wrap gap-2 md:gap-3">
                        <ButtonMain
                            v-for="value in prices"
                            :key="value.id"
                            type="button"
                            :outline="value.id !== product.id"
                            :extend-class="'!text-xs !px-3'"
                            @click="selectPrice(value)"
                        >
                            {{ value.tag }}
                        </ButtonMain>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex w-full gap-2">
                        <ButtonMain
                            type="button"
                            :outline="true"
                            :extend-class="'!grow !rounded-l-lg '"
                            @click="addToCart({ ...product, quantity: 1 })"
                        >
                            <ShoppingBag class="h-5 w-5" />
                            Tambah ke Keranjang
                        </ButtonMain>
                        <ButtonMain type="button" :outline="true" :extend-class="'!rounded-r-lg'">
                            <Bookmark class="h-5 w-5" />
                        </ButtonMain>
                    </div>
                </div>
            </section>
        </template>
    </AppLayout>
</template>
