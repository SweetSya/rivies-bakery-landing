<script setup lang="ts">
import ProductCard from '@/components/cards/ProductCard.vue';
import ProductCardSkeleton from '@/components/cards/ProductCardSkeleton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ArrowLeft, ArrowRight } from 'lucide-vue-next';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import { nextTick, onMounted, ref } from 'vue';

// Register Library
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

defineOptions({
    components: {
        AppLayout,
    },
});
interface pageProps {
    loading: boolean;
}
interface Product {
    id: string;
    name: string;
    price: number;
    image: string;
    slug: string;
    discount: number;
    status: {
        label: string;
        isReady: boolean;
    };
    category: {
        name: string;
    };
}
const products = ref<Product[]>([]);
const pageProps = ref<pageProps>({
    loading: true,
});
const fetchCount = ref(2);
const autoFetchLimit = 2;
const sentinel = ref<HTMLElement | null>(null);
const observer = ref<IntersectionObserver | null>(null);
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
    setTimeout(() => {
        products.value = [
            {
                id: 'product-1',
                name: 'Product 1',
                price: 10000,
                image: '/storage/images/products/1.jpg',
                slug: 'product-1',
                discount: 15,
                status: {
                    label: 'Preorder (3 Hari)',
                    isReady: false,
                },
                category: {
                    name: 'Pastry',
                },
            },
            {
                id: 'product-2',
                name: 'Product 2',
                price: 20000,
                image: '/storage/images/products/2.jpg',
                slug: 'product-2',
                discount: 10,
                status: {
                    label: 'Preorder (3 Hari)',
                    isReady: false,
                },
                category: {
                    name: 'Pastry',
                },
            },
            {
                id: 'product-3',
                name: 'Product 3',
                price: 30000,
                image: '/storage/images/products/3.jpg',
                slug: 'product-3',
                discount: 0,
                status: {
                    label: 'Preorder (3 Hari)',
                    isReady: false,
                },
                category: {
                    name: 'Pastry',
                },
            },
        ];
        pageProps.value.loading = false;
    }, 1000);
    // Intersection Observer for auto-fetch
    // Intersection Observer for auto-fetch
    observer.value = new IntersectionObserver(
        (entries) => {
            if (entries[0].isIntersecting && fetchCount.value < autoFetchLimit && !pageProps.value.loading) {
                autoFetch();
            }
        },
        { threshold: 1 },
    );
    if (sentinel.value) observer.value.observe(sentinel.value);
});

