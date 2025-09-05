<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import { nextTick, onMounted, ref } from 'vue';

// Register Library
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

const links = [
    { name: 'Informasi Akun', href: '/account-settings', icon: 'User', active: false, nonac: false },
    { name: 'Daftar Alamat', href: '/account-settings/address', icon: 'map-pin', active: false, nonac: false },
    { name: 'Transaksi', href: '/account-settings/transactions', icon: 'history', active: false, nonac: false },
    { name: 'Voucher', href: '#', icon: 'percent', active: false, nonac: true },
    { name: 'Ubah Password', href: '#', icon: 'lock', active: false, nonac: true },
    { name: 'Verifikasi Email', href: '#', icon: 'mail', active: false, nonac: true },
];

const linskRef = ref(links);

const activeLink = (href: string) => {
    let foundKey = -1; // Initialize with -1 to indicate "not found"

    linskRef.value = linskRef.value.map((link, key) => {
        const isActive = link.href === href;
        if (isActive) {
            foundKey = key; // Store the key when we find the active link
        }
        return {
            ...link,
            active: isActive,
        };
    });

    return foundKey; // Return the key of the active link
};

defineOptions({
    components: {
        AppLayout,
    },
});

onMounted(() => {
    nextTick(() => {
        const activeKey = activeLink(window.location.pathname);
        const swiper = new Swiper('.swiper-section', {
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
                            <div v-for="value in linskRef" :key="value.name" class="swiper-slide !w-fit">
                                <Link
                                    :href="value.href"
                                    :preserveScroll="true"
                                    :class="{
                                        'pointer-events-none line-through opacity-50': value.nonac,
                                        'border-b-2 border-background': value.active,
                                    }"
                                    class="flex flex-nowrap items-center gap-3 rounded bg-primary-800/70 px-4 py-2 text-xs font-semibold text-nowrap text-background backdrop-blur-lg hover:scale-95 hover:opacity-90 md:text-base dark:bg-primary-200/70"
                                >
                                    {{ value.name }}</Link
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
