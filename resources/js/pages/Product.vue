<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import ProductCard from '@/components/cards/ProductCard.vue';
import ProductCardSkeleton from '@/components/cards/ProductCardSkeleton.vue';
import BaseModal from '@/components/modal/BaseModal.vue';
import { useAPI } from '@/composables/useAPI';
import { useCart } from '@/composables/useCart';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ArrowDown, ArrowLeft, ArrowRight, Filter, Minus, Search, X } from 'lucide-vue-next';
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
const { fetchAPI } = useAPI();

// --- Types ---
interface PageProps {
    loading: boolean;
    paginate: number;
    page: number;
    message: string;
    pageEnd: boolean;
}

interface Product {
    id: string;
    name: string;
    price: number;
    thumbnail: string;
    slug: string;
    discount: number;
    images?: Array<string>;
    status: {
        label: string;
        isReady: boolean;
    };
    category: {
        name: string;
        id: string;
        slug: string;
        image?: string;
    }[];
}

interface TomSelectRef {
    category?: TomSelect;
}
interface Category {
    id: string;
    name: string;
    slug: string;
    image: string;
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
const page = usePage();

// --- Constants ---
const AUTO_FETCH_LIMIT = 2;
const CATEGORIES = ref<Category[]>([]);
// --- State ---
const products = ref<Product[]>([]);
const pageProps = ref<PageProps>({
    loading: true,
    paginate: 10,
    page: 0,
    message: '',
    pageEnd: false,
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
        labelField: 'name',
        searchField: ['name'],
        options: CATEGORIES.value,
        plugins: {
            remove_button: {
                title: 'Remove this item',
            },
        },
        persist: false,
        create: false,
    });
    if (appliedFilter.value.categoriesLength > 0) {
        setTimeout(() => {
            tomSelectRef.value.category?.setValue(appliedFilter.value.categories.map((cat) => cat.id) || []);
        }, 200);
    }
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
    CATEGORIES.value = page.props.categories as Category[];
    syncFilterFromSessionStorage();
    initializeFiltersViaParams();
    initializeSwiper();
    initializeTomSelect();
    fetchProducts();
};
const initializeFiltersViaParams = () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('categories')) {
        const categories = urlParams.get('categories')?.split(',') || [];
        appliedFilter.value.categories = CATEGORIES.value.filter((cat) => categories.includes(cat.slug));
        appliedFilter.value.categoriesLength = appliedFilter.value.categories.length;
    }
    if (urlParams.has('price_min')) {
        appliedFilter.value.priceMin = Number(urlParams.get('price_min'));
    }
    if (urlParams.has('price_max')) {
        appliedFilter.value.priceMax = Number(urlParams.get('price_max'));
    }
    if (urlParams.has('search')) {
        appliedFilter.value.searchTerm = urlParams.get('search') || '';
    }
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
const handleUnsuccesfulFetch = (message: string) => {
    pageProps.value.loading = false;
    // Handle error case
    fetchCount.value = 2;
    pageProps.value.pageEnd = true;
    pageProps.value.message = message;
};
const fetchProducts = async () => {
    pageProps.value.loading = true;
    pageProps.value.page += 1;
    // Check filters
    const filterParams = [];
    if (appliedFilter.value.categoriesLength > 0) {
        filterParams.push('categories=' + appliedFilter.value.categories.map((cat) => cat.slug).join(','));
    }
    if (appliedFilter.value.priceMin > 0) {
        filterParams.push('price_min=' + appliedFilter.value.priceMin);
    }
    if (appliedFilter.value.priceMax > 0) {
        filterParams.push('price_max=' + appliedFilter.value.priceMax);
    }
    if (appliedFilter.value.searchTerm !== '') {
        filterParams.push('search=' + appliedFilter.value.searchTerm);
    }
    // Set URL
    const applyingFilters = filterParams.join('&');
    const newUrl = `${window.location.pathname}${applyingFilters ? '?' + applyingFilters : ''}`;
    window.history.replaceState({}, '', newUrl);
    // Fetch products to API
    const params = 'page=' + pageProps.value.page + '&paginate=' + pageProps.value.paginate + '&' + applyingFilters;
    const response = await fetchAPI('products/fetch?' + params);
    if (!response.data.success) {
        handleUnsuccesfulFetch(response.data.message || 'Something went wrong, please refresh the page');
        return;
    }
    // console.log(response.data.products);
    products.value.push(...response.data.products);

    pageProps.value.loading = false;
    nextTick(() => {
        if (sentinel.value && observer.value) {
            observer.value.takeRecords();
            const rect = sentinel.value.getBoundingClientRect();
            if (rect.top >= 0 && rect.bottom <= (window.innerHeight || document.documentElement.clientHeight)) {
                autoFetch();
            }
        }
    });
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
    }
};

