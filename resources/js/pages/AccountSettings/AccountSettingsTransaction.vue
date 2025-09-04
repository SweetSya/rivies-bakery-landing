<script setup lang="ts">
import BaseModal from '@/components/modal/BaseModal.vue';
import { useMidtrans } from '@/composables/useMidtrans';
import { useNotifications } from '@/composables/useNotifications';
import AccountSettings from '@/layouts/AccountSettingsLayout.vue';
import axios from 'axios';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ReceiptText } from 'lucide-vue-next';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import { ref } from 'vue';

// Register Library
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

const { notivueSuccess, notivueError, notivueInfo } = useNotifications();
const { pay } = useMidtrans();

defineOptions({
    components: {
        AccountSettings,
    },
});

const detailModal = ref<typeof BaseModal | null>(null);

const createSnapPayment = async () => {
    try {
        const response = await axios.get('/payment-midtrans');
        const { snap_token } = response.data;

        await pay(snap_token, {
            onSuccess: (result: any) => console.log(result),
            onPending: () => notivueInfo('Pembayaran sedang diproses.'),
            onError: () => notivueError('Pembayaran gagal.'),
            onClose: () => console.log('Closed'),
        });
    } catch (error) {
        console.error('Payment error:', error);
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
                <div v-for="value of [1, 2, 3]" :key="value" class="rounded-lg border bg-transparent p-4 ps-4">
                    <div class="flex items-start">
                        <div class="w-full space-y-2 text-sm">
                            <div
                                class="flex flex-wrap items-center justify-between border-b pb-2 text-base leading-none font-bold text-foreground md:text-lg"
                            >
                                <p>RVB201220250001</p>
                                <div class="rounded border border-primary-700 bg-primary-500/80 p-1 text-xs">Status</div>
                            </div>
                            <p class="mt-1 text-xs font-normal text-foreground/80 md:text-base">5 Jenis Barang</p>
                            <p class="mt-1 text-base font-bold text-foreground/80 md:text-lg">Rp 250.000</p>

                            <div class="flex flex-wrap justify-between gap-3">
                                <button
                                    @click="detailModal?.open()"
                                    class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80 md:text-base"
                                >
                                    <ReceiptText class="me-2 h-4 w-4" /> Detail Transaksi
                                </button>
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-red-600 underline hover:opacity-80 md:text-base dark:text-red-500"
                                    >
                                        Batalkan
                                    </button>
                                    <div>|</div>
                                    <button
                                        @click="createSnapPayment()"
                                        class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-green-600 underline hover:opacity-80 md:text-base"
                                    >
                                        Bayar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <BaseModal :id="'detail-modal'" :title="'Detail Transaksi'" :isCloseable="true" :isLoading="false" ref="detailModal">
                <template #icon>
                    <ReceiptText class="h-5 w-5" />
                </template>
                <template #content>
                    <div class="flex flex-col gap-3 text-xs md:text-base">
                        <table class="table-col-one-nowrap w-full">
                            <tbody class="">
                                <tr class="">
                                    <td class="px-2 py-1 font-bold">ID</td>
                                    <td class="px-2">:</td>
                                    <td class="px-2">RVB201220250001</td>
                                </tr>
                                <tr class="">
                                    <td class="px-2 py-1 font-bold">Total</td>
                                    <td class="px-2">:</td>
                                    <td class="px-2">Rp 250.000</td>
                                </tr>
                                <tr class="">
                                    <td class="px-2 py-1 font-bold">Metode</td>
                                    <td class="px-2">:</td>
                                    <td class="px-2">QRIS</td>
                                </tr>
                                <tr class="">
                                    <td class="px-2 py-1 font-bold">Status</td>
                                    <td class="px-2">:</td>
                                    <td class="px-2">Selesai</td>
                                </tr>
                                <tr class="">
                                    <td class="px-2 py-1 font-bold">Pembayaran /<br />Selesai</td>
                                    <td class="px-2">:</td>
                                    <td class="px-2">20 Desember 2025, 15:16 /<br />20 Desember 2025, 15:16</td>
                                </tr>
                                <tr class="">
                                    <td class="px-2 pt-3 text-center font-bold" colspan="3">Detail</td>
                                </tr>
                            </tbody>
                        </table>
                        <ul class="px-2">
                            <li class="w-full rounded border-y p-2">
                                <p class="font-semibold text-primary-600 dark:text-primary-400">lorem ipsum dolor sit amet</p>
                                <div class="flex justify-between">
                                    <span>2 * 50.000</span>
                                    <span>Rp 100.000</span>
                                </div>
                            </li>
                            <li class="w-full rounded border-y p-2 text-xs md:text-base">
                                <p class="font-semibold text-primary-600 dark:text-primary-400">lorem ipsum dolor sit amet</p>
                                <div class="flex justify-between">
                                    <span>2 * 50.000</span>
                                    <span>Rp 100.000</span>
                                </div>
                            </li>
                            <li class="w-full rounded border-y p-2 text-xs md:text-base">
                                <p class="font-semibold text-primary-600 dark:text-primary-400">lorem ipsum dolor sit amet</p>
                                <div class="flex justify-between">
                                    <span>2 * 50.000</span>
                                    <span>Rp 100.000</span>
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
