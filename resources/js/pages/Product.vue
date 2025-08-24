<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import ProductCard from '@/components/cards/ProductCard.vue';
import ProductCardSkeleton from '@/components/cards/ProductCardSkeleton.vue';
import BaseModal from '@/components/modal/BaseModal.vue';
import { useCart } from '@/composables/useCart';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ArrowDown, ArrowLeft, ArrowRight, Filter, Search, X } from 'lucide-vue-next';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import TomSelect from 'tom-select';
import { computed, nextTick, onMounted, ref } from 'vue';

// Register Libraries
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

defineOptions({
    components: { AppLayout },
});

// --- Types ---
interface PageProps {
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
        id?: string;
    };
}

interface TomSelectRef {
    category?: TomSelect;
}
interface Category {
    id: string;
    title: string;
}

interface AppliedFilter {
    categoriesLength: number;
    categories: Category[];
    priceMax: number;
    priceMin: number;
    searchTerm: string;
}
// --- Imports ---
const { addToCart } = useCart();

// --- Constants ---
const AUTO_FETCH_LIMIT = 2;
const MOCK_CATEGORIES = [
    { id: 'category_1', title: 'Pastry', url: 'http://en.wikipedia.org/wiki/Spectrometers' },
    { id: 'category_2', title: 'Pastry 2', url: 'http://en.wikipedia.org/wiki/Star_chart' },
];
// --- State ---
const products = ref<Product[]>([]);
const pageProps = ref<PageProps>({
    loading: true,
});
const fetchCount = ref(2);
const autoFetchLimit = AUTO_FETCH_LIMIT;
const sentinel = ref<HTMLElement | null>(null);
const observer = ref<IntersectionObserver | null>(null);
const tomSelectRef = ref<TomSelectRef>({});
const modalFilter = ref<typeof BaseModal>();
// --- Filter State ---
const appliedFilter = ref<AppliedFilter>({
    categoriesLength: 0,
    categories: [],
    priceMax: 0,
    priceMin: 0,
    searchTerm: '',
});

// --- Computed ---
const isFiltersApplied = computed(() => {
    return (
        appliedFilter.value.categoriesLength > 0 ||
        appliedFilter.value.priceMax ||
        appliedFilter.value.priceMin ||
        appliedFilter.value.searchTerm !== ''
    );
});

// --- Mock Data ---
const mockProducts = [
    {
        id: 'product-1',
        name: 'Product 1',
        price: 10000,
        image: '/storage/images/logo.png',
        slug: 'product-1',
        discount: 15,
        status: { label: 'Preorder (3 Hari)', isReady: false },
        category: { name: 'Pastry' },
    },
    {
        id: 'product-2',
        name: 'Product 2',
        price: 20000,
        image: '/storage/images/logo.png',
        slug: 'product-2',
        discount: 10,
        status: { label: 'Preorder (3 Hari)', isReady: false },
        category: { id: 'category_1', name: 'Pastry' },
    },
    {
        id: 'product-3',
        name: 'Product 3',
        price: 30000,
        image: '/storage/images/logo.png',
        slug: 'product-3',
        discount: 0,
        status: { label: 'Preorder (3 Hari)', isReady: false },
        category: { id: 'category_2', name: 'Pastry 2' },
    },
];

const mockNewProducts = [
    {
        id: 'product-4',
        name: 'Product 4',
        price: 40000,
        image: '/storage/images/logo.png',
        slug: 'product-4',
        discount: 5,
        status: { label: 'Preorder (3 Hari)', isReady: false },
        category: { name: 'Pastry' },
    },
    {
        id: 'product-5',
        name: 'Product 5',
        price: 50000,
        image: '/storage/images/logo.png',
        slug: 'product-5',
        discount: 20,
        status: { label: 'Preorder (3 Hari)', isReady: false },
        category: { name: 'Pastry' },
    },
    {
        id: 'product-6',
        name: 'Product 6',
        price: 60000,
        image: '/storage/images/logo.png',
        slug: 'product-6',
        discount: 25,
        status: { label: 'Order Sekarang', isReady: true },
        category: { name: 'Pastry' },
    },
    {
        id: 'product-7',
        name: 'Product 7',
        price: 70000,
        image: '/storage/images/logo.png',
        slug: 'product-7',
        discount: 30,
        status: { label: 'Order Sekarang', isReady: true },
        category: { name: 'Pastry' },
    },
];
// --- Initialization ---
const initializeSwiper = () => {
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
};

const initializeTomSelect = () => {
    tomSelectRef.value.category = new TomSelect('#select-category', {
        maxItems: null,
        valueField: 'id',
        labelField: 'title',
        searchField: ['title'],
        options: MOCK_CATEGORIES,
        plugins: {
            remove_button: {
                title: 'Remove this item',
            },
        },
        persist: false,
        create: false,
    });
};

