<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import BaseModal from '@/components/modal/BaseModal.vue';
import { useAPI } from '@/composables/useAPI';
import { useConfirmation } from '@/composables/useConfirmation';
import { useNotifications } from '@/composables/useNotifications';
import AccountSettings from '@/layouts/AccountSettingsLayout.vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Home, MapPinCheck, MapPinPlus, MapPinX, Pencil, Pin, Plus, Trash, User } from 'lucide-vue-next';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import { onMounted, ref } from 'vue';

// Register Library
Swiper.use([Navigation, Pagination]);
const { notivueError, notivueSuccess, notivueInfo } = useNotifications();
const { fetchAPI } = useAPI();

type Address = {
    id: number;
    label: string;
    recipientName: string;
    fullAddress: string;
    isMain: boolean;
    hasPinpoint: boolean;
    pinpointLocation?: {
        lat: number | null;
        lng: number | null;
    };
};

gsap.registerPlugin(ScrollTrigger);

const editModal = ref<typeof BaseModal | null>(null);
const addModal = ref<typeof BaseModal | null>(null);

const formEdit = ref<Address>({
    id: 0,
    label: '',
    recipientName: '',
    fullAddress: '',
    isMain: false,
    hasPinpoint: false,
    pinpointLocation: {
        lat: null,
        lng: null,
    },
});
const formAdd = ref<Address>({
    id: 0,
    label: '',
    recipientName: '',
    fullAddress: '',
    isMain: false,
    hasPinpoint: false,
    pinpointLocation: {
        lat: null,
        lng: null,
    },
});

const addresses = ref<Address[]>([]);

const { showConfirmation } = useConfirmation();

