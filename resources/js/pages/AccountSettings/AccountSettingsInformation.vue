<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import BaseModal from '@/components/modal/BaseModal.vue';
import { useAPI } from '@/composables/useAPI';
import { useNotifications } from '@/composables/useNotifications';
import AccountSettings from '@/layouts/AccountSettingsLayout.vue';
import { User } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Pencil, UploadCloud, X } from 'lucide-vue-next';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import { ref } from 'vue';

// Register Library
Swiper.use([Navigation, Pagination]);
gsap.registerPlugin(ScrollTrigger);

const page = usePage();
const { getStorage, fetchAPI } = useAPI();
const { notivueError, notivueSuccess, notivueInfo } = useNotifications();
const user = ref<User>(page.props.user as User);
const editField = ref({
    title: '',
    section: '',
});
const formLoading = ref(false);
const formField = ref({
    name: user.value.name,
    email: user.value.email,
    phone_number: user.value.phone_number,
    profile_picture: user.value.profile_picture,
    new_profile_picture: null as File | null,
});
const editModal = ref<typeof BaseModal | null>(null);
const openEditModal = (title: string, section: string) => {
    editField.value.title = title;
    editField.value.section = section;
    editModal.value?.open();
};
const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        // Security validations
        if (!validateFile(file)) {
            formField.value.new_profile_picture = null;
            return;
        }
        formField.value.new_profile_picture = file;
    }
};

const validateFile = async (file: File): Promise<boolean> => {
    // 1. Check file size (2MB max)
    const maxSize = 2 * 1024 * 1024; // 2MB
    if (file.size > maxSize) {
        notivueError('Ukuran file terlalu besar. Maksimal 2MB.');
        return false;
    }

    // 2. Check file type by MIME type
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
        notivueError('Format file tidak didukung. Hanya JPG, PNG, GIF, dan WebP yang diizinkan.');
        return false;
    }

    // 3. Check file extension
    const allowedExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp'];
    const fileExtension = '.' + file.name.split('.').pop()?.toLowerCase();
    if (!allowedExtensions.includes(fileExtension)) {
        notivueError('Ekstensi file tidak valid.');
        return false;
    }

    // 4. Additional security: Check if it's actually an image
    return await validateImageHeader(file);
};

