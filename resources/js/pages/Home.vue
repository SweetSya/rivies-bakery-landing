<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Megaphone, SquareArrowOutUpRight } from 'lucide-vue-next';
import { onMounted } from 'vue';

defineOptions({
    components: {
        AppLayout,
    },
});

onMounted(() => {});
</script>

<template>
    <AppLayout>
        <Head>
            <title>Home</title>
            <meta name="description" content="Home page description" />
        </Head>

        <!-- Top Background -->
        <template #pageBackground>
            <div
                class="absolute h-full w-full bg-black/50 bg-[url('/placeholder-assets/storage/jumbotron/bg3.jpg')] bg-cover bg-no-repeat bg-blend-darken"
            ></div>
        </template>
        <!-- Breadcrumb -->
        <template #pageBreadcrumb>
            <!-- <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="#" class="inline-flex items-center text-sm font-medium text-base-100 hover:text-primary">
                        <svg class="me-2.5 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
                            />
                        </svg>
                        Home
                    </a>
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
                        <span class="ms-1 text-sm font-medium text-base-100/70 md:ms-2">Current</span>
                    </div>
                </li>
            </ol> -->
        </template>
        <!-- Title -->
        <template #pageTitle>
            <div class="text-nowrap">Selamat Datang di,</div>
            <div class="mt-2 w-fit rounded bg-gradient-to-r from-primary-500 to-secondary-500 p-2 text-nowrap">KONI Ketapang</div>
        </template>
        <!-- Description -->
        <template #pageDescription> </template>
        <!-- Content -->
        <template #content>
            <div class="mx-auto mb-10 -mt-20 rounded-lg border-border bg-gradient-to-br from-primary-400 to-primary-600 p-6 shadow-sm">
                <div class="flex gap-5 pb-4">
                    <Megaphone class="mt-1 h-6 w-6 text-base-50" />

                    <span class="text-2xl font-semibold tracking-tight text-base-50">Pengumuman</span>
                </div>
                <p class="mb-3 font-normal text-base-50/90">Go to this step by step guideline process on how to certify for your weekly benefits:</p>
                <Link href="#" class="inline-flex items-center font-medium text-base-50 underline hover:opacity-80">
                    Lihat disini selengkapnya
                    <SquareArrowOutUpRight class="ms-1 -mt-2 h-4 w-4" />
                </Link>
            </div>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
                <div>
                    <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image.jpg" alt="" />
                </div>
                <div>
                    <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-1.jpg" alt="" />
                </div>
                <div>
                    <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-2.jpg" alt="" />
                </div>
                <div>
                    <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-3.jpg" alt="" />
                </div>
                <div>
                    <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-4.jpg" alt="" />
                </div>
                <div>
                    <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-5.jpg" alt="" />
                </div>
                <div>
                    <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-6.jpg" alt="" />
                </div>
                <div>
                    <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-7.jpg" alt="" />
                </div>
                <div>
                    <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-8.jpg" alt="" />
                </div>
                <div>
                    <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-9.jpg" alt="" />
                </div>
                <div>
                    <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-10.jpg" alt="" />
                </div>
                <div>
                    <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-11.jpg" alt="" />
                </div>
            </div>
        </template>
    </AppLayout>
</template>
