<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { History, Lock, Mail, MapPin, Percent, User } from 'lucide-vue-next';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import { nextTick, onMounted } from 'vue';


// Register Library
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

defineOptions({
    components: {
        AppLayout,
    },
});
onMounted(() => {
    nextTick(() => {
        new Swiper('.swiper-section', {
            slidesPerView: 'auto',
            spaceBetween: '15px',
        });
    });
});
</script>

<template>
    <AppLayout>
        <template #pageHead>
            <Head>
                <title>Akun</title>
                <meta name="description" content="Halaman akun Rivies Bakery" />
            </Head>
        </template>

        <!-- Top Background -->
        <template #pageBackground>
            <img src="/storage/images/jumbotron/1.jpg" class="absolute h-full w-full object-cover brightness-50" />
        </template>
        <!-- Breadcrumb -->
        <template #pageBreadcrumb> </template>
        <!-- Title -->
        <template #pageTitle>Pengaturan <span class="rounded bg-primary-600 px-3 py-1 font-semibold">Akun Pelanggan</span></template>
        <!-- Description -->
        <template #pageDescription>Halo {Nama}, kamu dapat mengatur akunmu pada Rivie's Bakery disini</template>
        <!-- Content -->
        <template #content>
            <section class="relative mx-4">
                <div
                    class="absolute -top-14 left-1/2 w-full -translate-x-1/2 rounded-lg bg-gradient-to-br from-primary-600 to-primary-500 p-3 backdrop-blur-lg"
                >
                    <div class="swiper swiper-section">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide !w-fit">
                                <Link
                                    href="/account-settings"
                                    :preserveScroll="true"
                                    class="flex flex-nowrap items-center gap-3 rounded bg-primary-800/70 px-4 py-2 text-xs font-semibold text-nowrap text-background backdrop-blur-lg hover:scale-95 hover:opacity-90 md:text-base dark:bg-primary-200/70"
                                    ><User class="h-5 w-5" /> Informasi Akun</Link
                                >
                            </div>
                            <div class="swiper-slide !w-fit">
                                <Link
                                    href="/account-settings/address"
                                    :preserveScroll="true"
                                    class="flex flex-nowrap items-center gap-3 rounded bg-primary-800/70 px-4 py-2 text-xs font-semibold text-nowrap text-background backdrop-blur-lg hover:scale-95 hover:opacity-90 md:text-base dark:bg-primary-200/70"
                                    ><MapPin class="h-5 w-5" /> Daftar Alamat</Link
                                >
                            </div>
                            <div class="swiper-slide !w-fit">
                                <Link
                                    href="/account-settings/transactions"
                                    :preserveScroll="true"
                                    class="flex flex-nowrap items-center gap-3 rounded bg-primary-800/70 px-4 py-2 text-xs font-semibold text-nowrap text-background backdrop-blur-lg hover:scale-95 hover:opacity-90 md:text-base dark:bg-primary-200/70"
                                    ><History class="h-5 w-5" /> Transaksi</Link
                                >
                            </div>
                            <div class="swiper-slide !w-fit line-through opacity-50">
                                <Link
                                    href="#"
                                    :preserveScroll="true"
                                    class="flex flex-nowrap items-center gap-3 rounded bg-primary-800/70 px-4 py-2 text-xs font-semibold text-nowrap text-background backdrop-blur-lg hover:scale-95 hover:opacity-90 md:text-base dark:bg-primary-200/70"
                                    ><Percent class="h-5 w-5" /> Voucher</Link
                                >
                            </div>
                            <div class="swiper-slide !w-fit line-through opacity-50">
                                <Link
                                    href="#"
                                    :preserveScroll="true"
                                    class="flex flex-nowrap items-center gap-3 rounded bg-primary-800/70 px-4 py-2 text-xs font-semibold text-nowrap text-background backdrop-blur-lg hover:scale-95 hover:opacity-90 md:text-base dark:bg-primary-200/70"
                                    ><Lock class="h-5 w-5" /> Ubah Password</Link
                                >
                            </div>
                            <div class="swiper-slide !w-fit line-through opacity-50">
                                <Link
                                    href="#"
                                    :preserveScroll="true"
                                    class="flex flex-nowrap items-center gap-3 rounded bg-primary-800/70 px-4 py-2 text-xs font-semibold text-nowrap text-background backdrop-blur-lg hover:scale-95 hover:opacity-90 md:text-base dark:bg-primary-200/70"
                                    ><Mail class="h-5 w-5" /> Verifikasi Email</Link
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="px-4 py-8 lg:py-12">
                <h2 class="mb-1 text-start text-lg font-extrabold tracking-tight text-gray-900 xs:text-xl md:text-3xl dark:text-white">
                    <slot name="settingsHeader" />
                </h2>
                <p class="mb-6 text-start text-base font-normal text-gray-500 sm:text-lg lg:mb-10 dark:text-gray-400">
                    <slot name="settingsDescription" />
                </p>
                <div>
                    <slot name="settingsContent" />
                </div>
            </section>
        </template>
    </AppLayout>
</template>
