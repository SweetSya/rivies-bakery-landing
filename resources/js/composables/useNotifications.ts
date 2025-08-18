import { push } from 'notivue';

export function useNotifications() {
    const notivueSuccess = (message: string) => {
        push.success({
            message: message,
        });
    };

    const notivueError = (message: string) => {
        push.error({
            message: message,
        });
    };

    const notivueInfo = (message: string) => {
        push.info({
            message: message,
        });
    };

    return {
        notivueSuccess,
        notivueError,
        notivueInfo,
    };
}
