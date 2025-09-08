<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import InputMain from '@/components/form/InputMain.vue';
import BaseModal from '@/components/modal/BaseModal.vue';
import { useAPI } from '@/composables/useAPI';
import { useConfirmation } from '@/composables/useConfirmation';
import { useNotifications } from '@/composables/useNotifications';
import AccountSettings from '@/layouts/AccountSettingsLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Home, MapPinCheck, MapPinPlus, MapPinX, Pencil, Pin, Plus, Trash, User } from 'lucide-vue-next';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import { ref } from 'vue';

// Register Library
Swiper.use([Navigation, Pagination]);
const { notivueError, notivueSuccess, notivueInfo } = useNotifications();
const { fetchAPI } = useAPI();
const page = usePage();

type Address = {
    id: string;
    label: string;
    recipientName: string;
    phoneNumber: string;
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
    id: '',
    label: '',
    recipientName: '',
    phoneNumber: '',
    fullAddress: '',
    isMain: false,
    hasPinpoint: false,
    pinpointLocation: {
        lat: null,
        lng: null,
    },
});
const formAdd = ref<Address>({
    id: '',
    label: '',
    recipientName: '',
    phoneNumber: '',
    fullAddress: '',
    isMain: false,
    hasPinpoint: false,
    pinpointLocation: {
        lat: null,
        lng: null,
    },
});

const addresses = ref<Address[]>((page.props.addresses as Address[]) || []);
const formLoading = ref<boolean>(false);

const { showConfirmation } = useConfirmation();