const autoFetch = () => {
    if (!pageProps.value.loading && fetchCount.value < autoFetchLimit) {
        fetchProducts();
        fetchCount.value++;
    }
};
const fetchManually = () => {
    if (!pageProps.value.loading) {
        fetchProducts();
        fetchCount.value = 0;
    }
};
// Fetch products
const fetchProducts = () => {
    // Simulate fetching more products
    pageProps.value.loading = true;

    setTimeout(() => {
        // ...your product fetching logic...
        const newProducts: Product[] = [
            {
                id: 'product-4',
                name: 'Product 4',
                price: 40000,
                image: '/storage/images/products/4.jpg',
                slug: 'product-4',
                discount: 5,
                status: {
                    label: 'Preorder (3 Hari)',
                    isReady: false,
                },
                category: {
                    name: 'Pastry',
                },
            },
            {
                id: 'product-5',
                name: 'Product 5',
                price: 50000,
                image: '/storage/images/products/5.jpg',
                slug: 'product-5',
                discount: 20,
                status: {
                    label: 'Preorder (3 Hari)',
                    isReady: false,
                },
                category: {
                    name: 'Pastry',
                },
            },
            {
                id: 'product-6',
                name: 'Product 6',
                price: 60000,
                image: '/storage/images/products/6.jpg',
                slug: 'product-6',
                discount: 25,
                status: {
                    label: 'Order Sekarang',
                    isReady: true,
                },
                category: {
                    name: 'Pastry',
                },
            },
            {
                id: 'product-7',
                name: 'Product 7',
                price: 70000,
                image: '/storage/images/products/7.jpg',
                slug: 'product-7',
                discount: 30,
                status: {
                    label: 'Order Sekarang',
                    isReady: true,
                },
                category: {
                    name: 'Pastry',
                },
            },
        ];
        products.value.push(...newProducts);
        pageProps.value.loading = false;

        // After loading, check if sentinel is visible and auto-fetch if needed
        nextTick(() => {
            if (sentinel.value && observer.value) {
                observer.value.takeRecords(); // flush any pending records
                // Manually trigger observer callback
                const rect = sentinel.value.getBoundingClientRect();
                if (rect.top >= 0 && rect.bottom <= (window.innerHeight || document.documentElement.clientHeight)) {
                    autoFetch();
                }
            }
        });
    }, 1000);
};
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
                        <span class="ms-1 text-sm font-medium text-base-100/70 md:ms-2">Produk</span>
                    </div>
                </li>
            </ol>
        </template>
        <!-- Title -->
        <template #pageTitle>Produk di <span class="rounded bg-primary-600 px-3 py-1 font-semibold">Rivies Bakery</span></template>
        <!-- Description -->
        <template #pageDescription>Made with love and high quality ingredients</template>
        <!-- Content -->
        <template #content>
            <section class="mx-auto max-w-screen-xl px-4 py-8 sm:py-16 lg:px-6">
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
            <section class="mx-auto max-w-screen-xl px-4 py-8 sm:py-16 lg:px-6">
                <!-- Heading & Filters -->
                <div class="sticky top-32 mb-4 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8">
                    <div>
                        <h2 class="mt-3 text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Category</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button
                            data-modal-toggle="filterModal"
                            data-modal-target="filterModal"
                            type="button"
                            class="flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 focus:outline-none sm:w-auto dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
                        >
                            <svg
                                class="-ms-0.5 me-2 h-4 w-4"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-width="2"
                                    d="M18.796 4H5.204a1 1 0 0 0-.753 1.659l5.302 6.058a1 1 0 0 1 .247.659v4.874a.5.5 0 0 0 .2.4l3 2.25a.5.5 0 0 0 .8-.4v-7.124a1 1 0 0 1 .247-.659l5.302-6.059c.566-.646.106-1.658-.753-1.658Z"
                                />
                            </svg>
                            Filters
                            <svg
                                class="ms-2 -me-0.5 h-4 w-4"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                            </svg>
                        </button>
                        <button
                            id="sortDropdownButton1"
                            data-dropdown-toggle="dropdownSort1"
                            type="button"
                            class="flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 focus:outline-none sm:w-auto dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
                        >
                            <svg
                                class="-ms-0.5 me-2 h-4 w-4"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 4v16M7 4l3 3M7 4 4 7m9-3h6l-6 6h6m-6.5 10 3.5-7 3.5 7M14 18h4"
                                />
                            </svg>
                            Sort
                            <svg
                                class="ms-2 -me-0.5 h-4 w-4"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            id="dropdownSort1"
                            class="z-50 hidden w-40 divide-y divide-gray-100 rounded-lg bg-white shadow transition-opacity dark:bg-gray-700"
                            data-popper-placement="bottom"
                        >
                            <ul class="p-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400" aria-labelledby="sortDropdownButton">
                                <li>
                                    <a
                                        href="#"
                                        class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    >
                                        The most popular
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    >
                                        Newest
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    >
                                        Increasing price
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    >
                                        Decreasing price
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    >
                                        No. reviews
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    >
                                        Discount %
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                    <ProductCard
                        v-for="product in products"
                        :key="product.id"
                        :name="product.name"
                        :price="product.price"
                        :image="product.image"
                        :slug="product.slug"
                        :discount="product.discount"
                        :status="product.status"
                        :category="product.category"
                    />
                    <ProductCardSkeleton :total="5" v-if="pageProps.loading" />
                </div>
                <div ref="sentinel"></div>
                <div class="w-full text-center">
                    <button
                        v-if="!pageProps.loading"
                        @click="fetchManually"
                        type="button"
                        class="cursor-pointer rounded-lg border border-primary-600 bg-primary-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-700 hover:text-white focus:z-10 focus:ring-4 focus:ring-primary-200 focus:outline-none dark:border-primary-600 dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700 dark:hover:text-white dark:focus:ring-primary-900"
                    >
                        Muat lebih banyak
                    </button>
                </div>
            </section>
        </template>
    </AppLayout>
</template>
