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
        <template #settingsHeader>Infromasi Diri</template>
        <!-- Description -->
        <template #settingsDescription>Kamu dapat melihat informasi data diri mu disini</template>
        <!-- Content -->
        <template #settingsContent
            ><div class="grid w-full grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <table class="w-full">
                        <tr class="border-b">
                            <td class="px-2 pt-3 pb-6" colspan="3">
                                <img class="mx-auto aspect-square max-h-32 object-cover" src="/assets/images/logo.png" alt="" />
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="px-2 py-3 font-bold">Nama</td>
                            <td class="px-2">:</td>
                            <td class="px-2">Sultan Hakim Herrysan</td>
                        </tr>
                        <tr class="border-b">
                            <td class="px-2 py-3 font-bold">Email</td>
                            <td class="px-2">:</td>
                            <td class="px-2">herrysans12@gmail.com</td>
                        </tr>
                        <tr class="border-b">
                            <td class="px-2 py-3 font-bold">No Telepon</td>
                            <td class="px-2">:</td>
                            <td class="px-2">+6289677950614</td>
                        </tr>
                    </table>
                </div>
            </div>
        </template>
    </AccountSettings>
</template>