const validateImageHeader = (file: File): Promise<boolean> => {
    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const arr = new Uint8Array(e.target?.result as ArrayBuffer);

            // Check file signatures (magic numbers)
            const isJPEG = arr[0] === 0xff && arr[1] === 0xd8 && arr[2] === 0xff;
            const isPNG = arr[0] === 0x89 && arr[1] === 0x50 && arr[2] === 0x4e && arr[3] === 0x47;
            const isGIF = arr[0] === 0x47 && arr[1] === 0x49 && arr[2] === 0x46;
            const isWebP = arr[8] === 0x57 && arr[9] === 0x45 && arr[10] === 0x42 && arr[11] === 0x50;

            if (!(isJPEG || isPNG || isGIF || isWebP)) {
                alert('File bukan gambar yang valid.');
                resolve(false);
            } else {
                resolve(true);
            }
        };
        reader.readAsArrayBuffer(file.slice(0, 12)); // Read first 12 bytes
    });
};
const resetForm = () => {
    editField.value = { title: '', section: '' };
    formField.value.name = user.value.name;
    formField.value.email = user.value.email;
    formField.value.phone_number = user.value.phone_number;
    formField.value.profile_picture = user.value.profile_picture;
    formField.value.new_profile_picture = null;
};
const handleSubmit = async () => {
    formLoading.value = true;
    // Create FormData for file upload
    const formData = new FormData();

    // Add form fields to FormData
    formData.append('name', formField.value.name);
    formData.append('email', formField.value.email);
    formData.append('phone_number', formField.value.phone_number);

    // Add file if selected
    if (formField.value.new_profile_picture) {
        formData.append('new_profile_picture', formField.value.new_profile_picture);
    }

    const response = await fetchAPI('/account-settings/update-profile', {
        method: 'POST',
        data: formData,
    });

    if (response.status === 200) {
        console.log(response.data.user);
        user.value.name = response.data.user.name;
        user.value.email = response.data.user.email;
        user.value.phone_number = response.data.user.phone_number;
        user.value.profile_picture = response.data.user.profile_picture;
        // Success - close modal and reset form
        notivueSuccess('Profil berhasil diperbarui.');
        editModal.value?.close();
        resetForm();
    } else {
        // Handle validation errors
        if (response.data && response.data.errors) {
            const errors = Object.values(response.data.errors).flat();
            notivueError(errors.join(' '));
        } else {
            notivueError('Terjadi kesalahan saat memperbarui profil.');
        }
    }

    formLoading.value = false;
};
const createObjectUrl = (file: File | null) => {
    if (file) {
        return URL.createObjectURL(file);
    }
    return '';
};

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
                                        :src="getStorage(user.profile_picture ?? '../assets/placeholder/image.png')"
                                        alt=""
                                    />
                                    <p
                                        @click="openEditModal('Ubah Foto Profil', 'profile_picture')"
                                        class="mt-2 cursor-pointer px-2 text-center text-primary-600 underline"
                                    >
                                        Edit
                                    </p>
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
                                <td class="px-2">{{ user.name }}</td>
                                <td @click="openEditModal('Ubah Nama', 'name')" class="cursor-pointer px-2 text-primary-600 underline">Edit</td>
                            </tr>
                            <tr class="border-b">
                                <td class="px-2 py-3 font-bold">Email</td>
                                <td class="px-2">:</td>
                                <td class="px-2">{{ user.email }}</td>
                                <td @click="openEditModal('Ubah Email', 'email')" class="cursor-pointer px-2 text-primary-600 underline">Edit</td>
                            </tr>
                            <tr class="border-b">
                                <td class="px-2 py-3 font-bold">No Telepon</td>
                                <td class="px-2">:</td>
                                <td class="px-2">{{ user.phone_number }}</td>
                                <td @click="openEditModal('Ubah No Telepon', 'phone_number')" class="cursor-pointer px-2 text-primary-600 underline">
                                    Edit
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <BaseModal
                :id="'edit-modal'"
                :title="editField.title"
                :static="'static'"
                :on-close="resetForm"
                :isCloseable="true"
                :isLoading="formLoading"
                ref="editModal"
            >
                <template #icon>
                    <Pencil class="h-5 w-5" />
                </template>
                <template #content>
                    <form @submit.prevent="handleSubmit" class="grid grid-cols-1 gap-4 overflow-hidden sm:grid-cols-2">
                        <div v-if="editField.section === 'name'" class="col-span-2">
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Nama Lengkap </label>
                            <input
                                type="text"
                                id="name"
                                v-model="formField.name"
                                class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                required
                            />
                        </div>
                        <div v-if="editField.section === 'email'" class="col-span-2">
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Email </label>
                            <input
                                type="text"
                                id="email"
                                v-model="formField.email"
                                class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                required
                            />
                        </div>
                        <div v-if="editField.section === 'phone_number'" class="col-span-2">
                            <label for="phone_number" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> No. Telepon </label>
                            <input
                                type="text"
                                id="phone_number"
                                v-model="formField.phone_number"
                                class="block w-full rounded-lg border border-gray-300 bg-transparent p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                required
                            />
                        </div>
                        <div v-if="editField.section === 'profile_picture'" class="col-span-2">
                            <label for="profile_picture" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Foto Profil </label>

                            <div class="flex w-full items-center justify-center">
                                <label
                                    v-if="!formField.new_profile_picture"
                                    for="profile_picture"
                                    class="flex h-56 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed hover:border-primary-600"
                                >
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <UploadCloud class="mb-3 h-10 w-10 text-gray-400" />
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, or JPG</p>
                                    </div>
                                    <input @change="handleFileChange" id="profile_picture" type="file" accept="image/*" class="hidden" />
                                </label>
                                <div v-if="formField.new_profile_picture" class="relative ms-4">
                                    <button
                                        @click="formField.new_profile_picture = null"
                                        type="button"
                                        class="absolute bottom-2 left-1/2 z-10 flex -translate-x-1/2 cursor-pointer items-center gap-2 rounded-full bg-primary-500 p-1 text-xs text-background shadow-md hover:bg-primary-600"
                                    >
                                        <X class="h-5 w-5" />
                                    </button>
                                    <img
                                        :src="createObjectUrl(formField.new_profile_picture)"
                                        alt="Preview"
                                        class="aspect-square h-56 rounded border border-border object-cover shadow"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2 flex justify-end gap-2">
                            <ButtonMain
                                type="button"
                                @click="((editField = { section: '', title: '' }), editModal?.close())"
                                :extend-class="'!w-fit !bg-red-400'"
                            >
                                Batal
                            </ButtonMain>
                            <ButtonMain type="submit" :extend-class="'!w-fit'"> Simpan Perubahan </ButtonMain>
                        </div>
                    </form>
                </template>
            </BaseModal>
        </template>
    </AccountSettings>
</template>