const initializeIntersectionObserver = () => {
    observer.value = new IntersectionObserver(
        (entries) => {
            if (entries[0].isIntersecting && fetchCount.value < autoFetchLimit && !pageProps.value.loading) {
                autoFetch();
            }
        },
        { threshold: 1 },
    );
    if (sentinel.value) observer.value.observe(sentinel.value);
};

const loadInitialData = () => {
    setTimeout(() => {
        products.value = mockProducts;
        pageProps.value.loading = false;
    }, 1000);
};

// --- Business Logic ---
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

const fetchProducts = () => {
    pageProps.value.loading = true;
    setTimeout(() => {
        products.value.push(...mockNewProducts);
        pageProps.value.loading = false;

        // Check if sentinel is visible and auto-fetch if needed
        nextTick(() => {
            if (sentinel.value && observer.value) {
                observer.value.takeRecords();
                const rect = sentinel.value.getBoundingClientRect();
                if (rect.top >= 0 && rect.bottom <= (window.innerHeight || document.documentElement.clientHeight)) {
                    autoFetch();
                }
            }
        });
    }, 1000);
};

const handleBeforeAddProductToCart = (product: Product) => {
    const cartItem = {
        ...product,
        quantity: 1,
    };
    addToCart(cartItem);
};

// --- Filter Logic ---
const syncFilterFromSessionStorage = () => {
    const storedFilter = sessionStorage.getItem('appliedFilter');
    if (storedFilter) {
        appliedFilter.value = JSON.parse(storedFilter);
        tomSelectRef.value.category?.setValue(appliedFilter.value.categories.map((cat) => cat.id) || []);
    }
};

const syncFilterToSessionStorage = () => {
    sessionStorage.setItem('appliedFilter', JSON.stringify(appliedFilter.value));
};

const setCategories = () => {
    if (Array.isArray(tomSelectRef.value.category?.getValue())) {
        appliedFilter.value.categories = MOCK_CATEGORIES.filter((cat) => (tomSelectRef.value.category?.getValue() as string[]).includes(cat.id));
    }
    appliedFilter.value.categoriesLength = appliedFilter.value.categories.length;
};
const removeCategory = (categoryId: string) => {
    appliedFilter.value.categories = appliedFilter.value.categories.filter((cat) => cat.id !== categoryId);
    tomSelectRef.value.category?.setValue(appliedFilter.value.categories.map((cat) => cat.id) || []);
    setFilters();
};
const setFilters = () => {
    if (!pageProps.value.loading) {
        setCategories();
        products.value = [];
        fetchProducts();
        fetchCount.value = autoFetchLimit;
        syncFilterToSessionStorage();
    }
};

const resetFilters = () => {
    if (!pageProps.value.loading) {
        appliedFilter.value = {
            categoriesLength: 0,
            categories: [],
            priceMax: 0,
            priceMin: 0,
            searchTerm: '',
        };
        tomSelectRef.value.category?.clear();
        products.value = [];
        fetchProducts();
        fetchCount.value = autoFetchLimit;
        syncFilterToSessionStorage();
    }
};

// --- Mounted Hook ---
onMounted(() => {
    nextTick(() => {
        initializeSwiper();
        initializeTomSelect();
        syncFilterFromSessionStorage();
    });

    loadInitialData();
    initializeIntersectionObserver();
});
</script>

