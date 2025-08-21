<script setup lang="ts">
import { Modal, ModalInterface, ModalOptions } from 'flowbite';
import { X } from 'lucide-vue-next';
import { defineProps, onMounted, ref } from 'vue';
import LoadingSpinner from '../LoadingSpinner.vue';
const props = defineProps<{
    id: string;
    title: string;
    icon?: any;
    size?: 'md' | 'lg' | 'xl';
    static?: 'static' | 'dynamic';
    isCloseable?: boolean;
    isLoading?: boolean;
    loading?: {
        extendClass: string;
        message: string;
    };
}>();

const modal = ref<ModalInterface | null>(null);

const open = () => {
    modal.value?.show();
};
const close = () => {
    modal.value?.hide();
};
onMounted(() => {
    const modalElement = document.getElementById(props.id);

    if (modalElement) {
        const modalOptions: ModalOptions = {
            backdrop: props.static ?? 'dynamic',
        };

        modal.value = new Modal(modalElement, modalOptions);
    }
});
defineExpose({
    open,
    close,
});
</script>

<template>
    <div
        :id="id"
        tabindex="-1"
        class="fixed top-0 right-0 left-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-x-hidden overflow-y-auto p-4 md:inset-0"
    >
        <div class="relative max-h-full w-full max-w-xl">
            <!-- Modal content -->
            <div class="relative rounded-lg bg-background shadow-md shadow-foreground/10">
                <!-- Modal header -->

                <div class="flex items-center justify-between rounded-t border-b border-gray-200 bg-primary-600 p-4 md:p-5 dark:border-gray-600">
                    <h3 class="flex items-center gap-4 text-xl font-medium text-foreground"><slot name="modalIcon" /> {{ title }}</h3>
                    <X v-if="isCloseable" class="h-5 w-5 cursor-pointer hover:opacity-80" @click="close()" />
                </div>
                <!-- Modal body -->
                <LoadingSpinner
                    :extendClass="props.loading?.extendClass ?? 'h-40'"
                    :message="props.loading?.message ?? 'Sedang memproses..'"
                    v-show="isLoading"
                />
                <div v-show="!isLoading" class="p-4 md:p-5">
                    <slot name="modalContent" />
                </div>
            </div>
        </div>
    </div>
</template>
