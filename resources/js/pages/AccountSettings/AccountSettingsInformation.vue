<script setup lang="ts">
import { useAPI } from '@/composables/useAPI';
import AccountSettings from '@/layouts/AccountSettingsLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
// Register Library
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

const page = usePage();

const { getStorage } = useAPI();
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
                            <tr class="">
                                <td class="px-2 pt-3 pb-6" colspan="3">
                                    <img
                                        class="mx-auto aspect-square max-h-32 rounded object-cover"
                                        :src="getStorage(page.props.auth.user.profile_picture ?? '../assets/placeholder/image.png')"
                                        alt=""
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <table class="h-full w-full">
                        <tbody>
                            <tr class="border-b">
                                <td class="px-2 py-3 font-bold">Nama</td>
                                <td class="px-2">:</td>
                                <td class="px-2">{{ page.props.auth.user.name }}</td>
                                <td class="cursor-pointer px-2 text-primary-600 underline">Edit</td>
                            </tr>
                            <tr class="border-b">
                                <td class="px-2 py-3 font-bold">Email</td>
                                <td class="px-2">:</td>
                                <td class="px-2">{{ page.props.auth.user.email }}</td>
                                <td class="cursor-pointer px-2 text-primary-600 underline">Edit</td>
                            </tr>
                            <tr class="border-b">
                                <td class="px-2 py-3 font-bold">No Telepon</td>
                                <td class="px-2">:</td>
                                <td class="px-2">{{ page.props.auth.user.phone_number }}</td>
                                <td class="cursor-pointer px-2 text-primary-600 underline">Edit</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>
    </AccountSettings>
</template>
