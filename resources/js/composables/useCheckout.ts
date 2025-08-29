import { ref } from 'vue';
import { useNotifications } from './useNotifications';

const { notivueSuccess, notivueError, notivueInfo } = useNotifications();

export type CheckoutData = {
    fullName: string;
    email: string;
    address: string | null;
    payment: {
        method: string;
        status?: 'pending' | 'paid' | 'unpaid';
        details?: string;
    };
    delivery: {
        method: string;
        details?: string;
    };
};
const checkout = ref<CheckoutData>({
    fullName: '',
    email: '',
    address: '',
    payment: {
        method: '',
    },
    delivery: {
        method: '',
    },
});
export function useCheckout() {
    const getCheckout = () => {
        return checkout.value;
    };
    const setCheckout = (data: CheckoutData) => {
        checkout.value = data;
    };
    const resetCheckout = () => {
        checkout.value = {
            fullName: '',
            email: '',
            address: '',
            payment: {
                method: '',
            },
            delivery: {
                method: '',
            },
        };
    };
    const getPaymentStatus = () => {
        // Check payment using axios to database
        // For now, return a dummy status
        return 'unpaid';
    };
    const isCheckoutEmpty = () => {
        return (
            !checkout.value.fullName ||
            !checkout.value.email ||
            !checkout.value.address ||
            !checkout.value.payment.method ||
            !checkout.value.delivery.method
        );
    };
    return {
        getCheckout,
        setCheckout,
        resetCheckout,
        getPaymentStatus,
        isCheckoutEmpty,
    };
}
