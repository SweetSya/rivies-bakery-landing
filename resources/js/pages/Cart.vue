<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import ProductCart from '@/components/carts/ProductCart.vue';
import { useCart } from '@/composables/useCart';
import { formatRupiah } from '@/composables/useHelperFunctions';
import { useNotifications } from '@/composables/useNotifications';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, X } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';

const { notivueSuccess, notivueError } = useNotifications();

const { getCart, isCartEmpty, applyCupon } = useCart();

defineOptions({
    components: {
        AppLayout,
    },
});

// Cupon
const cuponRef = ref<string>('');
const checkAndApplyCupon = () => {
    const cuponCode = cuponRef.value;
    if (cuponCode === 'DISCOUNT10') {
        const appliedCupon = {
            code: cuponCode,
            discount: 10,
            type: 'percentage',
        };
        applyCupon(appliedCupon);
    } else {
        notivueError('Kode kupon tidak valid.');
        clearAppliedCupon();
    }
};
onUnmounted(() => {
    // clearAppliedCupon();
});
onMounted(() => {
    // if(cart.value.cupon.code != '') {
    //     cuponRef.value = cart.value.cupon.code;
    // }
    clearAppliedCupon();
});
const clearAppliedCupon = () => {
    cuponRef.value = '';
    applyCupon({
        code: '',
        discount: 0,
        type: '',
    });
};
// Use computed to make it reactive
const cart = computed(() => getCart());
</script>

<template>
    <AppLayout>
        <template #pageHead>
            <Head>
                <title>Keranjang</title>
                <meta name="description" content="Halaman keranjang Rivies Bakery" />
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
                        <span class="ms-1 text-sm font-medium text-base-100/70 md:ms-2">Keranjang</span>
                    </div>
                </li>
            </ol>
        </template>
        <!-- Title -->
        <template #pageTitle>Keranjang <span class="rounded bg-primary-600 px-3 py-1 font-semibold">Pelanggan</span></template>
        <!-- Description -->
        <template #pageDescription>Made with love and high quality ingredients</template>
        <!-- Content -->
        <template #content>
            <section class="py-8 antialiased md:py-16">
                <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                    <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-8">
                        <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-4xl">
                            <div v-show="!isCartEmpty()" class="space-y-6">
                                <ProductCart
                                    v-for="item in cart.items"
                                    :key="item.id"
                                    :id="item.id"
                                    :name="item.name"
                                    :price="item.price"
                                    :image="item.image"
                                    :slug="item.slug"
                                    :discount="item.discount"
                                    :quantity="item.quantity"
                                    :status="item.status"
                                    :category="item.category"
                                />
                            </div>
                            <div>
                                <div v-show="isCartEmpty()" class="text-center text-gray-500">
                                    <p class="text-lg font-semibold">Keranjang Anda kosong</p>
                                    <p class="my-2">Tambahkan produk ke keranjang untuk memulai belanja.</p>
                                    <div class="flex items-center justify-center gap-2">
                                        <Link
                                            href="/products"
                                            title=""
                                            class="inline-flex items-center gap-2 text-base font-medium text-primary-700 underline hover:no-underline dark:text-primary-500"
                                        >
                                            Kunjungi halaman produk
                                            <ArrowRight class="h-4 w-4" />
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-full">
                            <div class="space-y-4 rounded-lg p-4 sm:p-6">
                                <form @submit.prevent="checkAndApplyCupon" class="space-y-4">
                                    <div>
                                        <label for="voucher" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                            Memiliki kode voucher atau kupon?
                                        </label>
                                        <input
                                            type="text"
                                            v-model="cuponRef"
                                            id="voucher"
                                            :class="cart.cupon.code != '' ? 'pointer-events-none opacity-50' : ''"
                                            class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                            placeholder=""
                                            required
                                        />
                                    </div>
                                    <div class="flex gap-2">
                                        <ButtonMain type="submit" :disabled="cart.cupon.code != ''" :extend-class="'!w-full !text-xs !py-2'">
                                            Terapkan Kode
                                        </ButtonMain>
                                        <ButtonMain
                                            v-if="cart.cupon.code != ''"
                                            type="button"
                                            :extend-class="'!w-fit !text-xs !py-2'"
                                            @click="(notivueSuccess('Berhasil membersihkan kode diskon'), clearAppliedCupon())"
                                        >
                                            <X class="h-5 w-5" />
                                        </ButtonMain>
                                    </div>
                                </form>
                            </div>
                            <div class="space-y-4 rounded-lg p-4 sm:p-6">
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">Rangkuman Pemesanan</p>

                                <div class="space-y-4">
                                    <div class="space-y-2">
                                        <dl class="flex items-center justify-between gap-4">
                                            <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Hemat</dt>
                                            <dd class="text-base font-medium" :class="cart.discount.product ? 'text-green-500' : 'text-foreground'">
                                                {{ formatRupiah(cart.discount.product) }}
                                            </dd>
                                        </dl>
                                        <dl v-if="cart.discount.cupon" class="flex items-center justify-between gap-4">
                                            <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Voucher</dt>
                                            <dd class="text-base font-medium" :class="cart.discount.cupon ? 'text-green-500' : 'text-foreground'">
                                                {{ formatRupiah(cart.discount.cupon) }}
                                            </dd>
                                        </dl>
                                        <dl class="flex items-center justify-between gap-4">
                                            <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Total</dt>
                                            <dd class="text-base font-medium text-gray-900 dark:text-white">
                                                {{ formatRupiah(cart.total - cart.discount.product - cart.discount.cupon) }}
                                            </dd>
                                        </dl>
                                        <!-- <dl class="flex items-center justify-between gap-4">
                                            <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Pengiriman</dt>
                                            <dd class="text-base font-medium text-gray-900 dark:text-white">Rp 99.000</dd>
                                        </dl> -->

                                        <dl class="flex items-center justify-between gap-4">
                                            <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Pajak</dt>
                                            <dd class="text-base font-medium text-gray-900 dark:text-white">
                                                {{ formatRupiah((cart.tax / 100) * (cart.total - (cart.discount.cupon + cart.discount.product))) }}
                                            </dd>
                                        </dl>
                                    </div>

                                    <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                                        <dt class="text-base font-bold text-gray-900 dark:text-white">Total Akhir</dt>
                                        <dd class="text-base font-bold text-gray-900 dark:text-white">{{ formatRupiah(cart.grandTotal) }}</dd>
                                    </dl>
                                </div>

                                <ButtonMain type="submit" :href="'/checkout'" :disabled="isCartEmpty()"> Proses Checkout </ButtonMain>

                                <div class="flex items-center justify-center gap-2">
                                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400"> atau </span>
                                    <Link
                                        href="/products"
                                        title=""
                                        class="inline-flex items-center gap-2 text-sm font-medium text-primary-700 underline hover:no-underline dark:text-primary-500"
                                    >
                                        Lanjutkan Belanja
                                        <ArrowRight class="h-4 w-4" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </AppLayout>
</template>
