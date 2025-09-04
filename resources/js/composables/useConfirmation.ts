import { ref } from 'vue';

type ConfirmationOptions = {
    title?: string;
    content: string;
    onConfirm: () => void;
    onCancel?: () => void;
};

const isVisible = ref(false);
const modalOptions = ref<ConfirmationOptions>({
    title: 'Konfirmasi',
    content: '',
    onConfirm: () => {},
    onCancel: () => {},
});

export function useConfirmation() {
    const showConfirmation = (options: ConfirmationOptions) => {
        modalOptions.value = {
            title: 'Konfirmasi',
            ...options,
        };
        isVisible.value = true;
    };

    const hideConfirmation = () => {
        isVisible.value = false;
    };

    const confirm = () => {
        modalOptions.value.onConfirm();
        hideConfirmation();
    };

    const cancel = () => {
        modalOptions.value.onCancel?.();
        hideConfirmation();
    };

    return {
        isVisible,
        modalOptions,
        showConfirmation,
        hideConfirmation,
        confirm,
        cancel,
    };
}