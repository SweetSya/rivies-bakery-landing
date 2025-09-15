<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import PaymentSuccessAnimation from '@/lotties/payment-success.json';
import { Head } from '@inertiajs/vue3';
import { LottieAnimation } from 'lottie-web-vue';
import { Link } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

defineOptions({
    components: {
        AppLayout,
    },
});

const paymentStatus = ref<'success' | 'failed' | 'pending' | null>(null);
const callbackUrl = ref<string | null>(null);
onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('state');
    if (status === 'success' || status === 'failed' || status === 'pending') {
        paymentStatus.value = status;
    } else {
        paymentStatus.value = 'failed'; // Default to failed if status is invalid
    }
    const callback = urlParams.get('callback');
    if (callback) {
        callbackUrl.value = callback;
    }
});
</script>

<template>
    <AppLayout>
        <template #pageHead>
            <Head>
                <title>Status Pembayaran</title>
                <meta name="description" content="Halaman status pembayaran Rivies Bakery" />
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
                        <span class="ms-1 text-sm font-medium text-base-100/70 md:ms-2">Checkout</span>
                    </div>
                </li>
            </ol>
        </template>
        <!-- Title -->
        <template #pageTitle>Status <span class="rounded bg-primary-600 px-3 py-1 font-semibold">Pembayaran</span></template>
        <!-- Description -->
        <template #pageDescription>Made with love and high quality ingredients</template>
        <!-- Content -->
        <template #content>
            <div class="my-16 flex flex-col items-center justify-center space-y-4" v-show="paymentStatus === 'success'">
                <h2 class="text-xl font-semibold text-primary-500 md:text-4xl">Pembayaran Berhasil</h2>
                <p class="text-center text-base-500">Pihak kami akan mengkonfirmasi pesananmu melalui nomor yang ada</p>
                <LottieAnimation
                    v-if="paymentStatus"
                    :animation-data="PaymentSuccessAnimation"
                    :loop="true"
                    :auto-play="true"
                    :speed="1"
                    class="h-70"
                />
                <ButtonMain :href="callbackUrl ?? '/product'" :outline="true" :extend-class="'!px-6 !py-2 !text-sm'" type="button">
                    Kembali
                </ButtonMain>
            </div>
        </template>
    </AppLayout>
</template>
