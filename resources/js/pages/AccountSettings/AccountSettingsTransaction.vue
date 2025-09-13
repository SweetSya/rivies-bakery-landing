<script setup lang="ts">
import BaseModal from '@/components/modal/BaseModal.vue';
import { useAPI } from '@/composables/useAPI';
import { useConfirmation } from '@/composables/useConfirmation';
import { formatRupiah } from '@/composables/useHelperFunctions';
import { useMidtrans } from '@/composables/useMidtrans';
import { useNotifications } from '@/composables/useNotifications';
import AccountSettings from '@/layouts/AccountSettingsLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { CreditCard, ReceiptText, X } from 'lucide-vue-next';
import moment from 'moment';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import { ref } from 'vue';

// Register Library
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

const page = usePage();
const { fetchAPI } = useAPI();
const { notivueSuccess, notivueError, notivueInfo } = useNotifications();
const { pay } = useMidtrans();
const { showConfirmation } = useConfirmation();

defineOptions({
    components: {
        AccountSettings,
    },
});
type Order = {
    id: string;
    customer_id: string;
    invoice_number: string;
    order_details_count: number;
    status: string;
    payment?: any;
    order_details?: any[];
    total_amount: number;
    created_at: number;
};

const orders = ref<Order[]>((page.props.orders as Order[]) || []);
const detailModal = ref<typeof BaseModal | null>(null);
const detailModalOrder = ref<Order | null>(null);
const fetchLoading = ref(false);

const prepareDetailModal = async (invoice_number: string) => {
    fetchLoading.value = true;
    detailModal.value?.open();
    const response = await fetchAPI('/account-settings/transactions/detail', {
        method: 'POST',
        data: {
            invoice_number: invoice_number,
        },
    });
    detailModalOrder.value = response.data.order as Order;
    fetchLoading.value = false;
};
const closeDetailModal = () => {
    detailModalOrder.value = null;
};
const handleDeleteTransaction = (invoice_number: string) => {
    fetchLoading.value = true;
    showConfirmation({
        title: 'Hapus Transaksi',
        content: 'Apakah kamu yakin ingin membatalkan transaksi ini?',
        onConfirm: async () => {
            const response = await fetchAPI('/account-settings/transactions/cancel', {
                method: 'POST',
                data: {
                    invoice_number: invoice_number,
                },
            });
            if (response.status === 200) {
                notivueSuccess('Berhasil membatalkan transaksi');

                orders.value = orders.value.map((order) => {
                    if (order.invoice_number === invoice_number) {
                        return { ...order, status: 'cancelled' };
                    }
                    return order;
                });
            } else {
                // Handle validation errors
                if (response.data && response.data.errors) {
                    const errors = Object.values(response.data.errors).flat();
                    notivueError(errors.join(' '));
                } else {
                    notivueError('Terjadi kesalahan saat memperbarui transaksi');
                }
            }

            fetchLoading.value = false;
        },
        onCancel: () => {
            notivueInfo('Batal membatalkan transaksi');
        },
    });
};
const findSnapPayment = async (invoice_number: string) => {
    fetchLoading.value = true;
    try {
        const response = await fetchAPI('/account-settings/transactions/detail', {
            method: 'POST',
            data: {
                invoice_number: invoice_number,
                with_payment: true,
            },
        });
        const snap_token = response.data.order.payment.data.snap_token;
        if (!snap_token) {
            notivueError('Gagal mendapatkan token pembayaran, silahkan coba lagi.');
            return;
        }
        // Call Midtrans Snap payment
        await pay(snap_token, {
            onSuccess: (result: any) => notivueSuccess('Pembayaran berhasil.', result),
            onPending: (result: any) => (notivueInfo('Pembayaran sedang diproses.'), console.log(result)),
            onError: (result: any) => (notivueError('Pembayaran gagal.'), console.log(result)),
        });
    } catch (error) {
        console.error('Payment error:', error);
        notivueError('Gagal melakukan pembayaran, silahkan coba lagi.');
    } finally {
        fetchLoading.value = false;
    }
};
</script>

