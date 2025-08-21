<script setup lang="ts">
import AccountSettings from '@/layouts/AccountSettingsLayout.vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Eye } from 'lucide-vue-next';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

// Register Library
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

defineOptions({
    components: {
        AccountSettings,
    },
});
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
                <div v-for="value of [1, 2, 3]" class="rounded-lg border bg-transparent p-4 ps-4">
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
                                    class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80"
                                >
                                    <Eye class="me-2 h-4 w-4" /> Detail Transaksi
                                </button>
                                <div class="flex flex-wrap gap-3">
                                    <button
                                        class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80"
                                    >
                                        Lakukan Pembayaran
                                    </button>
                                    <!-- <button
                                            class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80"
                                        >
                                            Cek Status
                                        </button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </AccountSettings>
</template>
