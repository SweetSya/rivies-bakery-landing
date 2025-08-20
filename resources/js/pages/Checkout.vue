<script setup lang="ts">
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import { useAppearance } from '@/composables/useAppearance';
import { useCart } from '@/composables/useCart';
import { useCheckout } from '@/composables/useCheckout';
import { formatRupiah } from '@/composables/useHelperFunctions';
import { useNotifications } from '@/composables/useNotifications';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Modal, ModalInterface, ModalOptions } from 'flowbite';
import { ArrowRight, CreditCard } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';

const { appearance } = useAppearance();
const { notivueSuccess } = useNotifications();

const { getCart, resetCart, isCartEmpty } = useCart();
const { getCheckout, isCheckoutEmpty, resetCheckout } = useCheckout();

const selectAddress = ref(<string>'');

defineOptions({
    components: {
        AppLayout,
    },
});

// Use computed to make it reactive
const checkout = computed(() => getCheckout());
const cart = computed(() => getCart());
const loadingPayment = ref(false);

// Hold to cancel
const holdToCancel = ref(false);
const holdToCancelProgress = ref(0);
const handleHoldToCancel = (initiate: boolean) => {
    if (initiate) {
        holdToCancel.value = true;
        holdToCancelProgress.value = 0;
    }
    setTimeout(() => {
        if (!holdToCancel.value) {
            holdToCancelProgress.value = 0;
            return null;
        }
        holdToCancelProgress.value += 100;
        if (holdToCancelProgress.value >= 2200) {
            holdToCancel.value = false;
            // Reset progress
            holdToCancelProgress.value = 0;
            notivueSuccess('Pembayaran dibatalkan');
            paymentModal.value?.hide();
            return;
        }
        handleHoldToCancel(false);
    }, 100);
};

// Create payment
const createPayment = () => {
    // Show payment modal
    paymentModal.value?.show();
    loadingPayment.value = true;

    holdToCancel.value = false;
    holdToCancelProgress.value = 0;

    // Payment api request here
    setTimeout(() => {
        loadingPayment.value = false;
        // Reset checkout
        resetCheckout();
        resetCart();
    }, 2000);
};
const paymentModal = ref<ModalInterface | null>(null);
onMounted(() => {
    const modalElement = document.getElementById('payment-modal');

    if (modalElement) {
        const modalOptions: ModalOptions = {
            backdrop: 'static',
        };

        paymentModal.value = new Modal(modalElement, modalOptions);
    }
});

onUnmounted(() => {
    resetCheckout();
});
</script>

