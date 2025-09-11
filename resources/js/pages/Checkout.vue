<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import { useCart } from '@/composables/useCart';
import { useCheckout } from '@/composables/useCheckout';
import { formatRupiah } from '@/composables/useHelperFunctions';
import { useMidtrans } from '@/composables/useMidtrans';
import { useNotifications } from '@/composables/useNotifications';
import AppLayout from '@/layouts/AppLayout.vue';
import PaymentSuccessAnimation from '@/lotties/payment-success.json';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { LottieAnimation } from 'lottie-web-vue';
import { ArrowRight } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';

const { notivueSuccess, notivueError, notivueInfo } = useNotifications();
const { pay } = useMidtrans();

const { getCart, resetCart, isCartEmpty, validateCart } = useCart();
const { getCheckout, isCheckoutEmpty, resetCheckout, setAddress } = useCheckout();

const page = usePage();
type Address = {
    id: string;
    label: string;
    recipientName: string;
    phoneNumber: string;
    fullAddress: string;
    isMain: boolean;
    hasPinpoint: boolean;
    pinpointLocation?: {
        lat: number | null;
        lng: number | null;
    };
};

defineOptions({
    components: {
        AppLayout,
    },
});

// Use computed to make it reactive
const checkout = computed(() => getCheckout());
const cart = computed(() => getCart());
const pageLoad = ref(true);
const snapPaymentLoading = ref(false);
const afterPaymentCompleteAnimation = ref(false);
const addressList = ref<Address[]>([]);