const handleSetMainAddress = (id: string) => {
    showConfirmation({
        title: 'Set Alamat Utama',
        content: 'Apakah kamu yakin ingin menjadikan alamat ini sebagai alamat utama?',
        onConfirm: async () => {
            formLoading.value = true;
            formEdit.value = { ...addresses.value.find((address) => address.id === id) } as Address;
            formEdit.value.isMain = true;
            const response = await fetchAPI('/account-settings/address/update', {
                method: 'POST',
                data: { ...formEdit.value },
            });
            if (response.status === 200) {
                notivueSuccess('Berhasil merubah alamat utama');
                refreshData(response.data.addresses);
            } else {
                // Handle validation errors
                if (response.data && response.data.errors) {
                    const errors = Object.values(response.data.errors).flat();
                    notivueError(errors.join(' '));
                } else {
                    notivueError('Terjadi kesalahan saat memperbarui alamat utama');
                }
            }

            formLoading.value = false;
        },
        onCancel: () => {
            notivueInfo('Batal menjadikan alamat sebagai utama');
        },
    });
};
const handleDeleteAddress = (id: string) => {
    showConfirmation({
        title: 'Hapus Alamat',
        content: 'Apakah kamu yakin ingin menghapus alamat ini?',
        onConfirm: async () => {
            formLoading.value = true;
            const response = await fetchAPI('/account-settings/address/delete', {
                method: 'POST',
                data: { id },
            });
            if (response.status === 200) {
                notivueSuccess('Berhasil menghapus alamat');
                refreshData(response.data.addresses);
            } else {
                // Handle validation errors
                if (response.data && response.data.errors) {
                    const errors = Object.values(response.data.errors).flat();
                    notivueError(errors.join(' '));
                } else {
                    notivueError('Terjadi kesalahan saat memperbarui alamat');
                }
            }

            formLoading.value = false;
        },
        onCancel: () => {
            notivueInfo('Batal menghapus alamat');
        },
    });
};
const handleEditAddress = async () => {
    formLoading.value = true;
    const response = await fetchAPI('/account-settings/address/update', {
        method: 'POST',
        data: { ...formEdit.value },
    });
    if (response.status === 200) {
        // Success - close modal and reset form
        editModal.value?.close();
        notivueSuccess('Berhasil memperbarui alamat');
        refreshData(response.data.addresses);
    } else {
        // Handle validation errors
        if (response.data && response.data.errors) {
            const errors = Object.values(response.data.errors).flat();
            notivueError(errors.join(' '));
        } else {
            notivueError('Terjadi kesalahan saat memperbarui alamat');
        }
    }

    formLoading.value = false;
};
const handleAddAddress = async () => {
    formLoading.value = true;
    const response = await fetchAPI('/account-settings/address/create', {
        method: 'POST',
        data: { ...formAdd.value },
    });
    if (response.status === 200) {
        // Success - close modal and reset form
        addModal.value?.close();
        notivueSuccess('Berhasil menambahkan alamat');
        refreshData(response.data.addresses);
    } else {
        // Handle validation errors
        if (response.data && response.data.errors) {
            const errors = Object.values(response.data.errors).flat();
            notivueError(errors.join(' '));
        } else {
            notivueError('Terjadi kesalahan saat menambahkan alamat');
        }
    }

    formLoading.value = false;
};
const refreshData = (data: Address[]) => {
    addresses.value = data;
};
const handleResetModals = () => {
    formEdit.value = {
        id: '',
        label: '',
        recipientName: '',
        phoneNumber: '',
        fullAddress: '',
        isMain: false,
        hasPinpoint: false,
        pinpointLocation: {
            lat: null,
            lng: null,
        },
    };
    formAdd.value = {
        id: '',
        label: '',
        recipientName: '',
        phoneNumber: '',
        fullAddress: '',
        isMain: false,
        hasPinpoint: false,
        pinpointLocation: {
            lat: null,
            lng: null,
        },
    };
};
const handleRequestLocation = (formType: 'edit' | 'add'): { lat: number; lng: number } | null => {
    if (!navigator.geolocation) {
        notivueError('Geolocation is not supported by your browser');
        return null;
    }

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            if (formType === 'edit') {
                formEdit.value.pinpointLocation = { lat: latitude, lng: longitude };
                formEdit.value.hasPinpoint = true;
            } else {
                formAdd.value.pinpointLocation = { lat: latitude, lng: longitude };
                formAdd.value.hasPinpoint = true;
            }
            notivueSuccess('Berhasil menetapkan titik lokasi saat ini');
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
</script>

<template>
    <AccountSettings>
        <!-- Loading -->
        <!-- Title -->
        <template #settingsHeader>Daftar Alamat</template>
        <!-- Description -->
        <template #settingsDescription>Kamu dapat melihat mengelola alamatmu disini</template>
        <!-- Content -->
        <template #settingsContent>
            <div class="mb-8 flex justify-start">
                <ButtonMain id="add-new-address" type="button" :disabled="formLoading" @click="addModal?.open()">
                    Tambah Alamat Baru <Plus class="h-4 w-4" />
                </ButtonMain>
            </div>
            <div v-if="addresses.length > 0" class="grid grid-cols-1 gap-4 md:grid-cols-2">
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
                                <User class="h-4 w-4" /> {{ value.recipientName }} ({{ value.phoneNumber }})
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
                                    :class="{
                                        'pointer-events-none opacity-50': formLoading,
                                    }"
                                    class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80"
                                >
                                    <div class="me-2">|</div>
                                    <Trash class="me-2 h-4 w-4" /> {{ formLoading ? 'Menghapus...' : 'Hapus' }}
                                </button>
                                <div class="flex flex-wrap gap-3">
                                    <button
                                        v-if="!value.isMain"
                                        @click="handleSetMainAddress(value.id)"
                                        :class="{
                                            'pointer-events-none opacity-50': formLoading,
                                        }"
                                        class="flex cursor-pointer items-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80"
                                    >
                                        <div class="me-2">|</div>
                                        <Pin class="me-2 h-4 w-4" /> {{ formLoading ? 'Mengubah...' : 'Jadikan Utama' }}
                                    </button>
                                    <button
                                        @click="
                                            formEdit = { ...value };
                                            editModal?.open();
                                        "
                                        :class="{
                                            'pointer-events-none opacity-50': formLoading,
                                        }"
                                        class="flex cursor-pointer items-center justify-center text-sm font-medium text-nowrap text-foreground underline hover:opacity-80"
                                    >
                                        <div class="me-2">|</div>
                                        <Home class="me-2 h-4 w-4" /> Ubah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="text-center text-gray-500">
                <p class="text-lg font-semibold">Belum ada alamat yang disimpan</p>
                <p class="my-2">Tambahkan alamat untuk memudahkan pengiriman pesanan Anda.</p>
            </div>
            <BaseModal
                :id="'edit-modal'"
                static="static"
                :on-close="handleResetModals"
                :title="'Edit Alamat'"
                :isCloseable="true"
                :isLoading="formLoading"
                ref="editModal"
            >
                <template #icon>
                    <Pencil class="h-5 w-5" />
                </template>
                <template #content>
                    <form @submit.prevent="handleEditAddress" class="grid grid-cols-1 gap-4 overflow-hidden px-1 sm:grid-cols-2">
                        <div class="col-span-2">
                            <InputMain
                                v-model="formEdit.label"
                                label="Label"
                                id="label"
                                type="text"
                                helper-text="Contoh: Rumah, Kantor, dll."
                                class="col-span-2"
                                placeholder=""
                                :required="true"
                            />
                        </div>
                        <div class="col-span-2">
                            <InputMain
                                v-model="formEdit.recipientName"
                                label="Nama Penerima"
                                id="reciever"
                                type="text"
                                helper-text="Nama lengkap penerima pesanan"
                                class="col-span-2"
                                placeholder=""
                                :required="true"
                            />
                        </div>
                        <div class="col-span-2">
                            <InputMain
                                v-model="formEdit.phoneNumber"
                                label="No. Telp Penerima"
                                id="phone"
                                type="number"
                                helper-text="Contoh: 621234567890, 081234567890"
                                class="col-span-2"
                                placeholder=""
                                :prefix="true"
                                :prefix-class="'bg-primary-600 text-background border-border'"
                                :required="true"
                            >
                                <template #prefix>+62</template>
                            </InputMain>
                        </div>
                        <div class="col-span-2">
                            <InputMain
                                v-model="formEdit.fullAddress"
                                label="Alamat Lengkap"
                                id="address"
                                type="textarea"
                                helper-text="Alamat lengkap penerima pesanan"
                                class="col-span-2"
                                placeholder=""
                                :textArea="true"
                                :required="true"
                            />
                        </div>
                        <div class="col-span-2">
                            <InputMain
                                label="Pin Lokasi Saat Ini"
                                id="address"
                                type="custom"
                                helper-text="Opsional, berikan titik lokasi saat ini untuk memudahkan kurir menemukan alamatmu"
                                class="col-span-2"
                                placeholder=""
                            >
                                <template #custom>
                                    <div
                                        v-if="!formEdit.hasPinpoint"
                                        @click="handleRequestLocation('add') ?? { lat: null, lng: null }"
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
                                </template>
                            </InputMain>
                        </div>
                        <div class="col-span-2 flex justify-end gap-2">
                            <ButtonMain type="button" @click="editModal?.close()" :extend-class="'!w-fit !bg-red-400'"> Batal </ButtonMain>
                            <ButtonMain type="submit" :extend-class="'!w-fit'"> Simpan Perubahan </ButtonMain>
                        </div>
                    </form>
                </template>
            </BaseModal>
            <BaseModal
                :id="'add-modal'"
                static="static"
                :on-close="handleResetModals"
                :title="'Tambah Alamat'"
                :isCloseable="true"
                :isLoading="formLoading"
                ref="addModal"
            >
                <template #icon>
                    <Plus class="h-5 w-5" />
                </template>
                <template #content>
                    <form @submit.prevent="handleAddAddress" class="grid grid-cols-1 gap-4 overflow-hidden px-1 sm:grid-cols-2">
                        <div class="col-span-2">
                            <InputMain
                                v-model="formAdd.label"
                                label="Label"
                                id="label"
                                type="text"
                                helper-text="Contoh: Rumah, Kantor, dll."
                                class="col-span-2"
                                placeholder=""
                                :required="true"
                            />
                        </div>
                        <div class="col-span-2">
                            <InputMain
                                v-model="formAdd.recipientName"
                                label="Nama Penerima"
                                id="reciever"
                                type="text"
                                helper-text="Nama lengkap penerima pesanan"
                                class="col-span-2"
                                placeholder=""
                                :required="true"
                            />
                        </div>
                        <div class="col-span-2">
                            <InputMain
                                v-model="formAdd.phoneNumber"
                                label="No. Telp Penerima"
                                id="phone"
                                type="number"
                                helper-text="Contoh: 621234567890, 081234567890"
                                class="col-span-2"
                                placeholder=""
                                :prefix="true"
                                :prefix-class="'bg-primary-600 text-background border-border'"
                                :required="true"
                            >
                                <template #prefix>+62</template>
                            </InputMain>
                        </div>
                        <div class="col-span-2">
                            <InputMain
                                v-model="formAdd.fullAddress"
                                label="Alamat Lengkap"
                                id="address"
                                type="textarea"
                                helper-text="Alamat lengkap penerima pesanan"
                                class="col-span-2"
                                placeholder=""
                                :textArea="true"
                                :required="true"
                            />
                        </div>
                        <div class="col-span-2">
                            <InputMain
                                label="Pin Lokasi Saat Ini"
                                id="address"
                                type="custom"
                                helper-text="Opsional, berikan titik lokasi saat ini untuk memudahkan kurir menemukan alamatmu"
                                class="col-span-2"
                                placeholder=""
                            >
                                <template #custom>
                                    <div
                                        v-if="!formAdd.hasPinpoint"
                                        @click="handleRequestLocation('add') ?? { lat: null, lng: null }"
                                        class="flex min-h-24 w-full cursor-pointer flex-col items-center justify-center gap-2 rounded border border-border bg-background px-5 py-2 text-center text-base font-medium text-foreground hover:bg-muted hover:opacity-80 focus:ring-4 focus:ring-ring/20 focus:outline-none"
                                    >
                                        <MapPinPlus class="h-6 w-6" />
                                        Berikan Lokasi saat ini
                                    </div>
                                    <div
                                        v-if="formAdd.hasPinpoint"
                                        class="flex min-h-24 w-full flex-col items-center justify-center gap-2 rounded border border-border bg-background px-5 py-5 text-center text-base font-medium text-foreground"
                                    >
                                        <MapPinCheck class="h-6 w-6 text-primary-600" />

                                        Lokasi telah ditetapkan (Lat: {{ formAdd.pinpointLocation?.lat }}, Lng: {{ formAdd.pinpointLocation?.lng }})
                                        <ButtonMain
                                            type="button"
                                            @click="((formAdd.pinpointLocation = { lat: null, lng: null }), (formAdd.hasPinpoint = false))"
                                            :extend-class="'!w-fit !border-red-400 !text-red-400 hover:!bg-red-400 hover:!text-background !text-xs !px-3 !py-2'"
                                            :outline="true"
                                        >
                                            Hapus Pin Lokasi
                                        </ButtonMain>
                                    </div>
                                </template>
                            </InputMain>
                        </div>
                        <div class="col-span-2 flex justify-end gap-2">
                            <ButtonMain type="button" @click="addModal?.close()" :extend-class="'!w-fit !bg-red-400'"> Batal </ButtonMain>
                            <ButtonMain type="submit" :extend-class="'!w-fit'"> Simpan Perubahan </ButtonMain>
                        </div>
                    </form>
                </template>
            </BaseModal>
        </template>
    </AccountSettings>
</template>
