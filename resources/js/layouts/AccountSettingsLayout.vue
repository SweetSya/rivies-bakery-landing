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
                    class="absolute -top-14 left-1/2 w-full -translate-x-1/2 rounded-lg bg-gradient-to-br from-primary-400 to-primary-500 p-3 backdrop-blur-lg"
                >
                    <div class="swiper swiper-section">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide !w-fit">
                                <Link
                                    href="/account-settings"
                                    :preserveScroll="true"
                                    class="text-xs md:text-base flex flex-nowrap items-center gap-3 rounded bg-primary-200/70 px-4 py-2 font-semibold text-nowrap text-foreground backdrop-blur-lg hover:scale-95 hover:opacity-90 dark:bg-primary-800/70"
                                    ><User class="h-5 w-5" /> Informasi Akun</Link
                                >
                            </div>
                            <div class="swiper-slide !w-fit">
                                <Link
                                    href="/account-settings/address"
                                    :preserveScroll="true"
                                    class="text-xs md:text-base flex flex-nowrap items-center gap-3 rounded bg-primary-200/70 px-4 py-2 font-semibold text-nowrap text-foreground backdrop-blur-lg hover:scale-95 hover:opacity-90 dark:bg-primary-800/70"
                                    ><MapPin class="h-5 w-5" /> Daftar Alamat</Link
                                >
                            </div>
                            <div class="swiper-slide !w-fit">
                                <Link
                                    href="/account-settings/transactions"
                                    :preserveScroll="true"
                                    class="text-xs md:text-base flex flex-nowrap items-center gap-3 rounded bg-primary-200/70 px-4 py-2 font-semibold text-nowrap text-foreground backdrop-blur-lg hover:scale-95 hover:opacity-90 dark:bg-primary-800/70"
                                    ><History class="h-5 w-5" /> Transaksi</Link
                                >
                            </div>
                            <div class="swiper-slide !w-fit">
                                <Link
                                    href="/account-settings/vouchers"
                                    :preserveScroll="true"
                                    class="text-xs md:text-base flex flex-nowrap items-center gap-3 rounded bg-primary-200/70 px-4 py-2 font-semibold text-nowrap text-foreground backdrop-blur-lg hover:scale-95 hover:opacity-90 dark:bg-primary-800/70"
                                    ><Percent class="h-5 w-5" /> Voucher</Link
                                >
                            </div>
                            <div class="swiper-slide !w-fit">
                                <Link
                                    href="/account-settings/change-password"
                                    :preserveScroll="true"
                                    class="text-xs md:text-base flex flex-nowrap items-center gap-3 rounded bg-primary-200/70 px-4 py-2 font-semibold text-nowrap text-foreground backdrop-blur-lg hover:scale-95 hover:opacity-90 dark:bg-primary-800/70"
                                    ><Lock class="h-5 w-5" /> Ubah Password</Link
                                >
                            </div>
                            <div class="swiper-slide !w-fit">
                                <Link
                                    href="/account-settings/email-verification"
                                    :preserveScroll="true"
                                    class="text-xs md:text-base flex flex-nowrap items-center gap-3 rounded bg-primary-200/70 px-4 py-2 font-semibold text-nowrap text-foreground backdrop-blur-lg hover:scale-95 hover:opacity-90 dark:bg-primary-800/70"
                                    ><Mail class="h-5 w-5" /> Verifikasi Email</Link
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="px-4 py-8 lg:py-12">
                <h2 class="mb-1 text-start text-lg xs:text-xl md:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white"><slot name="settingsHeader" /></h2>
                <p class="mb-6 text-start font-normal text-gray-500 text-base sm:text-lg lg:mb-10 dark:text-gray-400">
                    <slot name="settingsDescription" />
                </p>
                <div>
                    <slot name="settingsContent" />
                </div>
            </section>
        </template>
    </AppLayout>
</template>
