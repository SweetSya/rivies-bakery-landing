<script setup lang="ts">
import { useConfirmation } from '@/composables/useConfirmation';
import { ref, watch } from 'vue';
import ButtonMain from '../buttons/ButtonMain.vue';
import BaseModal from './BaseModal.vue';

const confirmationModal = ref<InstanceType<typeof BaseModal> | null>(null);
const { isVisible, modalOptions, confirm, cancel } = useConfirmation();

// Watch for visibility changes and open/close modal
watch(isVisible, (newValue) => {
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
        static="static"
        :title="modalOptions.title as string"
        title-extend-class="!px-2 !py-1 md:!py-3 md:!px-2"
        ref="confirmationModal"
    >
        <template #content>
            {{ modalOptions.content }}
            <div class="flex justify-end gap-2">
                <ButtonMain @click="handleCancel" :extend-class="'!bg-red-400 !px-4 !py-1.5 !text-sm'" type="button"> Batal </ButtonMain>
                <ButtonMain @click="handleConfirm" :extend-class="'!px-4 !py-1.5 !text-sm'" type="button"> Konfirmasi </ButtonMain>
            </div>
        </template>
    </BaseModal>
</template>
