<script setup lang="ts">
import AccountSettings from '@/layouts/AccountSettingsLayout.vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

// Register Library
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

defineOptions({
    components: {
        AccountSettings,
    },
});
</script>

<template>
    <AccountSettings>
        <!-- Title -->
        <template #settingsHeader>Informasi Akun</template>
        <!-- Description -->
        <template #settingsDescription>Kamu dapat melihat informasi akunmu beserta poin yang kamu dapatkan</template>
        <!-- Content -->
        <template #settingsContent
            ><div class="grid w-full grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <table class="w-full">
                        <tbody>
                            <tr class="border-b">
                                <td class="px-2 pt-3 pb-6" colspan="3">
                                    <img class="mx-auto aspect-square max-h-32 object-cover" src="/assets/images/logo.png" alt="" />
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="px-2 py-3 font-bold">Nama</td>
                                <td class="px-2">:</td>
                                <td class="px-2">Sultan Hakim Herrysan</td>
                                <td class="cursor-pointer px-2 text-primary-600 underline">Edit</td>
                            </tr>
                            <tr class="border-b">
                                <td class="px-2 py-3 font-bold">Email</td>
                                <td class="px-2">:</td>
                                <td class="px-2">herrysans12@gmail.com</td>
                                <td class="cursor-pointer px-2 text-primary-600 underline">Edit</td>
                            </tr>
                            <tr class="border-b">
                                <td class="px-2 py-3 font-bold">No Telepon</td>
                                <td class="px-2">:</td>
                                <td class="px-2">+6289677950614</td>
                                <td class="cursor-pointer px-2 text-primary-600 underline">Edit</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>
    </AccountSettings>
</template>
