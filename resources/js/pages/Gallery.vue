<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { FolderClosed } from 'lucide-vue-next';

defineOptions({
    components: {
        AppLayout,
    },
});
</script>

<template>
    <AppLayout>
        <template #pageHead>
            <Head>
                <title>Galeri</title>
                <meta name="description" content="Gallery page description" />
            </Head>
        </template>

        <!-- Top Background -->
        <template #pageBackground>
            <img src="/storage/images/jumbotron/1.jpg" class="absolute h-full w-full object-cover brightness-50" />
        </template>
        <!-- Breadcrumb -->
        <template #pageBreadcrumb>
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <Link href="/" class="inline-flex items-center text-sm font-medium text-base-100 hover:text-primary">
                        <svg class="me-2.5 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
                            />
                        </svg>
                        Home
                    </Link>
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
                        <span class="ms-1 text-sm font-medium text-base-100/70 md:ms-2">Galeri</span>
                    </div>
                </li>
            </ol>
        </template>
        <!-- Title -->
        <template #pageTitle>Galeri <span class="rounded bg-primary-600 px-3 py-1 font-semibold text-nowrap">Rivies Bakery</span></template>
        <!-- Description -->
        <template #pageDescription>Made with love and high quality ingredients</template>
        <!-- Content -->
        <template #content>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <div class="group/folder relative cursor-pointer overflow-hidden rounded-lg border text-base-50">
                    <FolderClosed
                        class="absolute top-1/2 left-1/2 z-10 h-6 -translate-1/2 group-hover/folder:scale-150 group-hover/folder:rotate-12"
                    />

                    <p class="absolute top-1/2 left-1/2 z-10 mt-9 -translate-1/2 font-semibold opacity-0 group-hover/folder:opacity-80">
                        Lihat Koleksi
                    </p>
                    <div class="absolute bottom-0 left-0 z-10 w-full bg-gradient-to-t from-primary-700 to-primary-500 px-4 py-2 backdrop-blur-xs">
                        <p class="text-sm font-semibold text-base-100">Koleksi Pertama</p>
                    </div>
                    <img
                        class="h-auto max-w-full scale-110 rounded-lg object-cover brightness-75 group-hover/folder:scale-100"
                        src="/storage/images/jumbotron/1.jpg"
                        alt=""
                    />
                </div>
            </div>
        </template>
    </AppLayout>
</template>