const findAddressById = (id: string) => {
    return addressList.value.find((addr) => addr.id === id) || null;
};
const handleAddressChange = (event: Event) => {
    const target = event.target as HTMLSelectElement;
    const selectedId = target.value;
    const address = findAddressById(selectedId);
    if (address) {
        setAddress(address);
    } else {
        setAddress(null);
    }
};
// Create payment
const createSnapPayment = async () => {
    // check payment method
    if (checkout.value.payment.method === 'online') {
        // Save to DB and prefetch
        snapPaymentLoading.value = true;
        try {
            const response = await axios.get('/payment-midtrans');
            const { snap_token } = response.data;
            // Save snap token to DB

            await pay(snap_token, {
                onSuccess: (result: any) => (
                    router.push({
                        url: `?payment-completed=true&order-id=${result.order_id}`,
                    }),
                    afterPaymentComplete()
                ),
                onPending: (result: any) => (notivueInfo('Pembayaran sedang diproses.'), console.log(result)),
                onError: (result: any) => (notivueError('Pembayaran gagal.'), console.log(result)),
                onClose: () => (resetCart(), resetCheckout()),
            });
            snapPaymentLoading.value = false;
        } catch (error) {
            console.error('Payment error:', error);
        }
    } else {
        // Handle other payment methods
        notivueInfo('Silahkan tunjukkan pembayaran di tempat.');
        resetCart();
        resetCheckout();
    }
};
const afterPaymentComplete = () => {
    notivueSuccess('Pembayaran berhasil.');
    resetCart();
    resetCheckout();
    afterPaymentCompleteAnimation.value = true;
};
onMounted(async () => {
    const valid = await validateCart();
    if (!valid) {
        router.visit('/cart');
        return;
    }
    addressList.value = page.props.auth.user.addresses || [];
    //Check params
    const params = new URLSearchParams(window.location.search);
    if (params.has('payment-completed')) {
        if (params.get('payment-completed') === 'true') {
            const orderId = params.get('order-id') || '';
            if (orderId) {
                // Check DB
                // Success
                afterPaymentComplete();
            }
        }
    }
    pageLoad.value = false;
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
            <div class="flex h-96 w-full items-center justify-center" v-if="pageLoad">
                <LoadingSpinner message="Memuat.." extend-class="!h-40 !w-40" />
            </div>
            <section v-if="!pageLoad" class="py-8 antialiased md:py-16">
                <div class="mb-10 flex flex-col items-center justify-center" v-show="afterPaymentCompleteAnimation">
                    <h2 class="text-xl font-semibold text-primary-500 md:text-4xl">Pembayaran Berhasil</h2>
                    <p class="text-center text-base-500">Pihak kami akan mengkonfirmasi pesananmu melalui nomor yang ada</p>
                    <LottieAnimation
                        v-if="afterPaymentCompleteAnimation"
                        :animation-data="PaymentSuccessAnimation"
                        :loop="true"
                        :auto-play="true"
                        :speed="1"
                        class="h-70"
                    />
                </div>
                <div v-show="isCartEmpty()">
                    <div class="text-center text-base-500">
                        <p class="text-lg font-semibold">Keranjangmu kosong</p>
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
                <div v-show="!isCartEmpty()" class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                    <div class="mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12 xl:gap-16">
                        <div class="min-w-0 flex-1 space-y-8">
                            <div class="space-y-4">
                                <h3 class="text-xl font-semibold text-foreground">Metode Pembayaran</h3>

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <label for="pay-with-online" class="cursor-pointer rounded-lg border bg-transparent p-4 ps-4">
                                        <div class="flex items-start">
                                            <div class="flex h-5 items-center">
                                                <input
                                                    id="pay-with-online"
                                                    aria-describedby="pay-on-delivery-text"
                                                    type="radio"
                                                    name="payment-method"
                                                    v-model="checkout.payment.method"
                                                    value="online"
                                                    class="h-4 w-4 bg-background text-primary-600 focus:ring-2 focus:ring-primary-600"
                                                />
                                            </div>

                                            <div class="ms-4 text-sm">
                                                <div class="leading-none font-medium text-base-900 dark:text-white">Online</div>
                                                <p id="pay-on-delivery-text" class="mt-1 text-xs font-normal text-base-500 dark:text-base-400">
                                                    Pembayaran secara online melalui payment gateway
                                                </p>
                                            </div>
                                        </div>
                                    </label>

                                    <label for="pay-with-offline" class="cursor-pointer rounded-lg border bg-transparent p-4 ps-4">
                                        <div class="flex items-start">
                                            <div class="flex h-5 items-center">
                                                <input
                                                    id="pay-with-offline"
                                                    aria-describedby="pay-on-delivery-text"
                                                    type="radio"
                                                    name="payment-method"
                                                    v-model="checkout.payment.method"
                                                    value="offline"
                                                    class="h-4 w-4 bg-background text-primary-600 focus:ring-2 focus:ring-primary-600"
                                                />
                                            </div>

                                            <div class="ms-4 text-sm">
                                                <div class="leading-none font-medium text-base-900 dark:text-white">Offline (Manual)</div>
                                                <p id="pay-on-delivery-text" class="mt-1 text-xs font-normal text-base-500 dark:text-base-400">
                                                    Pembayaran dengan konfirmasi melalui pihak Rivie's Bakery
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <h3 class="text-xl font-semibold text-base-900 dark:text-white">Metode Pengambilan</h3>

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
                                                <div class="leading-none font-medium text-base-900 dark:text-white">Di Toko</div>
                                                <p id="pay-on-delivery-text" class="mt-1 text-xs font-normal text-base-500 dark:text-base-400">
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
                                                <div class="leading-none font-medium text-base-900 dark:text-white">GoSend</div>
                                                <p id="pay-on-delivery-text" class="mt-1 text-xs font-normal text-base-500 dark:text-base-400">
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
                                                <div class="leading-none font-medium text-base-900 dark:text-white">Lainnya (JNE,JNT,dll)</div>
                                                <p id="pay-on-delivery-text" class="mt-1 text-xs font-normal text-base-500 dark:text-base-400">
                                                    Biaya menyesuaikan jarak
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div v-show="['OTHER', 'GOSEND'].includes(checkout.delivery.method)" class="space-y-4">
                                <h2 class="text-xl font-semibold text-base-900 dark:text-white">Detail Pengiriman</h2>

                                <select
                                    id="countries"
                                    @change="handleAddressChange($event)"
                                    class="block w-full rounded-lg border border-base-300 bg-background p-2.5 text-sm text-base-900 focus:border-primary-500 focus:ring-primary-500 dark:border-base-600 dark:text-white dark:placeholder:text-base-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                >
                                    <option value="" selected>Pilih alamat..</option>
                                    <option v-for="value in addressList" :key="value.id" :value="value.id">
                                        {{ value.label }} - {{ value.recipientName }}
                                    </option>
                                </select>
                                <p v-show="checkout.address != null" class="mt-1 text-xs font-normal text-base-500 md:text-base dark:text-base-400">
                                    Ditambahkan secara otomatis berdasarkan data yang ada, untuk mengubah alamat harap
                                    <Link href="/account-settings/address" class="text-primary-600 underline">klik disini</Link>
                                </p>
                                <div class="grid gap-4 overflow-hidden md:grid-cols-2" v-show="checkout.address?.id !== ''">
                                    <div>
                                        <label for="name" class="mb-2 block text-sm font-medium text-base-900 dark:text-white"> Nama Penerima </label>
                                        <input
                                            :value="checkout.address?.recipientName"
                                            type="text"
                                            id="name"
                                            disabled
                                            class="block w-full rounded-lg border border-base-300 bg-transparent p-2.5 text-sm text-base-900 focus:border-primary-500 focus:ring-primary-500 dark:border-base-600 dark:text-white dark:placeholder:text-base-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                            placeholder=""
                                            required
                                        />
                                    </div>

                                    <div>
                                        <label for="name" class="mb-2 block text-sm font-medium text-base-900 dark:text-white">
                                            No Telp. Penerima
                                        </label>
                                        <input
                                            :value="checkout.address?.phoneNumber"
                                            type="tel"
                                            id="phone"
                                            disabled
                                            class="block w-full rounded-lg border border-base-300 bg-transparent p-2.5 text-sm text-base-900 focus:border-primary-500 focus:ring-primary-500 dark:border-base-600 dark:text-white dark:placeholder:text-base-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                            placeholder=""
                                            required
                                        />
                                    </div>

                                    <div class="col-span-2">
                                        <label for="address" class="mb-2 block text-sm font-medium text-base-900 dark:text-white">
                                            Alamat Lengkap
                                        </label>
                                        <textarea
                                            :value="checkout.address?.fullAddress"
                                            name="address"
                                            id="address"
                                            rows="4"
                                            disabled
                                            class="block w-full rounded-lg border border-base-300 bg-transparent p-2.5 text-sm text-base-900 focus:border-primary-500 focus:ring-primary-500 dark:border-base-600 dark:text-white dark:placeholder:text-base-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div v-show="checkout.delivery.method === 'PICKUP'" class="space-y-3">
                                <h2 class="text-lg font-semibold text-base-900 dark:text-white">Estimasi Pengambilan</h2>
                                <p class="mt-1 text-xs font-normal text-base-500 md:text-base dark:text-base-400">
                                    Jika tidak terisi, estimasi akan diatur secara otomatis berdasarkan jam buka toko dan dikonfirmasi melalui nomor
                                    telepon yang terdaftar.
                                </p>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="relative col-span-2 w-full md:col-span-1">
                                        <input
                                            id="default-datepicker"
                                            type="datetime-local"
                                            class="block w-full rounded-lg border border-base-300 bg-transparent p-2.5 text-sm text-base-900 scheme-light focus:border-primary-500 focus:ring-primary-500 dark:border-base-600 dark:text-white dark:scheme-dark dark:placeholder:text-base-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                            placeholder="Select date"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 w-full space-y-6 sm:mt-8 lg:mt-0 lg:max-w-xs xl:max-w-md">
                            <p class="text-xl font-semibold text-base-900 dark:text-white">Rangkuman Pemesanan</p>

                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-base-500 dark:text-base-400">Hemat</dt>
                                        <dd class="text-base font-medium" :class="cart.discount.product ? 'text-green-500' : 'text-foreground'">
                                            {{ formatRupiah(cart.discount.product) }}
                                        </dd>
                                    </dl>
                                    <dl v-if="cart.discount.cupon" class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-base-500 dark:text-base-400">Voucher</dt>
                                        <dd class="text-base font-medium" :class="cart.discount.cupon ? 'text-green-500' : 'text-foreground'">
                                            {{ formatRupiah(cart.discount.cupon) }}
                                        </dd>
                                    </dl>
                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-base-500 dark:text-base-400">Total</dt>
                                        <dd class="text-base font-medium text-base-900 dark:text-white">
                                            {{ formatRupiah(cart.total - cart.discount.product - cart.discount.cupon) }}
                                        </dd>
                                    </dl>
                                    <!-- <dl class="flex items-center justify-between gap-4">
                                            <dt class="text-base font-normal text-base-500 dark:text-base-400">Pengiriman</dt>
                                            <dd class="text-base font-medium text-base-900 dark:text-white">Rp 99.000</dd>
                                        </dl> -->

                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-base-500 dark:text-base-400">Pajak</dt>
                                        <dd class="text-base font-medium text-base-900 dark:text-white">
                                            {{ formatRupiah((cart.tax / 100) * (cart.total - (cart.discount.cupon + cart.discount.product))) }}
                                        </dd>
                                    </dl>
                                </div>

                                <dl class="flex items-center justify-between gap-4 border-t border-base-200 pt-2 dark:border-base-700">
                                    <dt class="text-base font-bold text-base-900 dark:text-white">Total Akhir</dt>
                                    <dd class="text-base font-bold text-base-900 dark:text-white">{{ formatRupiah(cart.grandTotal) }}</dd>
                                </dl>
                            </div>
                            <ButtonMain
                                type="submit"
                                @click="createSnapPayment"
                                :extendClass="'!w-full'"
                                :disabled="isCheckoutEmpty() || isCartEmpty()"
                                :isLoading="snapPaymentLoading"
                            >
                                Proses Pembayaran
                            </ButtonMain>

                            <div class="flex items-center justify-center gap-2">
                                <span class="text-sm font-normal text-base-500 dark:text-base-400"> atau </span>
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
        </template>
    </AppLayout>
</template>