const syncFilterToSessionStorage = () => {
    sessionStorage.setItem('appliedFilter', JSON.stringify(appliedFilter.value));
};

const setCategories = () => {
    if (Array.isArray(tomSelectRef.value.category?.getValue())) {
        const selectedIds = tomSelectRef.value.category?.getValue() as string[];
        // Only keep plain properties
        appliedFilter.value.categories = CATEGORIES.value
            .filter((cat) => selectedIds.includes(cat.id))
            .map((cat) => ({
                id: cat.id,
                name: cat.name,
                slug: cat.slug,
                image: cat.image,
            }));
    }
    appliedFilter.value.categoriesLength = appliedFilter.value.categories.length;
};
const removeCategory = (categoryId: string) => {
    appliedFilter.value.categories = appliedFilter.value.categories.filter((cat) => cat.id !== categoryId);
    tomSelectRef.value.category?.setValue(appliedFilter.value.categories.map((cat) => cat.id) || []);
    setFilters();
};
const resetPageProps = () => {
    products.value = [];
    pageProps.value.page = 0;
    pageProps.value.pageEnd = false;
    pageProps.value.message = '';
    fetchCount.value = AUTO_FETCH_LIMIT;
};
const setFilters = () => {
    if (!pageProps.value.loading) {
        setCategories();
        resetPageProps();
        fetchProducts();
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
        resetPageProps();
        fetchProducts();
        syncFilterToSessionStorage();
    }
};

// --- Mounted Hook ---
onMounted(() => {
    nextTick(() => {
        loadInitialData();
    });

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
                                class="flex items-center gap-2 rounded bg-primary-600 px-3 py-2 text-xs font-light text-background md:text-base"
                                >{{ category.name }} <X class="h-4 w-4 cursor-pointer" @click="removeCategory(category.id)"
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
                <div class="mb-4 grid grid-cols-2 gap-4 md:mb-8 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                    <ProductCard
                        v-for="product in products"
                        :key="product.id"
                        :id="product.id"
                        :name="product.name"
                        :price="product.price"
                        :thumbnail="product.thumbnail"
                        :slug="product.slug"
                        :discount="product.discount"
                        :status="product.status"
                        :category="product.category"
                        @handle-add-to-cart="handleBeforeAddProductToCart(product)"
                    />
                    <ProductCardSkeleton :total="5" v-if="pageProps.loading" />
                </div>
                <p v-if="pageProps.message != ''" class="mb-6 text-center font-light text-gray-500 md:text-lg dark:text-gray-400">
                    - {{ pageProps.message }} -
                </p>
                <div ref="sentinel"></div>

                <div class="flex w-full justify-center">
                    <ButtonMain
                        v-if="!pageProps.loading && fetchCount == 2 && !pageProps.pageEnd"
                        @click="fetchManually"
                        type="button"
                        :extend-class="'!w-fit'"
                    >
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
                                    <label class="mb-2 block text-sm font-medium text-foreground md:text-lg dark:text-white">Pilih Kategori</label>
                                    <select id="select-category" placeholder="Cari kategori.."></select>
                                </div>
                            </div>
                            <div class="mb-6 grid gap-6 md:grid-cols-2">
                                <div class="col-span-2">
                                    <label for="price" class="mb-2 block text-sm font-medium text-foreground md:text-lg dark:text-white"
                                        >Harga (Rupiah)</label
                                    >
                                    <div class="flex flex-wrap items-center gap-5 md:flex-nowrap">
                                        <div class="flex grow">
                                            <span
                                                class="rounded-e-0 inline-flex items-center rounded-s-md border border-e-0 border-foreground/20 bg-primary-600 px-2 text-sm text-background md:text-base"
                                            >
                                                Min.
                                            </span>
                                            <input
                                                type="number"
                                                v-model="appliedFilter.priceMin"
                                                id="minimum-price"
                                                class="base-input block w-full min-w-0 flex-1 rounded-none rounded-e-lg"
                                            />
                                        </div>
                                        <div><Minus class="h-4 w-4" /></div>
                                        <div class="flex grow">
                                            <span
                                                class="rounded-e-0 inline-flex items-center rounded-s-md border border-e-0 border-foreground/20 bg-primary-600 px-2 text-sm text-background md:text-base"
                                            >
                                                Max.
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
                                class="w-full cursor-pointer rounded-lg border border-primary-500 bg-primary-500 px-5 py-2 text-center text-xs font-medium text-background hover:bg-primary-700 focus:z-10 focus:ring-4 focus:ring-primary-200 focus:outline-none md:text-base dark:border-primary-600 dark:bg-primary-600"
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
