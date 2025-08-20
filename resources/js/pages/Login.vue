<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

// Register Library
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

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
                <title>Login</title>
                <meta name="description" content="Halaman login Rivies Bakery" />
            </Head>
        </template>

        <!-- Top Background -->
        <template #pageBackground>
            <img src="/storage/images/jumbotron/1.jpg" class="absolute h-full w-full object-cover brightness-50" />
        </template>
        <!-- Breadcrumb -->
        <template #pageBreadcrumb> </template>
        <!-- Title -->
        <template #pageTitle>Login <span class="rounded bg-primary-600 px-3 py-1 font-semibold">Rivies Bakery</span></template>
        <!-- Description -->
        <template #pageDescription>Gunakan kredensial yang sudah terdaftar dalam sistem untuk melanjutkan</template>
        <!-- Content -->
        <template #content>
            <form class="mx-auto py-5 max-w-sm">
                <div class="mb-5">
                    <label for="phone" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> No. Telepon / Email </label>
                    <div>
                        <input
                            type="text"
                            id="phone"
                            class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            placeholder=""
                            required
                        />
                    </div>
                </div>
                <div class="mb-5">
                    <label for="password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input
                        type="password"
                        class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                        placeholder=""
                        required
                    />
                </div>

                <div class="mb-5 flex items-start">
                    <div class="flex h-5 items-center">
                        <input
                            id="remember"
                            type="checkbox"
                            value=""
                            class="h-4 w-4 rounded-sm border border-gray-300 bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                            required
                        />
                    </div>
                    <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Remember me</label>
                </div>
                <button
                    type="submit"
                    class="w-full cursor-pointer rounded-lg border border-primary-500 bg-primary-500 px-5 py-2 text-center text-xs font-medium text-foreground hover:bg-primary-700 focus:z-10 focus:ring-4 focus:ring-primary-200 focus:outline-none md:text-base dark:border-primary-600 dark:bg-primary-600"
                >
                    Masuk
                </button>
            </form>
        </template>
    </AppLayout>
</template>