<template>
    <AppLayout>
        <template #pageHead>
            <Head>
                <title>Checkout</title>
                <meta name="description" content="Halaman checkout Rivies Bakery" />
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
                        <span class="ms-1 text-sm font-medium text-base-100/70 md:ms-2">Checkout</span>
                    </div>
                </li>
            </ol>
        </template>
        <!-- Title -->
        <template #pageTitle>Checkout <span class="rounded bg-primary-600 px-3 py-1 font-semibold">Keranjang</span></template>
        <!-- Description -->
        <template #pageDescription>Made with love and high quality ingredients</template>
        <!-- Content -->
        <template #content>
            <section class="py-8 antialiased md:py-16">
                <div v-show="isCartEmpty()">
                    <div class="text-center text-gray-500">
                        <p class="text-lg font-semibold">Keranjang Anda kosong</p>
                        <p class="my-2">Tambahkan produk ke keranjang untuk memulai belanja disini.</p>
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
                <div v-show="!isCartEmpty() || !isCheckoutEmpty()" class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                    <div class="mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12 xl:gap-16">
                        <div class="min-w-0 flex-1 space-y-8">
                            <div class="space-y-4">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Detail Pengiriman</h2>

                                <select
                                    id="countries"
                                    v-model="selectAddress"
                                    class="block w-full rounded-lg border border-gray-300 bg-background p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                >
                                    <option value="" selected>Pilih alamat..</option>
                                    <option value="address1">Alamat 1</option>
                                    <option value="address2">Alamat 2</option>
                                </select>
                                <p
                                    v-show="![''].includes(selectAddress)"
                                    class="mt-1 text-xs font-normal text-gray-500 md:text-base dark:text-gray-400"
                                >
                                    Ditambahkan secara otomatis berdasarkan data yang ada, untuk mengubah alamat harap
                                    <Link href="/account" class="text-primary-600 underline">klik disini</Link>
                                </p>
                                <div class="grid grid-cols-1 gap-4 overflow-hidden sm:grid-cols-2" v-show="selectAddress !== ''">
                                    <div>
                                        <label for="name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Nama Lengkap </label>
                                        <input
                                            v-model="checkout.fullName"
                                            type="text"
                                            id="name"
                                            class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                            placeholder=""
                                            required
                                        />
                                    </div>

                                    <div>
                                        <label for="name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Email </label>
                                        <input
                                            v-model="checkout.email"
                                            type="email"
                                            id="email"
                                            class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                            placeholder=""
                                            required
                                        />
                                    </div>

                                    <div class="col-span-2">
                                        <label for="address" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                            Alamat Lengkap
                                        </label>
                                        <textarea
                                            v-model="checkout.address"
                                            name="address"
                                            id="address"
                                            rows="4"
                                            class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-semibold text-foreground">Metode Pembayaran</h3>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <label for="pay-with-qris" class="cursor-pointer rounded-lg border bg-transparent p-4 ps-4">
                                        <div class="flex items-start">
                                            <div class="flex h-5 items-center">
                                                <input
                                                    id="pay-with-qris"
                                                    aria-describedby="pay-on-delivery-text"
                                                    type="radio"
                                                    name="payment-method"
                                                    v-model="checkout.payment.method"
                                                    value="QRIS"
                                                    class="h-4 w-4 bg-background text-primary-600 focus:ring-2 focus:ring-primary-600"
                                                />
                                            </div>

                                            <div class="ms-4 text-sm">
                                                <div class="leading-none font-medium text-gray-900 dark:text-white">QRIS</div>
                                                <p id="pay-on-delivery-text" class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400">
                                                    Tidak ada biaya tambahan
                                                </p>
                                            </div>
                                        </div>
                                    </label>

                                    <label for="pay-with-manual" class="cursor-pointer rounded-lg border bg-transparent p-4 ps-4">
                                        <div class="flex items-start">
                                            <div class="flex h-5 items-center">
                                                <input
                                                    id="pay-with-manual"
                                                    aria-describedby="pay-on-delivery-text"
                                                    type="radio"
                                                    name="payment-method"
                                                    value=""
                                                    class="h-4 w-4 bg-background text-primary-600 focus:ring-2 focus:ring-primary-600"
                                                />
                                            </div>

                                            <div class="ms-4 text-sm">
                                                <div class="leading-none font-medium text-gray-900 dark:text-white">Manual (Transfer / Cash)</div>
                                                <p id="pay-on-delivery-text" class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400">
                                                    Tidak ada tambahan
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Metode Pengambilan</h3>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <label for="delivery-with-pickup" class="cursor-pointer rounded-lg border bg-transparent p-4 ps-4">
                                        <div class="flex items-start">
                                            <div class="flex h-5 items-center">
                                                <input
                                                    id="delivery-with-pickup"
                                                    aria-describedby="pay-on-delivery-text"
                                                    type="radio"
                                                    name="delivery-method"
                                                    v-model="checkout.delivery.method"
                                                    value="PICKUP"
                                                    class="h-4 w-4 bg-background text-primary-600 focus:ring-2 focus:ring-primary-600"
                                                />
                                            </div>

                                            <div class="ms-4 text-sm">
                                                <div class="leading-none font-medium text-gray-900 dark:text-white">Di Toko</div>
                                                <p id="pay-on-delivery-text" class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400">
                                                    Tidak ada tambahan
                                                </p>
                                            </div>
                                        </div>
                                    </label>

                                    <label for="delivery-with-gosend" class="cursor-pointer rounded-lg border bg-transparent p-4 ps-4">
                                        <div class="flex items-start">
                                            <div class="flex h-5 items-center">
                                                <input
                                                    id="delivery-with-gosend"
                                                    aria-describedby="pay-on-delivery-text"
                                                    type="radio"
                                                    name="delivery-method"
                                                    v-model="checkout.delivery.method"
                                                    value="GOSEND"
                                                    class="h-4 w-4 bg-background text-primary-600 focus:ring-2 focus:ring-primary-600"
                                                />
                                            </div>

                                            <div class="ms-4 text-sm">
                                                <div class="leading-none font-medium text-gray-900 dark:text-white">GoSend</div>
                                                <p id="pay-on-delivery-text" class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400">
                                                    Biaya menyesuaikan jarak
                                                </p>
                                            </div>
                                        </div>
                                    </label>

                                    <label for="delivery-with-other" class="cursor-pointer rounded-lg border bg-transparent p-4 ps-4">
                                        <div class="flex items-start">
                                            <div class="flex h-5 items-center">
                                                <input
                                                    id="delivery-with-other"
                                                    aria-describedby="pay-on-delivery-text"
                                                    type="radio"
                                                    name="delivery-method"
                                                    v-model="checkout.delivery.method"
                                                    value="OTHER"
                                                    class="h-4 w-4 bg-background text-primary-600 focus:ring-2 focus:ring-primary-600"
                                                />
                                            </div>

                                            <div class="ms-4 text-sm">
                                                <div class="leading-none font-medium text-gray-900 dark:text-white">Lainnya (JNE,JNT,dll)</div>
                                                <p id="pay-on-delivery-text" class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400">
                                                    Biaya menyesuaikan jarak
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div v-show="checkout.delivery.method === 'PICKUP'" class="space-y-3">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Estimasi Pengambilan</h2>
                                <p class="mt-1 text-xs font-normal text-gray-500 md:text-base dark:text-gray-400">
                                    Jika tidak terisi, estimasi akan diatur secara otomatis berdasarkan jam buka toko dan dikonfirmasi melalui nomor
                                    telepon yang terdaftar.
                                </p>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="relative col-span-2 w-full md:col-span-1">
                                        <input
                                            id="default-datepicker"
                                            type="datetime-local"
                                            class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 scheme-light focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:scheme-dark dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                            placeholder="Select date"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 w-full space-y-6 sm:mt-8 lg:mt-0 lg:max-w-xs xl:max-w-md">
                            <p class="text-xl font-semibold text-gray-900 dark:text-white">Rangkuman Pemesanan</p>

                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Total</dt>
                                        <dd class="text-base font-medium text-gray-900 dark:text-white">
                                            {{ formatRupiah(cart.total - cart.discount.product - cart.discount.cupon) }}
                                        </dd>
                                    </dl>

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

                            <button
                                @click="createPayment"
                                :class="isCheckoutEmpty() || isCartEmpty() ? 'pointer-events-none opacity-50' : ''"
                                class="flex w-full cursor-pointer items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 focus:outline-none dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                            >
                                Proses Pembayaran
                            </button>

                            <div class="flex items-center justify-center gap-2">
                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400"> atau </span>
                                <Link
                                    href="/cart"
                                    title=""
                                    class="inline-flex items-center gap-2 text-sm font-medium text-primary-700 underline hover:no-underline dark:text-primary-500"
                                >
                                    Kembali ke Keranjang
                                    <ArrowRight class="h-4 w-4" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div
                id="payment-modal"
                data-modal-backdrop="static"
                tabindex="-1"
                class="fixed top-0 right-0 left-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-x-hidden overflow-y-auto p-4 md:inset-0"
            >
                <div class="relative max-h-full w-full max-w-xl">
                    <!-- Modal content -->
                    <div class="relative rounded-lg bg-background shadow-md shadow-foreground/10">
                        <!-- Modal header -->

                        <div
                            class="flex items-center justify-between rounded-t border-b border-gray-200 bg-primary-600 p-4 md:p-5 dark:border-gray-600"
                        >
                            <h3 class="flex items-center gap-4 text-xl font-medium text-foreground">
                                <CreditCard class="h-5 w-5" /> Proses Pembayaran
                            </h3>
                        </div>
                        <!-- Modal body -->
                        <LoadingSpinner :extendClass="'h-40'" :message="'Sedang membuat pembayaran...'" v-show="loadingPayment" />
                        <div v-show="!loadingPayment" class="flex flex-col items-center justify-center space-y-1 p-4 md:p-5">
                            <!-- <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Metode Pembayaran</h2> -->
                            <img :src="`/assets/images/qris-logo-${appearance}.png`" class="h-16 rounded object-contain md:h-24" alt="QRIS" />
                            <p class="mt-1 text-center text-xs font-normal text-gray-500 md:text-base dark:text-gray-400">
                                Harap lakukan pembayaran sebelum
                                <span class="text-primary-600 dark:text-primary-500">{{ new Date().toLocaleString() }}</span> untuk menghindari
                                pembatalan
                            </p>
                            <div class="flex flex-col items-center justify-center space-y-2 rounded p-3">
                                <p class="text-xl font-bold">Rivies Bakery</p>
                                <div class="rounded-lg border-4 border-primary-500 shadow-lg shadow-foreground/10">
                                    <img src="/storage/images/qris.png" class="rounded" alt="QRIS" />
                                </div>

                                <p class="text-xl font-bold">{{ formatRupiah(350000) }}</p>
                                <p
                                    class="text-xl font-bold underline"
                                    :class="{
                                        'text-primary-500': ['', 'pending', 'unpaid'].includes(checkout.payment.status ?? 'unpaid'),
                                        'text-green-500': checkout.payment.status === 'paid',
                                    }"
                                >
                                    Menunggu pembayaran
                                </p>
                                <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Pengecekan dalam 5 detik..</p>
                            </div>
                            <p class="mt-1 text-center text-xs font-normal text-gray-500 md:text-base dark:text-gray-400">
                                Untuk melihat seluruh riwayat pembayaran, silahkan kunjungi
                                <Link href="/payment-history" class="text-primary-600 underline hover:opacity-80 dark:text-primary-500"
                                    >halaman ini</Link
                                >
                            </p>
                        </div>
                        <!-- Modal footer -->
                        <div v-show="!loadingPayment" class="flex items-center justify-between space-x-2 rounded-b px-4 pb-4 md:px-5 md:pb-5">
                            <button
                                type="button"
                                @click="paymentModal?.hide()"
                                class="relative cursor-pointer overflow-hidden rounded-lg border-2 border-primary-500 bg-primary-600 px-5 py-2 text-center text-base text-foreground hover:opacity-80 focus:z-10 focus:ring-1 focus:ring-primary-300 focus:outline-none"
                            >
                                <div class="relative z-10">Tutup</div>
                            </button>
                            <button
                                type="button"
                                @mousedown="handleHoldToCancel(true)"
                                @mouseleave="holdToCancel = false"
                                @mouseup="holdToCancel = false"
                                @touchstart="handleHoldToCancel(true)"
                                @touchend="holdToCancel = false"
                                :class="holdToCancel ? 'bg-transparent' : ''"
                                class="relative cursor-pointer overflow-hidden rounded-lg border-2 border-red-500 bg-red-500 px-5 py-2 text-center text-base text-foreground hover:opacity-80 focus:z-10 focus:ring-1 focus:ring-red-300 focus:outline-none"
                            >
                                <div class="relative z-10">Batalkan pesanan (Tahan)</div>
                                <div
                                    :style="{ width: `${(holdToCancelProgress / 2000) * 100}%` }"
                                    class="absolute top-0 left-0 -z-0 h-full bg-red-500"
                                ></div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </AppLayout>
</template>
