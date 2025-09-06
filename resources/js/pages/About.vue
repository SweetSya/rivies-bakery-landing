<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Instagram, Linkedin } from 'lucide-vue-next';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

import { nextTick, onMounted, onUnmounted } from 'vue';

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
        new Swiper('.swiper-banner', {
            pagination: {
                el: '.swiper-pagination',
                type: 'fraction',
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });
    gsap.to('.horizontal-scroll', {
        scrollTrigger: {
            trigger: '.horizontal-scroll',
            start: 'center center',
            end: () => '+=' + ((document.querySelector('.horizontal-scroll') as HTMLElement | null)?.offsetWidth ?? 0),
            scrub: 1,
            pin: true,
        },
        xPercent: -70,
    });
    // Refresh ScrollTrigger on viewport change
    const refreshScrollTrigger = () => ScrollTrigger.refresh();
    window.addEventListener('resize', refreshScrollTrigger);
    window.addEventListener('orientationchange', refreshScrollTrigger);
    // Clean up listeners on unmount
    onUnmounted(() => {
        window.removeEventListener('resize', refreshScrollTrigger);
        window.removeEventListener('orientationchange', refreshScrollTrigger);
    });
    setTimeout(() => {
        refreshScrollTrigger();
    }, 500);
});

const teamMembers = [
    {
        id: 1,
        name: 'Rivina Nur Fahira',
        position: 'Owner',
        image: '/storage/images/logo.png',
        link: {
            instagram: 'https://www.instagram.com/johndoe',
            linkedin: 'https://www.linkedin.com/in/johndoe',
        },
    },
    {
        id: 2,
        name: 'Ainun',
        position: 'Baker',
        image: '/storage/images/logo.png',
        link: {
            instagram: 'https://www.instagram.com/johndoe',
            linkedin: 'https://www.linkedin.com/in/johndoe',
        },
    },
    {
        id: 3,
        name: 'Fara',
        position: 'Baker',
        image: '/storage/images/logo.png',
        link: {
            instagram: 'https://www.instagram.com/johndoe',
            linkedin: 'https://www.linkedin.com/in/johndoe',
        },
    },
];

const timelineCompany = [
    {
        id: 'tl1',
        year: 2016,
        event: {
            title: 'Awal Memulai',
            description:
                'Berawal dari dapur rumah sederhana, kami membuat roti dan kue untuk keluarga dan teman. Setiap adonan diuleni dengan cinta, menghadirkan aroma hangat yang membawa senyum di setiap gigitan.',
        },
        background: '/storage/images/jumbotron/1.jpg',
    },
    {
        id: 'tl2',
        year: 2020,
        event: {
            title: 'Hadir Online',
            description:
                'Kami mulai merangkul dunia digital, menjangkau lebih banyak pelanggan lewat media sosial dan layanan pesan antar, membawa cita rasa buatan rumah hingga ke pintu mereka.',
        },
        background: '/storage/images/jumbotron/2.jpg',
    },
    {
        id: 'tl3',
        year: 2022,
        event: {
            title: 'Toko Offline Pertama',
            description:
                'Toko pertama kami lahir, menjadi rumah bagi aroma roti hangat dan etalase kue segar. Tempat ini menjadi bagian dari cerita dan momen manis para pelanggan.',
        },
        background: '/storage/images/jumbotron/3.jpg',
    },
    {
        id: 'tl4',
        year: 2025,
        event: {
            title: 'Hingga Sekarang',
            description:
                'Kami terus berinovasi, menghadirkan rasa yang selalu mengingatkan pada kehangatan awal perjalanan kami, dan membagikannya ke lebih banyak hati.',
        },
        background: '/storage/images/jumbotron/1.jpg',
    },
];
</script>

