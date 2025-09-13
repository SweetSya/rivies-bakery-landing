<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import ProductCart from '@/components/carts/ProductCart.vue';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import { useAPI } from '@/composables/useAPI';
import { useCart } from '@/composables/useCart';
import { formatRupiah } from '@/composables/useHelperFunctions';
import { useNotifications } from '@/composables/useNotifications';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ArrowRight, LockKeyhole, ReceiptText, X } from 'lucide-vue-next';
import TomSelect from 'tom-select';
import { computed, nextTick, onMounted, ref } from 'vue';

defineOptions({ components: { AppLayout } });

const { notivueSuccess, notivueError } = useNotifications();
const { getCart, isCartEmpty, applyCupon, validateCart } = useCart();
const { fetchAPI } = useAPI();
const page = usePage();

const cart = computed(() => getCart());
const cuponRef = ref<string>('');
const pageLoad = ref(true);
const partialLoading = ref(false);
const downloadLoading = ref(false);
const cuponTomSelect = ref<TomSelect | null>(null);

// ✅ Download Draft
const downloadDraftCart = async () => {
    try {
        downloadLoading.value = true;
        const response = await fetchAPI('cart/download-draft', {
            method: 'POST',
            data: cart.value,
        });

        if (response.status === 200) {
            // Check if the response is PDF content
            const contentType = response.headers['content-type'];

            if (contentType && contentType.includes('application/pdf')) {
                // Handle PDF blob response
                const blob = new Blob([response.data], { type: 'application/pdf' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'Cart ' + new Date().toISOString() + '.pdf';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);

                notivueSuccess('Draft berhasil diunduh');
            }
        } else {
            notivueError('Gagal mengunduh draft.');
        }
    } catch (error) {
        console.error('Download error:', error);
        notivueError('Gagal mengunduh draft.');
    } finally {
        downloadLoading.value = false;
    }
};

// ✅ Coupon Handling
const hasCupon = computed(() => cart.value.cupon.code !== '');

const applyCuponCode = async () => {
    partialLoading.value = true;
    const code = cuponTomSelect.value?.getValue() as string;
    if (code) {
        const response = await fetchAPI('cart/apply-voucher', {
            method: 'POST',
            data: {
                cart: cart.value,
                code: code,
            },
        });
        if (response.status === 200 && response.data.valid) {
            applyCupon(response.data.cart.cupon);
            partialLoading.value = false;
            return;
        }
    }
    notivueError('Kode kupon tidak valid.');
    clearCupon();
    partialLoading.value = false;
};

const clearCupon = () => {
    cuponTomSelect.value?.clear();
    applyCupon({ id: '', code: '', discount: 0, type: '' });
};
const initializeTomSelectCupon = () => {
    cuponTomSelect.value = new TomSelect('#select-cupon', {
        valueField: 'id',
        labelField: 'title',
        searchField: ['title'],
        options: (page.props.vouchers as Array<{ id: string; title: string; code: string }> | []) || [],
        persist: false,
        create: false,
    });
};

// ✅ Totals
const productDiscount = computed(() => cart.value.discount.product || 0);
const cuponDiscount = computed(() => cart.value.discount.cupon || 0);
const totalAfterDiscounts = computed(() => cart.value.total);
const taxAmount = computed(() => (cart.value.tax / 100) * cart.value.total);
 
onMounted(async () => {
    applyCupon({ id: '', code: '', discount: 0, type: '' });
    await validateCart();
    pageLoad.value = false;
    nextTick(() => {
        initializeTomSelectCupon();
    });
});
</script>

<template>
    <AppLayout>
        <!-- Meta -->
        <template #pageHead>
            <Head>
                <title>Keranjang</title>
                <meta name="description" content="Halaman keranjang Rivies Bakery" />
            </Head>
        </template>

        <!-- Background -->
        <template #pageBackground>
            <img src="/storage/images/jumbotron/1.jpg" class="absolute h-full w-full object-cover brightness-50" />
        </template>

        <!-- Breadcrumb -->
        <template #pageBreadcrumb>
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li>
                    <Link href="/" class="inline-flex items-center text-sm font-medium text-base-100 hover:text-primary">
                        <svg class="me-2.5 h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
                            />
                        </svg>
                        Home
                    </Link>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="mx-1 h-3 w-3 text-base-100/50 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="ms-1 text-sm font-medium text-base-100/70 md:ms-2">Keranjang</span>
                    </div>
                </li>
            </ol>
        </template>

        <!-- Title & Description -->
        <template #pageTitle> Keranjang <span class="rounded bg-primary-600 px-3 py-1 font-semibold">Pelanggan</span> </template>
        <template #pageDescription>Made with love and high quality ingredients</template>

        <!-- Content -->
        <template #content>
            <div v-if="pageLoad" class="flex h-96 w-full items-center justify-center">
                <LoadingSpinner message="Memuat.." extend-class="!h-40 !w-40" />
            </div>

            <section v-else class="py-8 md:py-16">
                <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                    <div class="mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-6 xl:gap-8">
                        <!-- Cart Items -->
                        <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-4xl">
                            <div v-if="!isCartEmpty()" class="space-y-6">
                                <ProductCart v-for="item in cart.items" :key="item.id" :item="item" />
                            </div>
                            <div v-else class="text-center text-gray-500">
                                <p class="text-lg font-semibold">Keranjang Anda kosong</p>
                                <p class="my-2">Tambahkan produk ke keranjang untuk memulai belanja.</p>
                                <Link
                                    href="/products"
                                    class="inline-flex items-center gap-2 text-base font-medium text-primary-700 underline hover:no-underline dark:text-primary-500"
                                >
                                    Kunjungi halaman produk <ArrowRight class="h-4 w-4" />
                                </Link>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="relative mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-full">
                            <!-- Login Overlay -->
                            <div
                                v-if="!page.props.isAuthed"
                                class="absolute top-1/2 left-1/2 z-10 flex -translate-1/2 flex-col items-center gap-2 rounded-lg bg-background p-3 text-center text-foreground shadow"
                            >
                                <LockKeyhole class="h-10 w-10 !text-primary-600" />
                                <Link href="/login" class="text-sm underline">Harap login terlebih dahulu disini</Link>
                            </div>
                            <div class="mb-7 flex items-center justify-end gap-2 md:w-full md:px-6" v-if="!isCartEmpty()">
                                <ButtonMain
                                    id="download-draft-cart"
                                    type="button"
                                    :outline="true"
                                    :isLoading="downloadLoading"
                                    @click="downloadDraftCart"
                                    :disabled="downloadLoading"
                                    extend-class="!w-full"
                                >
                                    Unduh draft belanja <ReceiptText class="h-4 w-4" />
                                </ButtonMain>
                            </div>
                            <div
                                class="pointer-events-none opacity-70 blur-[1px]"
                                :class="{ '!pointer-events-auto !opacity-100 !blur-[0]': page.props.isAuthed }"
                            >
                                <!-- Coupon -->
                                <div class="space-y-4 rounded-lg p-4 sm:p-6">
                                    <form @submit.prevent="applyCuponCode" class="space-y-4">
                                        <label for="voucher" class="mb-2 block text-sm font-medium">Memiliki kode voucher atau kupon?</label>
                                        <!-- <input
                                            v-model="cuponRef"
                                            id="voucher"
                                            type="text"
                                            :disabled="hasCupon"
                                            class="block w-full rounded-lg border p-2.5 text-sm"
                                        /> -->
                                        <select id="select-cupon" placeholder="Cari Kupon.."></select>
                                        <div class="flex gap-2">
                                            <ButtonMain
                                                type="submit"
                                                :disabled="hasCupon"
                                                :is-loading="partialLoading"
                                                extend-class="!w-full !text-xs !py-2"
                                            >
                                                Terapkan Kode
                                            </ButtonMain>
                                            <ButtonMain v-if="hasCupon" type="button" @click="clearCupon" extend-class="!w-fit !text-xs !py-2">
                                                <X class="h-5 w-5" />
                                            </ButtonMain>
                                        </div>
                                    </form>
                                </div>

                                <!-- Summary -->
                                <div class="space-y-4 rounded-lg p-4 sm:p-6">
                                    <p class="text-xl font-semibold">Rangkuman Pemesanan</p>

                                    <dl class="flex items-center justify-between">
                                        <dt>Hemat</dt>
                                        <dd :class="productDiscount ? 'text-green-500' : ''">{{ formatRupiah(productDiscount) }}</dd>
                                    </dl>
                                    <dl v-if="cuponDiscount" class="flex items-center justify-between">
                                        <dt>Voucher</dt>
                                        <dd class="text-green-500">{{ formatRupiah(cuponDiscount) }}</dd>
                                    </dl>
                                    <dl class="flex items-center justify-between">
                                        <dt>Total</dt>
                                        <dd>{{ formatRupiah(totalAfterDiscounts) }}</dd>
                                    </dl>
                                    <dl class="flex items-center justify-between">
                                        <dt>Pajak</dt>
                                        <dd>{{ formatRupiah(taxAmount) }}</dd>
                                    </dl>
                                    <dl class="flex items-center justify-between border-t pt-2">
                                        <dt class="font-bold">Total Akhir</dt>
                                        <dd class="font-bold">{{ formatRupiah(cart.grandTotal) }}</dd>
                                    </dl>

                                    <ButtonMain type="submit" :href="'/checkout'" :disabled="isCartEmpty()"> Proses Checkout </ButtonMain>
                                </div>
                            </div>

                            <!-- Continue Shopping -->
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-sm">atau</span>
                                <Link
                                    href="/products"
                                    class="inline-flex items-center gap-2 text-sm font-medium text-primary-700 underline dark:text-primary-500"
                                >
                                    Lanjutkan Belanja <ArrowRight class="h-4 w-4" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </AppLayout>
</template>