<template>
    <AccountSettings>
        <!-- Title -->
        <template #settingsHeader>Daftar Transaksi</template>
        <!-- Description -->
        <template #settingsDescription>Kamu dapat melihat daftar transaksimu baik yang sudah selesai dan belum disini</template>
        <!-- Content -->
        <template #settingsContent>
            <div class="mb-8 flex justify-start">
                <select
                    id="status"
                    class="block w-full rounded-lg border border-gray-300 bg-background p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                >
                    <option value="" selected>Semua Transaksi</option>
                    <option value="address1">Selesai</option>
                    <option value="address2">Proses</option>
                    <option value="address2">Menunggu Pembayaran</option>
                    <option value="address2">Batal</option>
                </select>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div v-for="order in orders" :key="order.id" class="rounded-lg border bg-transparent p-4 ps-4">
                    <div class="flex items-start">
                        <div class="w-full space-y-2 text-sm">
                            <div
                                class="flex flex-wrap items-center justify-between border-b pb-2 text-base leading-none font-bold text-foreground md:text-lg"
                            >
                                <p>{{ order.invoice_number }}</p>
                                <div
                                    :class="{
                                        'border-primary-500 bg-primary-500/30 text-primary-500': order.status === 'pending',
                                        'border-sky-500 bg-sky-500/30 text-sky-500': order.status === 'paid',
                                        'border-green-500 bg-green-500/30 text-green-500': order.status === 'completed',
                                        'border-gray-500 bg-gray-500/30 text-gray-500': order.status === 'cancelled',
                                    }"
                                    class="rounded border p-1 text-xs font-semibold"
                                >
                                    {{ order.status }}
                                </div>
                            </div>
                            <p class="mt-1 text-xs font-normal text-foreground/80 md:text-base">
                                {{ moment(order.created_at).format('DD/MM/YYYY HH:mm:ss') }}
                            </p>
                            <p class="mt-1 text-xs font-normal text-foreground/80 md:text-base">{{ order.order_details_count }} Barang</p>
                            <p class="mt-1 text-base font-bold text-foreground/80 md:text-lg">{{ formatRupiah(Math.round(order.total_amount)) }}</p>

                            <div class="flex flex-wrap justify-between gap-3">
                                <button
                                    @click="prepareDetailModal(order.invoice_number)"
                                    class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80 md:text-base"
                                >
                                    <div class="me-2">|</div>
                                    <ReceiptText class="me-2 h-4 w-4" /> Detail Transaksi
                                </button>
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        @click="handleDeleteTransaction(order.invoice_number)"
                                        v-if="['pending'].includes(order.status)"
                                        :class="{ 'cursor-not-allowed opacity-50': fetchLoading }"
                                        class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap underline hover:opacity-80 md:text-base"
                                    >
                                        <div class="me-2">|</div>
                                        <X class="me-2 h-4 w-4" /> Batalkan
                                    </button>

                                    <button
                                        v-if="['pending'].includes(order.status)"
                                        @click="findSnapPayment(order.invoice_number)"
                                        :class="{ 'cursor-not-allowed opacity-50': fetchLoading }"
                                        class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap underline hover:opacity-80 md:text-base"
                                    >
                                        <div class="me-2">|</div>
                                        <CreditCard class="me-2 h-4 w-4" /> Bayar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <BaseModal
                :id="'detail-modal'"
                :title="'Detail Transaksi'"
                :onClose="closeDetailModal"
                :isCloseable="true"
                :isLoading="fetchLoading"
                ref="detailModal"
            >
                <template #icon>
                    <ReceiptText class="h-5 w-5" />
                </template>
                <template #content>
                    <div class="flex flex-col gap-3 text-xs md:text-base">
                        <table class="table-col-one-nowrap w-full">
                            <tbody class="">
                                <tr class="">
                                    <td class="px-2 py-1 font-bold">Invoice</td>
                                    <td class="px-2">:</td>
                                    <td class="px-2">{{ detailModalOrder?.invoice_number }}</td>
                                </tr>
                                <tr class="">
                                    <td class="px-2 py-1 font-bold">Total</td>
                                    <td class="px-2">:</td>
                                    <td class="px-2">{{ detailModalOrder?.total_amount ? formatRupiah(detailModalOrder?.total_amount) : '' }}</td>
                                </tr>
                                <tr class="">
                                    <td class="px-2 py-1 font-bold">Metode</td>
                                    <td class="px-2">:</td>
                                    <td class="px-2">{{ detailModalOrder?.payment?.type }}</td>
                                </tr>
                                <tr class="">
                                    <td class="px-2 py-1 font-bold">Status</td>
                                    <td class="px-2">:</td>
                                    <td class="px-2">{{ detailModalOrder?.status }}</td>
                                </tr>
                                <!-- <tr class="">
                                    <td class="px-2 py-1 font-bold">Pembayaran /<br />Selesai</td>
                                    <td class="px-2">:</td>
                                    <td class="px-2">20 Desember 2025, 15:16 /<br />20 Desember 2025, 15:16</td>
                                </tr> -->
                                <tr class="">
                                    <td class="px-2 pt-3 text-center font-bold" colspan="3">Detail</td>
                                </tr>
                            </tbody>
                        </table>
                        <ul class="px-2">
                            <li
                                v-for="item in detailModalOrder?.order_details"
                                :key="item.id"
                                class="w-full rounded border-y p-2 text-xs md:text-base"
                            >
                                <div class="flex justify-between">
                                    <span class="flex items-center gap-2"
                                        >{{ item.quantity }} *
                                        <p class="font-semibold text-primary-600 dark:text-primary-400">{{ item.product_price.product.name }}</p>
                                    </span>
                                    <span>{{ formatRupiah(parseInt(item.subtotal)) }}</span>
                                </div>
                            </li>
                        </ul>
                        <div class="col-span-2 mt-5 flex justify-end gap-2">
                            <button
                                type="button"
                                @click="detailModal?.close()"
                                class="relative cursor-pointer overflow-hidden rounded-lg border-2 border-primary-500 bg-primary-600 px-5 py-2 text-center text-base text-foreground hover:opacity-80 focus:z-10 focus:ring-1 focus:ring-primary-300 focus:outline-none"
                            >
                                <div class="relative z-10">Tutup</div>
                            </button>
                        </div>
                    </div>
                </template>
            </BaseModal>
        </template>
    </AccountSettings>
</template>
