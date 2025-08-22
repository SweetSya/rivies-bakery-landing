import { push } from 'notivue';

type ConfirmationNotification = {
    title: string;
    message: string;
    confirmText?: string;
    cancelText?: string;
    onConfirm?: () => void;
    onCancel?: () => void;
};
export function useNotifications() {
    const notivueSuccess = (message: string, confirmation?: ConfirmationNotification) => {
        push.success({
            message: message,
            props: {
                confirmation: confirmation || null,
            },
        });
    };

    const notivueError = (message: string, confirmation?: ConfirmationNotification) => {
        push.error({
            message: message,
            props: {
                confirmation: confirmation || null,
            },
        });
    };

    const notivueInfo = (message: string, confirmation?: ConfirmationNotification) => {
        push.info({
            message: message,
            props: {
                confirmation: confirmation || null,
            },
        });
    };

    return {
        notivueSuccess,
        notivueError,
        notivueInfo,
    };
}