<template>
    <AppLayout>
        <template #pageHead>
            <Head>
                <title>Tentang</title>
                <meta name="description" content="About page description" />
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
                        <span class="ms-1 text-sm font-medium text-base-100/70 md:ms-2">Tentang</span>
                    </div>
                </li>
            </ol>
        </template>
        <!-- Title -->
        <template #pageTitle>Tentang <span class="rounded bg-primary-600 px-3 py-1 font-semibold">Rivies Bakery</span></template>
        <!-- Description -->
        <template #pageDescription>Made with love and high quality ingredients</template>
        <!-- Content -->
        <template #content>
            <section class="quotes bg-transparent">
                <div class="mx-auto max-w-screen-xl px-4 py-8 lg:py-16">
                    <div class="w-full text-center">
                        <h1 class="mb-4 text-2xl leading-none font-extrabold tracking-tight md:text-5xl xl:text-6xl dark:text-white">
                            "Made with love and high quality ingredients"
                        </h1>
                        <p class="mb-6 font-light text-gray-500 md:text-lg lg:mb-8 lg:text-xl dark:text-gray-400">
                            Di dapur kami, setiap adonan dimulai dengan bahan-bahan segar dan berkualitas tinggi. Tepung terbaik, mentega harum, gula
                            lembut, hingga cokelat pilihan — semua dipilih dengan teliti. Lalu, dengan tangan penuh cinta, kami menguleni, memanggang,
                            dan menghiasnya dengan kesabaran. Hasilnya? Roti dan kue yang tidak hanya lezat, tapi juga membawa kehangatan di setiap
                            gigitan.
                        </p>
                    </div>
                    <img
                        class="logo mx-auto my-8 h-full rounded-full transition-none lg:my-16"
                        src="/storage/images/logo.png"
                        alt="dashboard image"
                    />
                    <ol class="horizontal-scroll relative z-0 flex min-h-[500px] w-[360vw] items-center transition-none md:w-[180vw]">
                        <li
                            v-for="item in timelineCompany"
                            :key="item.id"
                            class="relative mb-6 h-full sm:mb-0"
                            :class="'w-[' + 360 / timelineCompany.length + 'vw] md:w-[' + 180 / timelineCompany.length + 'vw]'"
                        >
                            <img :src="item.background" class="absolute -z-10 mt-8 h-full w-full object-cover brightness-50" alt="" />
                            <div class="flex items-center">
                                <div
                                    class="z-10 flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-primary-600 font-bold text-base-50 ring-0 ring-background sm:ring-8"
                                >
                                    {{ item.year }}
                                </div>
                                <div class="flex h-1 w-full bg-primary-500"></div>
                            </div>
                            <div class="3 mt-3 sm:pe-8">
                                <h3 class="mb-4 px-5 text-2xl font-bold text-primary-500 md:text-4xl">{{ item.event.title }}</h3>
                                <p class="px-5 text-base font-normal text-base-50 md:text-lg">
                                    {{ item.event.description }}
                                </p>
                            </div>
                        </li>
                    </ol>
                </div>
            </section>
            <section class="teams relative z-10 bg-transparent">
                <div class="bg mx-auto max-w-screen-xl rounded bg-background px-4 py-8 lg:px-6 lg:py-16">
                    <div class="w-full text-center">
                        <h1 class="mb-4 text-2xl leading-none font-extrabold tracking-tight md:text-5xl xl:text-6xl dark:text-white">Tim Rivies</h1>
                        <p class="mb-6 font-light text-gray-500 md:text-lg lg:mb-8 lg:text-xl dark:text-gray-400">
                            Kami adalah tim profesional pecinta roti, yang setiap harinya memanggang dengan hati dan menyajikan kebahagiaan di setiap
                            gigitan.
                        </p>
                    </div>
                    <div class="mb-6 grid gap-8 md:grid-cols-2 lg:mb-16">
                        <div v-for="item in teamMembers" :key="item.id" class="items-center rounded-lg border-b bg-background sm:flex">
                            <div>
                                <img
                                    class="w-full max-w-[206px] rounded-lg sm:rounded-none sm:rounded-l-lg"
                                    :src="item.image"
                                    :alt="`${item.name} Avatar`"
                                />
                            </div>
                            <div class="grow p-5">
                                <h3 class="font 4xl trackibold mb-2 text-2xl text-primary-700 md:font-bold dark:text-primary-500">
                                    <span>{{ item.name }}</span>
                                </h3>
                                <span class="text-gray-500 dark:text-gray-400">{{ item.position }}</span>
                                <ul class="mt-3 flex space-x-4">
                                    <li v-if="item.link.instagram">
                                        <a
                                            target="_blank"
                                            :href="item.link.instagram"
                                            class="text-gray-500 hover:text-gray-900 dark:hover:text-white"
                                        >
                                            <Instagram fill="#fff" class="h-5 w-5" />
                                        </a>
                                    </li>
                                    <li v-if="item.link.linkedin">
                                        <a target="_blank" :href="item.link.linkedin" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                                            <Linkedin fill="#fff" class="h-5 w-5" />
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </AppLayout>
</template>
