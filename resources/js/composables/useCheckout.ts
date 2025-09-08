import { ref } from 'vue';
import { useNotifications } from './useNotifications';

const { notivueSuccess, notivueError, notivueInfo } = useNotifications();

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

export type CheckoutData = {
    fullName: string;
    email: string;
    address: Address | null;
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
    address: null,
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
            address: null,
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
        if (checkout.value.delivery.method === 'PICKUP') {
            return !checkout.value.payment.method || !checkout.value.delivery.method;
        }
        if (checkout.value.delivery.method === 'GOSEND' || checkout.value.delivery.method === 'OTHER') {
            if (!checkout.value.address || !checkout.value.payment.method) {
                return true;
            }
        }
        return false;
    };
    return {
        getCheckout,
        setCheckout,
        resetCheckout,
        getPaymentStatus,
        isCheckoutEmpty,
    };
}
