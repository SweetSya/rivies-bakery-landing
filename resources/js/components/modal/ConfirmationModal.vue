<script setup lang="ts">
import { useConfirmation } from '@/composables/useConfirmation';
import AlertAnimation from '@/lotties/alert.json';
import { LottieAnimation } from 'lottie-web-vue';
import { ref, watch } from 'vue';
import ButtonMain from '../buttons/ButtonMain.vue';
import BaseModal from './BaseModal.vue';

const confirmationModal = ref<InstanceType<typeof BaseModal> | null>(null);
const { isVisible, modalOptions, confirm, cancel } = useConfirmation();

// Watch for visibility changes and open/close modal
watch(isVisible, (newValue) => {
    console.log('isVisible changed:', newValue);
    if (newValue) {
        confirmationModal.value?.open();
    } else {
        confirmationModal.value?.close();
    }
});

const handleConfirm = () => {
    confirm();
};

const handleCancel = () => {
    cancel();
};
</script>

<template>
    <BaseModal
        id="confirmation-modal"
        :class-name="'!max-w-md'"
        static="static"
        :animateIn="'animate__animated animate__bounceIn animate__faster'"
        :title="''"
        :title-class="'!p-2'"
        ref="confirmationModal"
    >
        <template #content>
            <div class="text-center">
                <LottieAnimation :animation-data="AlertAnimation" :loop="true" :auto-play="true" :speed="1" class="h-28" />
                {{ modalOptions.content }}
            </div>
            <div class="mt-4 flex justify-center gap-2">
                <ButtonMain @click="handleCancel" :extend-class="'!bg-red-400 !px-4 !py-1.5 !text-sm'" type="button"> Batal </ButtonMain>
                <ButtonMain @click="handleConfirm" :extend-class="'!px-4 !py-1.5 !text-sm'" type="button"> Konfirmasi </ButtonMain>
            </div>
        </template>
    </BaseModal>
</template>