<template>
    <AppLayout>
        <template #pageHead>
            <Head>
                <title>Produk</title>
                <meta name="description" content="Halaman produk Rivies Bakery" />
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
                    <Link href="/" class="inline-flex items-center text-sm font-medium text-base-100 hover:text-primary md:text-lg">
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
                        <span class="ms-1 text-sm font-medium text-base-100/70 md:ms-2 md:text-lg">Produk</span>
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

                <form @submit.prevent="setFilters()" class="mb-4 text-foreground">
                    <label for="search" class="sr-only mb-2 text-sm font-medium text-foreground md:text-lg">Search</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                            <Search class="h-4 w-4 text-foreground" />
                        </div>
                        <input
                            type="search"
                            id="search"
                            v-model="appliedFilter.searchTerm"
                            class="block w-full rounded-lg border bg-base-300/5 px-4 py-3 ps-10"
                            placeholder="Cari sesuatu.."
                            required
                        />
                        <button
                            class="absolute end-2.5 bottom-2 inline-flex cursor-pointer items-center rounded-lg border border-border bg-background px-5 py-2 text-center text-xs font-medium text-foreground hover:bg-muted hover:opacity-80 focus:ring-4 focus:ring-ring/20 focus:outline-none"
                        >
                            Cari
                        </button>
                    </div>
                </form>

                <div class="sticky top-32 mb-4 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8">
                    <div>
                        <h2 class="mt-3 text-xl font-semibold text-foreground sm:text-2xl dark:text-white">Produk Rivies</h2>
                        <div class="mt-2 flex flex-wrap justify-start gap-2">
                            <span
                                v-for="category in appliedFilter.categories"
                                :key="category.id"
                                id="category-{{ category.id }}"
                                class="flex items-center gap-2 rounded bg-primary-600 px-3 py-2 text-xs font-light text-foreground md:text-base"
                                >{{ category.title }} <X class="h-4 w-4 cursor-pointer" @click="removeCategory(category.id)"
                            /></span>
                        </div>
                    </div>
                    <div class="mb-auto flex items-center space-x-4">
                        <button
                            @click="modalFilter?.open()"
                            class="inline-flex cursor-pointer items-center rounded-lg border border-border bg-background px-5 py-2 text-center text-xs font-medium text-foreground hover:bg-muted hover:opacity-80 focus:ring-4 focus:ring-ring/20 focus:outline-none"
                        >
                            Filter <Filter class="ms-2 h-4 w-4" />
                        </button>
                        <button
                            v-show="isFiltersApplied"
                            @click="resetFilters"
                            class="inline-flex cursor-pointer items-center rounded-lg border border-border bg-background px-5 py-2 text-center text-xs font-medium text-foreground hover:bg-muted hover:opacity-80 focus:ring-4 focus:ring-ring/20 focus:outline-none"
                        >
                            Reset <X class="ms-2 h-4 w-4" />
                        </button>
                    </div>
                </div>
                <div class="mb-4 grid gap-4 xs:grid-cols-2 md:mb-8 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                    <ProductCard
                        v-for="product in products"
                        :key="product.id"
                        :id="product.id"
                        :name="product.name"
                        :price="product.price"
                        :image="product.image"
                        :slug="product.slug"
                        :discount="product.discount"
                        :status="product.status"
                        :category="product.category"
                        @handle-add-to-cart="handleBeforeAddProductToCart(product)"
                    />
                    <ProductCardSkeleton :total="5" v-if="pageProps.loading" />
                </div>
                <div ref="sentinel"></div>

                <div class="flex w-full justify-center">
                    <ButtonMain v-if="!pageProps.loading" @click="fetchManually" type="button" :extend-class="'!w-fit'">
                        Muat lebih banyak <ArrowDown class="h-4 w-4" />
                    </ButtonMain>
                </div>
            </section>
            <!-- Main modal -->
            <BaseModal :id="'filter-produk'" :title="'Filter Produk'" :is-closeable="true" ref="modalFilter">
                <template #modalIcon>
                    <Filter class="h-5 w-5" />
                </template>
                <template #modalContent>
                    <form @submit.prevent="(setFilters(), modalFilter?.close())">
                        <div class="space-y-4">
                            <div class="mb-6 grid gap-6 md:grid-cols-2">
                                <div class="col-span-2">
                                    <label for="select-category" class="mb-2 block text-sm font-medium text-foreground md:text-lg dark:text-white"
                                        >Pilih Kategori</label
                                    >
                                    <select id="select-category" placeholder="Cari kategori.."></select>
                                </div>
                            </div>
                            <div class="mb-6 grid gap-6 md:grid-cols-2">
                                <div class="col-span-2">
                                    <label for="price" class="mb-2 block text-sm font-medium text-foreground md:text-lg dark:text-white">Harga</label>
                                    <div class="flex flex-wrap gap-5 md:flex-nowrap">
                                        <div class="flex grow">
                                            <span
                                                class="rounded-e-0 inline-flex items-center rounded-s-md border border-e-0 border-foreground/20 bg-primary-500 px-3 text-sm text-foreground md:text-lg"
                                            >
                                                Rp
                                            </span>
                                            <input
                                                type="number"
                                                v-model="appliedFilter.priceMin"
                                                id="minimum-price"
                                                class="base-input block w-full min-w-0 flex-1 rounded-none rounded-e-lg"
                                            />
                                        </div>
                                        <div class="flex grow">
                                            <span
                                                class="rounded-e-0 inline-flex items-center rounded-s-md border border-e-0 border-foreground/20 bg-primary-500 px-3 text-sm text-foreground md:text-lg"
                                            >
                                                Rp
                                            </span>
                                            <input
                                                type="number"
                                                v-model="appliedFilter.priceMax"
                                                id="maximum-price"
                                                class="base-input block w-full min-w-0 flex-1 rounded-none rounded-e-lg"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end space-x-2 rounded-b py-4 md:py-5">
                            <button
                                type="submit"
                                class="w-full cursor-pointer rounded-lg border border-primary-500 bg-primary-500 px-5 py-2 text-center text-xs font-medium text-foreground hover:bg-primary-700 focus:z-10 focus:ring-4 focus:ring-primary-200 focus:outline-none md:text-base dark:border-primary-600 dark:bg-primary-600"
                            >
                                Terapkan
                            </button>
                        </div>
                    </form>
                </template>
            </BaseModal>
        </template>
    </AppLayout>
</template>
