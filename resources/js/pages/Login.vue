<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import { useAPI } from '@/composables/useAPI';
import { useNotifications } from '@/composables/useNotifications';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import { ref } from 'vue';

// Register Library
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

const { fetchAPI } = useAPI();
const { notivueError, notivueSuccess } = useNotifications();

defineOptions({
    components: {
        AppLayout,
    },
});

const isLoading = ref<boolean>(false);
const form = ref<{
    email: string;
    password: string;
    remember: boolean;
}>({
    email: '',
    password: '',
    remember: false,
});

const handleLogin = async () => {
    isLoading.value = true;
    const response = await fetchAPI('/login', {
        method: 'POST',
        data: form.value,
    });
    if (response.status === 200) {
        notivueSuccess('Login berhasil, mengarahkan pengguna!');
        // Redirect to the desired page
        router.visit('/products');
        isLoading.value = false;
    } else {
        // Handle login error
        form.value.password = '';
        notivueError('Gagal masuk, silahkan cek kembali kredensial');
        isLoading.value = false;
    }
};
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
        <template #pageTitle>Masuk sebagai <span class="rounded bg-primary-600 px-3 py-1 font-semibold">Pelanggan</span></template>
        <!-- Description -->
        <template #pageDescription>Gunakan kredensial yang sudah terdaftar dalam sistem untuk melanjutkan</template>
        <!-- Content -->
        <template #content>
            <form class="mx-auto max-w-sm py-5" @submit.prevent="handleLogin">
                <div class="mb-5">
                    <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> No. Telepon / Email </label>
                    <div>
                        <input
                            type="email"
                            id="email"
                            v-model="form.email"
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
                        v-model="form.password"
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
                            v-model="form.remember"
                            value=""
                            class="h-4 w-4 rounded-sm border border-border bg-background checked:border-primary-500 checked:bg-primary-500 focus:ring-3 focus:ring-primary-300 dark:border-border dark:bg-background dark:ring-offset-background dark:checked:border-primary-600 dark:checked:bg-primary-600 dark:focus:ring-primary-600 dark:focus:ring-offset-background"
                        />
                    </div>
                    <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ingat perangkat</label>
                </div>
                <ButtonMain type="submit" :extendClass="'!w-full'" :isLoading="isLoading" :disabled="!form.email || !form.password" :outline="true">
                    Masuk
                </ButtonMain>
                <p class="mt-6 text-center text-xs font-normal text-gray-500 md:text-base dark:text-gray-400">
                    Belum memiliki akun pelanggan di Rivie's Bakery?
                    <Link href="/register" class="text-primary-600 underline hover:opacity-80 dark:text-primary-500">Daftar disini</Link>
                </p>
            </form>
        </template>
    </AppLayout>
</template>
