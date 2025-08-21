<script setup lang="ts">
import AccountSettings from '@/layouts/AccountSettingsLayout.vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { MapPinCheck, Plus, Trash } from 'lucide-vue-next';
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
        <template #settingsHeader>Daftar Alamat</template>
        <!-- Description -->
        <template #settingsDescription>Kamu dapat melihat mengelola alamatmu disini</template>
        <!-- Content -->
        <template #settingsContent>
            <div class="mb-8 flex justify-start">
                <button
                    class="flex cursor-pointer items-center justify-center gap-2 rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 focus:outline-none dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                >
                    Tambah Alamat Baru <Plus class="h-4 w-4" />
                </button>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div v-for="value of [1, 2, 3]" class="rounded-lg border bg-transparent p-4 ps-4">
                    <div class="flex items-start">
                        <div class="w-full space-y-2 text-sm">
                            <div class="border-b pb-2 text-base leading-none font-bold text-foreground md:text-lg">
                                Rumah <span class="text-primary-600">[Utama]</span>
                            </div>
                            <p class="mt-1 text-xs font-normal text-foreground/80 md:text-base">
                                Jl. Raya Ijen No.61, Mergelo, Wates, Kec. Magersari, Kota Mojokerto, Jawa Timur 61317
                            </p>
                            <div class="mt-1 flex items-center gap-2 text-xs font-normal text-foreground/80 md:text-base">
                                <MapPinCheck class="h-4 w-4" /> Sudah Pinpoint
                            </div>
                            <div class="flex flex-wrap justify-between gap-3">
                                <button
                                    class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80"
                                >
                                    <Trash class="me-2 h-4 w-4" /> Hapus
                                </button>
                                <div class="flex flex-wrap gap-3">
                                    <button
                                        class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80"
                                    >
                                        Ubah Alamat
                                    </button>
                                    <button
                                        class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80"
                                    >
                                        Jadikan Utama
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </AccountSettings>
</template>