const handleSetMainAddress = (id: number) => {
    showConfirmation({
        title: 'Set Alamat Utama',
        content: 'Apakah kamu yakin ingin menjadikan alamat ini sebagai alamat utama?',
        onConfirm: async () => {
            formEdit.value = { ...addresses.value.find((address) => address.id === id) } as Address;
            formEdit.value.isMain = true;
            const response = await fetchAPI('/account-settings/address/update', {
                method: 'POST',
                data: { ...formEdit.value },
            });
            if (response.status === 200) {
                notivueSuccess('Berhasil merubah alamat utama');
            } else {
                // Handle validation errors
                if (response.data && response.data.errors) {
                    const errors = Object.values(response.data.errors).flat();
                    notivueError(errors.join(' '));
                } else {
                    notivueError('Terjadi kesalahan saat memperbarui alamat utama');
                }
            }
        },
        onCancel: () => {
            notivueInfo('Batal menjadikan alamat sebagai utama');
        },
    });
};
const handleDeleteAddress = (id: number) => {
    showConfirmation({
        title: 'Hapus Alamat',
        content: 'Apakah kamu yakin ingin menghapus alamat ini?',
        onConfirm: async () => {
            const response = await fetchAPI('/account-settings/address/delete', {
                method: 'POST',
                data: { id },
            });
            if (response.status === 200) {
                notivueSuccess('Berhasil menghapus alamat');
                // Add your logic to delete the address here
            } else {
                // Handle validation errors
                if (response.data && response.data.errors) {
                    const errors = Object.values(response.data.errors).flat();
                    notivueError(errors.join(' '));
                } else {
                    notivueError('Terjadi kesalahan saat memperbarui alamat');
                }
            }
        },
        onCancel: () => {
            notivueInfo('Batal menghapus alamat');
        },
    });
};
const handleEditAddress = async () => {
    const response = await fetchAPI('/account-settings/address/update', {
        method: 'POST',
        data: { ...formEdit.value },
    });
    if (response.status === 200) {
        // Success - close modal and reset form
        editModal.value?.close();
        notivueSuccess('Berhasil memperbarui alamat');
    } else {
        // Handle validation errors
        if (response.data && response.data.errors) {
            const errors = Object.values(response.data.errors).flat();
            notivueError(errors.join(' '));
        } else {
            notivueError('Terjadi kesalahan saat memperbarui alamat');
        }
    }
};
const handleRequestLocation = (): { lat: number; lng: number } | null => {
    if (!navigator.geolocation) {
        notivueError('Geolocation is not supported by your browser');
        return null;
    }

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            notivueSuccess('Berhasil menetapkan titik lokasi saat ini');
            formEdit.value.hasPinpoint = true;
            formEdit.value.pinpointLocation = {
                lat: latitude,
                lng: longitude,
            };
        },
        () => {
            notivueError('Tidak dapat mengambil lokasi Anda');
            return null;
        },
    );
    return null;
};
defineOptions({
    components: {
        AccountSettings,
    },
});
onMounted(() => {
    addresses.value = [
        {
            id: 1,
            label: 'Rumah',
            recipientName: 'Sultan Hakim Herrysan',
            fullAddress: 'Jl. Raya Ijen No.61, Mergelo, Wates, Kec. Magersari, Kota Mojokerto, Jawa Timur 61317',
            isMain: true,
            hasPinpoint: true,
            pinpointLocation: {
                lat: -7.46844,
                lng: 112.43294,
            },
        },
        {
            id: 2,
            label: 'Kantor',
            recipientName: 'Sultan Hakim Herrysan',
            fullAddress: 'Jl. Raya Ijen No.61, Mergelo, Wates, Kec. Magersari, Kota Mojokerto, Jawa Timur 61317',
            isMain: false,
            hasPinpoint: false,
        },
        {
            id: 3,
            label: 'Ortu',
            recipientName: 'Sultan Hakim Herrysan',
            fullAddress: 'Jl. Raya Ijen No.61, Mergelo, Wates, Kec. Magersari, Kota Mojokerto, Jawa Timur 61317',
            isMain: false,
            hasPinpoint: true,
            pinpointLocation: {
                lat: -7.46844,
                lng: 112.43294,
            },
        },
    ];
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
                <div v-for="value in addresses" :key="value.id" class="rounded-lg border bg-transparent p-4 ps-4">
                    <div class="flex items-start">
                        <div class="w-full space-y-2 text-sm">
                            <div
                                class="flex justify-between gap-1 border-b pb-2 text-base leading-none font-bold text-foreground xs:flex-row md:items-center md:text-lg"
                            >
                                <span>{{ value.label }}</span> <span class="text-primary-600" v-if="value.isMain">[Utama]</span>
                            </div>
                            <p class="mt-1 text-xs font-normal text-foreground/80 md:text-base">
                                {{ value.fullAddress }}
                            </p>
                            <div class="mt-1 flex items-center gap-2 text-xs font-normal text-foreground/80 md:text-base">
                                <User class="h-4 w-4" /> {{ value.recipientName }}
                            </div>
                            <div
                                :class="{ 'text-foreground/80': !value.hasPinpoint, 'text-primary-600': value.hasPinpoint }"
                                class="mt-1 flex items-center gap-2 text-xs font-normal md:text-base"
                            >
                                <MapPinCheck v-if="value.hasPinpoint" class="h-4 w-4" /> <MapPinX v-if="!value.hasPinpoint" class="h-4 w-4" />
                                {{ value.hasPinpoint ? 'Sudah pin lokasi' : 'Tidak ada pin lokasi' }}
                            </div>
                            <div class="flex flex-wrap justify-between gap-3">
                                <button
                                    @click="handleDeleteAddress(value.id)"
                                    class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80"
                                >
                                    <Trash class="me-2 h-4 w-4" /> Hapus
                                </button>
                                <div class="flex flex-wrap gap-3">
                                    <button
                                        v-if="!value.isMain"
                                        @click="handleSetMainAddress(value.id)"
                                        class="flex cursor-pointer items-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80"
                                    >
                                        <Pin class="me-2 h-4 w-4" /> Jadikan utama
                                    </button>
                                    <button
                                        @click="
                                            formEdit = { ...value };
                                            editModal?.open();
                                        "
                                        class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80"
                                    >
                                        <Home class="me-2 h-4 w-4" /> Ubah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <BaseModal :id="'edit-modal'" static="static" :title="'Edit Alamat'" :isCloseable="true" :isLoading="false" ref="editModal">
                <template #icon>
                    <Pencil class="h-5 w-5" />
                </template>
                <template #content>
                    <form @submit.prevent="handleEditAddress" class="grid grid-cols-1 gap-4 overflow-hidden sm:grid-cols-2">
                        <div class="col-span-2">
                            <label for="label" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Label </label>
                            <input
                                type="text"
                                id="label"
                                v-model="formEdit.label"
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
                                v-model="formEdit.recipientName"
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
                                v-model="formEdit.fullAddress"
                                rows="4"
                                required
                                class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            ></textarea>
                        </div>
                        <div class="col-span-2">
                            <label for="address" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Pin Lokasi Saat Ini </label>
                            <div
                                v-if="!formEdit.hasPinpoint"
                                @click="handleRequestLocation() ?? { lat: null, lng: null }"
                                class="flex min-h-24 w-full cursor-pointer flex-col items-center justify-center gap-2 rounded border border-border bg-background px-5 py-2 text-center text-base font-medium text-foreground hover:bg-muted hover:opacity-80 focus:ring-4 focus:ring-ring/20 focus:outline-none"
                            >
                                <MapPinPlus class="h-6 w-6" />
                                Berikan Lokasi saat ini
                            </div>
                            <div
                                v-if="formEdit.hasPinpoint"
                                class="flex min-h-24 w-full flex-col items-center justify-center gap-2 rounded border border-border bg-background px-5 py-5 text-center text-base font-medium text-foreground"
                            >
                                <MapPinCheck class="h-6 w-6 text-primary-600" />

                                Lokasi telah ditetapkan (Lat: {{ formEdit.pinpointLocation?.lat }}, Lng: {{ formEdit.pinpointLocation?.lng }})
                                <ButtonMain
                                    type="button"
                                    @click="((formEdit.pinpointLocation = { lat: null, lng: null }), (formEdit.hasPinpoint = false))"
                                    :extend-class="'!w-fit !border-red-400 !text-red-400 hover:!bg-red-400 hover:!text-background !text-xs !px-3 !py-2'"
                                    :outline="true"
                                >
                                    Hapus Pin Lokasi
                                </ButtonMain>
                            </div>
                        </div>
                        <div class="col-span-2 flex justify-end gap-2">
                            <ButtonMain type="button" @click="editModal?.close()" :extend-class="'!w-fit !bg-red-400'"> Batal </ButtonMain>
                            <ButtonMain type="submit" :extend-class="'!w-fit'"> Simpan Perubahan </ButtonMain>
                        </div>
                    </form>
                </template>
            </BaseModal>
            <BaseModal :id="'add-modal'" static="static" :title="'Tambah Alamat'" :isCloseable="true" :isLoading="false" ref="addModal">
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
                                class="flex min-h-24 w-full cursor-pointer flex-col items-center justify-center gap-2 rounded border border-border bg-background px-5 py-2 text-center text-base font-medium text-foreground hover:bg-muted hover:opacity-80 focus:ring-4 focus:ring-ring/20 focus:outline-none"
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
