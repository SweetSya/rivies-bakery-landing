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

const { notivueSuccess, notivueError, notivueInfo } = useNotifications();
const { fetchAPI } = useAPI();

const isLoading = ref<boolean>(false);
const registerForm = ref({
    name: '',
    email: '',
    phone_number: '',
    password: '',
    confirm_password: '',
    errors: {} as Record<string, [string]>,
});

const handleRegister = async () => {
    // Registration logic here
    isLoading.value = true;
    console.log(registerForm.value);
    // Check password match
    if (registerForm.value.password !== registerForm.value.confirm_password) {
        notivueError('Konfirmasi password tidak sesuai.');
        registerForm.value.confirm_password = '';
        return;
    }

    const response = await fetchAPI('/register', {
        method: 'POST',
        data: registerForm.value,
    });
    if (response.status != 200) {
        if (response.status == 422) {
            Object.entries(response.data.errors).forEach(([key, messages]) => {
                registerForm.value.errors[key] = messages as [string];
            });
            notivueError('Form registrasi tidak valid. Silakan periksa kembali.');
        } else {
            registerForm.value.name = '';
            registerForm.value.email = '';
            registerForm.value.phone_number = '';
            registerForm.value.password = '';
            registerForm.value.confirm_password = '';
            notivueError(response.data.message || 'Terjadi kesalahan pada server.');
        }
        isLoading.value = false;
        return;
    }
    notivueSuccess('Registrasi berhasil, mengarahkan dalam beberapa detik!');
    setTimeout(() => {
        // Redirect to the desired page
        router.visit('/login');
        isLoading.value = false;
    }, 2000);

    isLoading.value = false;
};

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
                <title>Daftar</title>
                <meta name="description" content="Halaman daftar Rivies Bakery" />
            </Head>
        </template>

        <!-- Top Background -->
        <template #pageBackground>
            <img src="/storage/images/jumbotron/1.jpg" class="absolute h-full w-full object-cover brightness-50" />
        </template>
        <!-- Breadcrumb -->
        <template #pageBreadcrumb> </template>
        <!-- Title -->
        <template #pageTitle>Daftar sebagai <span class="rounded bg-primary-600 px-3 py-1 font-semibold">Pelanggan</span></template>
        <!-- Description -->
        <template #pageDescription>Gunakan kredensial yang dapat kamu ingat dengan baik</template>
        <!-- Content -->
        <template #content>
            <form @submit.prevent="handleRegister" class="mx-auto max-w-sm py-5">
                <div class="mb-5">
                    <label for="name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Nama Lengkap *</label>
                    <div>
                        <input
                            type="text"
                            id="name"
                            v-model="registerForm.name"
                            class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            placeholder=""
                            required
                        />
                        <p class="mt-2 text-xs text-red-600 md:text-sm dark:text-red-400">
                            {{ registerForm.errors.name ? registerForm.errors.name[0] : '' }}
                        </p>
                    </div>
                </div>
                <div class="mb-5">
                    <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Email *</label>
                    <div>
                        <input
                            type="email"
                            id="email"
                            v-model="registerForm.email"
                            class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            placeholder=""
                            required
                        />
                        <p class="mt-2 text-xs text-red-600 md:text-sm dark:text-red-400">
                            {{ registerForm.errors.email ? registerForm.errors.email[0] : '' }}
                        </p>
                    </div>
                </div>
                <div class="mb-5">
                    <label for="phone" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">No Telepon *</label>
                    <div class="relative">
                        <span class="absolute rounded-s-lg bg-primary-600 p-3 text-[12px] font-bold text-background">+62</span>
                        <input
                            type="text"
                            id="phone"
                            v-model="registerForm.phone_number"
                            class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 ps-14 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            placeholder=""
                            required
                        />
                        <p class="mt-2 text-xs text-red-600 md:text-sm dark:text-red-400">
                            {{ registerForm.errors.phone_number ? registerForm.errors.phone_number[0] : '' }}
                        </p>
                    </div>
                </div>
                <div class="mb-5">
                    <label for="password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input
                        type="password"
                        id="password"
                        v-model="registerForm.password"
                        class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                        placeholder=""
                        required
                    />
                    <p class="mt-2 text-xs text-red-600 md:text-sm dark:text-red-400">
                        {{ registerForm.errors.password ? registerForm.errors.password[0] : '' }}
                    </p>
                </div>
                <div class="mb-5">
                    <label for="confirm_password" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Konfirmasi Password</label>
                    <input
                        type="password"
                        v-model="registerForm.confirm_password"
                        class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                        placeholder=""
                        required
                    />
                    <p class="mt-2 text-xs text-red-600 md:text-sm dark:text-red-400">
                        {{ registerForm.errors.confirm_password ? registerForm.errors.confirm_password[0] : '' }}
                    </p>
                </div>
                <ButtonMain type="submit" :extendClass="'!w-full'" :isLoading="isLoading" :outline="true"> Daftar </ButtonMain>
                <p class="mt-6 text-center text-xs font-normal text-gray-500 md:text-base dark:text-gray-400">
                    Sudah memiliki akun pelanggan Rivie's Bakery?
                    <Link href="/login" class="text-primary-600 underline hover:opacity-80 dark:text-primary-500">Masuk disini</Link>
                </p>
            </form>
        </template>
    </AppLayout>
</template>
