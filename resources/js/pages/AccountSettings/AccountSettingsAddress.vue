<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import BaseModal from '@/components/modal/BaseModal.vue';
import AccountSettings from '@/layouts/AccountSettingsLayout.vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { MapPinCheck, MapPinPlus, Pencil, Plus, Trash, User } from 'lucide-vue-next';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import { ref } from 'vue';

// Register Library
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

const editModal = ref<typeof BaseModal | null>(null);
const addModal = ref<typeof BaseModal | null>(null);

defineOptions({
    components: {
        AccountSettings,
    },
});
</script>

<template>
    <AccountSettings>
        <!-- Title -->
        <template #settingsHeader>Daftar Alamat</template>
        <!-- Description -->
        <template #settingsDescription>Kamu dapat melihat mengelola alamatmu disini</template>
        <!-- Content -->
        <template #settingsContent>
            <div class="mb-8 flex justify-start">
                <ButtonMain id="add-new-address" type="button" :disabled="false" @click="addModal?.open()">
                    Tambah Alamat Baru <Plus class="h-4 w-4" />
                </ButtonMain>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div v-for="value of [1, 2, 3]" :key="value" class="rounded-lg border bg-transparent p-4 ps-4">
                    <div class="flex items-start">
                        <div class="w-full space-y-2 text-sm">
                            <div class="border-b pb-2 text-base leading-none font-bold text-foreground md:text-lg">
                                Rumah <span class="text-primary-600">[Utama]</span>
                            </div>
                            <p class="mt-1 text-xs font-normal text-foreground/80 md:text-base">
                                Jl. Raya Ijen No.61, Mergelo, Wates, Kec. Magersari, Kota Mojokerto, Jawa Timur 61317
                            </p>
                            <div class="mt-1 flex items-center gap-2 text-xs font-normal text-foreground/80 md:text-base">
                                <User class="h-4 w-4" /> Sultan Hakim Herrysan
                            </div>
                            <div class="mt-1 flex items-center gap-2 text-xs font-normal text-foreground/80 md:text-base">
                                <MapPinCheck class="h-4 w-4" /> Sudah Pinpoint
                            </div>
                            <div class="flex flex-wrap justify-between gap-3">
                                <button
                                    class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80"
                                >
                                    <Trash class="me-2 h-4 w-4" /> Hapus
                                </button>
                                <div class="flex flex-wrap gap-3">
                                    <button
                                        @click="editModal?.open()"
                                        class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80"
                                    >
                                        Ubah Alamat
                                    </button>
                                    <button
                                        class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80"
                                    >
                                        Jadikan Utama
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <BaseModal :id="'edit-modal'" :title="'Edit Alamat'" :isCloseable="true" :isLoading="false" ref="editModal">
                <template #icon>
                    <Pencil class="h-5 w-5" />
                </template>
                <template #content>
                    <form class="grid grid-cols-1 gap-4 overflow-hidden sm:grid-cols-2">
                        <div class="col-span-2">
                            <label for="label" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Label </label>
                            <input
                                type="text"
                                id="label"
                                class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                placeholder=""
                                required
                            />
                        </div>
                        <div class="col-span-2">
                            <label for="reciever" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Nama Penerima </label>
                            <input
                                type="text"
                                id="reciever"
                                class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                placeholder=""
                                required
                            />
                        </div>
                        <div class="col-span-2">
                            <label for="address" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Alamat Lengkap </label>
                            <textarea
                                name="address"
                                id="address"
                                rows="4"
                                class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            ></textarea>
                        </div>
                        <div class="col-span-2">
                            <label for="address" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Pin Lokasi Saat Ini </label>
                            <div
                                class="flex min-h-24 w-full flex-col items-center justify-center gap-2 rounded border border-border bg-background px-5 py-2 text-center text-base font-medium text-foreground hover:bg-muted hover:opacity-80 focus:ring-4 focus:ring-ring/20 focus:outline-none"
                            >
                                <MapPinPlus class="h-6 w-6" />
                                Berikan Lokasi saat ini
                            </div>
                        </div>
                        <div class="col-span-2 flex justify-end gap-2">
                            <button
                                type="button"
                                @click="addModal?.close()"
                                class="relative cursor-pointer overflow-hidden rounded-lg border-2 border-base-500 bg-base-600 px-5 py-2 text-center text-base text-foreground hover:opacity-80 focus:z-10 focus:ring-1 focus:ring-base-300 focus:outline-none"
                            >
                                <div class="relative z-10">Batal</div>
                            </button>
                            <button
                                type="button"
                                @click="addModal?.close()"
                                class="relative cursor-pointer overflow-hidden rounded-lg border-2 border-primary-500 bg-primary-600 px-5 py-2 text-center text-base text-foreground hover:opacity-80 focus:z-10 focus:ring-1 focus:ring-primary-300 focus:outline-none"
                            >
                                <div class="relative z-10">Simpan Perubahan</div>
                            </button>
                        </div>
                    </form>
                </template>
            </BaseModal>
            <BaseModal :id="'add-modal'" :title="'Tambah Alamat'" :isCloseable="true" :isLoading="false" ref="addModal">
                <template #icon>
                    <Plus class="h-5 w-5" />
                </template>
                <template #content>
                    <form class="grid grid-cols-1 gap-4 overflow-hidden sm:grid-cols-2">
                        <div class="col-span-2">
                            <label for="label" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Label </label>
                            <input
                                type="text"
                                id="label"
                                class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                placeholder=""
                                required
                            />
                        </div>
                        <div class="col-span-2">
                            <label for="reciever" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Nama Penerima </label>
                            <input
                                type="text"
                                id="reciever"
                                class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                placeholder=""
                                required
                            />
                        </div>
                        <div class="col-span-2">
                            <label for="address" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Alamat Lengkap </label>
                            <textarea
                                name="address"
                                id="address"
                                rows="4"
                                class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            ></textarea>
                        </div>
                        <div class="col-span-2">
                            <label for="address" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Pin Lokasi Saat Ini </label>
                            <div
                                class="flex min-h-24 w-full flex-col items-center justify-center gap-2 rounded border border-border bg-background px-5 py-2 text-center text-base font-medium text-foreground hover:bg-muted hover:opacity-80 focus:ring-4 focus:ring-ring/20 focus:outline-none"
                            >
                                <MapPinPlus class="h-6 w-6" />
                                Berikan Lokasi saat ini
                            </div>
                        </div>
                        <div class="col-span-2 flex justify-end gap-2">
                            <button
                                type="button"
                                @click="addModal?.close()"
                                class="relative cursor-pointer overflow-hidden rounded-lg border-2 border-base-500 bg-base-600 px-5 py-2 text-center text-base text-foreground hover:opacity-80 focus:z-10 focus:ring-1 focus:ring-base-300 focus:outline-none"
                            >
                                <div class="relative z-10">Batal</div>
                            </button>
                            <button
                                type="button"
                                @click="addModal?.close()"
                                class="relative cursor-pointer overflow-hidden rounded-lg border-2 border-primary-500 bg-primary-600 px-5 py-2 text-center text-base text-foreground hover:opacity-80 focus:z-10 focus:ring-1 focus:ring-primary-300 focus:outline-none"
                            >
                                <div class="relative z-10">Tambahkan</div>
                            </button>
                        </div>
                    </form>
                </template>
            </BaseModal>
        </template>
    </AccountSettings>
</template>
