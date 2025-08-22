<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { useCart } from '@/composables/useCart';
import { useCheckout } from '@/composables/useCheckout';
import { formatRupiah } from '@/composables/useHelperFunctions';
import { useNotifications } from '@/composables/useNotifications';
import { Link } from '@inertiajs/vue3';
import { CreditCard } from 'lucide-vue-next';
import { ref } from 'vue';
import BaseModal from './BaseModal.vue';

const { appearance } = useAppearance();
const { notivueSuccess } = useNotifications();

const { getCart, resetCart, isCartEmpty } = useCart();
const { getCheckout, isCheckoutEmpty, resetCheckout } = useCheckout();
export type Payment = {
    id: string;
    amount: number;
    method: string;
    status: 'pending' | 'paid' | 'unpaid' | 'cancel';
};

// Use computed to make it reactive
const payment = ref<Payment | null>(null);
const loadingPayment = ref(false);

// Create payment
const createPayment = () => {
    // Fetch Data from backend (Mock for now)
    const mockCheckoutData = <Payment>{
        id: '1234567890',
        amount: 350000,
        method: 'QRIS',
        status: 'pending',
    };
    payment.value = mockCheckoutData;
    // Show payment modal
    modal.value?.open();
    loadingPayment.value = true;

    // Payment api request here
    setTimeout(() => {
        loadingPayment.value = false;
    }, 2000);
};

const modal = ref<InstanceType<typeof BaseModal> | null>(null);
defineExpose({
    createPayment,
    modal,
});
</script>

<template>
    <BaseModal :id="'payment-modal'" static="static" :is-loading="loadingPayment" :title="'Proses Pembayaran'" ref="modal">
        <template #modalIcon>
            <CreditCard class="h-5 w-5" />
        </template>
        <template #modalContent>
            <img :src="`/assets/images/qris-logo-${appearance}.png`" class="mx-auto h-16 rounded object-contain md:h-24" alt="QRIS" />
            <p class="mx-5 mt-1 text-center text-xs font-normal text-gray-500 md:text-base dark:text-gray-400">
                Harap lakukan pembayaran sebelum
                <span class="text-primary-600 dark:text-primary-500">{{ new Date().toLocaleString() }}</span> untuk menghindari pembatalan
            </p>
            <div v-if="payment?.method === 'QRIS'" class="flex flex-col items-center justify-center space-y-2 rounded p-3">
                <p class="text-lg font-bold md:text-xl">Rivies Bakery</p>
                <div class="rounded-lg border-4 border-primary-500 shadow-lg shadow-foreground/10">
                    <img src="/assets/images/qris.png" class="max-h-40 rounded md:max-h-52" alt="QRIS" />
                </div>

                <p class="text-center text-lg font-bold md:text-xl">{{ formatRupiah(payment?.amount) }}</p>
                <p
                    class="text-center text-base font-bold underline md:text-xl"
                    :class="{
                        'text-primary-500': ['', 'pending', 'unpaid'].includes(payment?.status ?? 'unpaid'),
                        'text-green-500': payment?.status === 'paid',
                    }"
                >
                    Menunggu pembayaran
                </p>
                <p class="text-center text-sm font-normal text-base-400">Pengecekan dalam 5 detik..</p>
            </div>
            <p class="mx-5 mt-1 text-center text-xs font-normal text-gray-500 md:text-base dark:text-gray-400">
                Untuk melihat seluruh riwayat pembayaran, silahkan kunjungi
                <Link href="/account-settings/transactions" class="text-primary-600 underline hover:opacity-80 dark:text-primary-500">halaman ini</Link>
            </p>

            <!-- Modal footer -->
            <div class="flex items-center justify-between space-x-2 rounded-b pt-5">
                <button
                    type="button"
                    @click="modal?.close()"
                    class="relative cursor-pointer overflow-hidden rounded-lg border-2 border-primary-500 bg-primary-600 px-5 py-2 text-center text-base text-foreground hover:opacity-80 focus:z-10 focus:ring-1 focus:ring-primary-300 focus:outline-none"
                >
                    <div class="relative z-10">Tutup</div>
                </button>
            </div>
        </template>
    </BaseModal>
</template>
