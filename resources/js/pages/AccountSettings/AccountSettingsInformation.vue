<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import BaseModal from '@/components/modal/BaseModal.vue';
import { useAPI } from '@/composables/useAPI';
import { useConfirmation } from '@/composables/useConfirmation';
import AccountSettings from '@/layouts/AccountSettingsLayout.vue';
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
const { getStorage } = useAPI();

const editField = ref({
    title: '',
    section: '',
});
const formField = ref({
    name: page.props.auth.user.name,
    email: page.props.auth.user.email,
    phone_number: page.props.auth.user.phone_number,
    profile_picture: page.props.auth.user.profile_picture,
    new_profile_picture: null as string | null,
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
        const reader = new FileReader();
        reader.readAsDataURL(target.files[0]);
        reader.onload = () => {
            formField.value.new_profile_picture = reader.result as string;
        };
    }
};
const resetForm = () => {
    editField.value = { title: '', section: '' };
    formField.value.name = page.props.auth.user.name;
    formField.value.email = page.props.auth.user.email;
    formField.value.phone_number = page.props.auth.user.phone_number;
    formField.value.profile_picture = page.props.auth.user.profile_picture;
    formField.value.new_profile_picture = null;
};
const handleSubmit = (e: Event) => {
    console.log(e);
    // Submit logic here
    editModal.value?.close();
};
// Usage functions
const { showConfirmation } = useConfirmation();

const editProfile = () => {};

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
                                <td class="px-2">{{ page.props.auth.user.name }}</td>
                                <td @click="openEditModal('Ubah Nama', 'name')" class="cursor-pointer px-2 text-primary-600 underline">Edit</td>
                            </tr>
                            <tr class="border-b">
                                <td class="px-2 py-3 font-bold">Email</td>
                                <td class="px-2">:</td>
                                <td class="px-2">{{ page.props.auth.user.email }}</td>
                                <td @click="openEditModal('Ubah Email', 'email')" class="cursor-pointer px-2 text-primary-600 underline">Edit</td>
                            </tr>
                            <tr class="border-b">
                                <td class="px-2 py-3 font-bold">No Telepon</td>
                                <td class="px-2">:</td>
                                <td class="px-2">{{ page.props.auth.user.phone_number }}</td>
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
                :isLoading="false"
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
                                        class="absolute -end-2 -top-2 z-10 flex cursor-pointer items-center gap-2 rounded-full bg-primary-500 p-1 text-xs text-background hover:bg-primary-600"
                                    >
                                        <X class="h-5 w-5" /> <span>Ubah</span>
                                    </button>
                                    <img
                                        :src="formField.new_profile_picture"
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
