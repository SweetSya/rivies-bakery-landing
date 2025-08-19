import { ref } from 'vue';
import { Cart } from './useCart';
import { useNotifications } from './useNotifications';

const { notivueSuccess, notivueError, notivueInfo } = useNotifications();

export type CheckoutData = {
    fullName: String;
    email: String;
    address: String;
    cart?: Cart;
    payment: {
        method: String;
        status?: 'pending' | 'paid' | 'unpaid';
        details?: String;
    };
    delivery: {
        method: String;
        details?: String;
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
    return {
        getCheckout,
        setCheckout,
        resetCheckout,
        getPaymentStatus,
    };
}
