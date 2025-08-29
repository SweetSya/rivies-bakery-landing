import { ref } from 'vue';
import { useNotifications } from './useNotifications';

const { notivueSuccess, notivueError, notivueInfo } = useNotifications();

export type CartItem = {
    id: string;
    name: string;
    price: number;
    image: string;
    slug: string;
    discount: number;
    status: {
        label: string;
        isReady: boolean;
    };
    category: {
        name: string;
        id: string;
        slug?: string;
    }[];
    quantity: number;
    notes?: string;
};
type Cupon = {
    code: string;
    type: string | 'percentage' | 'fixed';
    discount: number;
};
export type Cart = {
    items: CartItem[];
    cupon: Cupon;
    tax: number;
    total: number;
    discount: {
        product: number;
        cupon: number;
    };
    grandTotal: number;
};
const cart = ref<Cart>({
    items: [],
    total: 0,
    tax: 0,
    cupon: {
        code: '',
        type: '',
        discount: 0,
    },
    discount: {
        product: 0,
        cupon: 0,
    },
    grandTotal: 0,
});
export function useCart() {
    const getCart = () => {
        return cart.value;
    };
    const calculateCartTotal = () => {
        const temp = {
            total: 0,
            discount: {
                product: 0,
                cupon: 0,
            },
            grandTotal: 0,
        };

        cart.value.items.forEach((item) => {
            temp.total += (item.price - item.discount) * item.quantity;
            temp.discount.product += item.discount * item.quantity;
        });
        // Check for coupon
        if (cart.value.cupon.code) {
            if (cart.value.cupon.type === 'percentage') {
                temp.discount.cupon = (temp.total * cart.value.cupon.discount) / 100;
            } else if (cart.value.cupon.type === 'fixed') {
                temp.discount.cupon = cart.value.cupon.discount;
            }
            temp.total -= temp.discount.cupon;
        }

        cart.value.total = temp.total;
        cart.value.discount = temp.discount;
        cart.value.grandTotal = temp.total + (temp.total * cart.value.tax) / 100;
    };
    const addToCart = (item: CartItem) => {
        if (isProductInCart(item.id)) {
            notivueInfo(`${item.name} sudah ada di keranjang.`);
            return;
        }
        cart.value.items.push(item);
        saveCartToStorage();
        notivueSuccess(`${item.name} telah ditambahkan ke keranjang.`);
    };
    const setCartQuantity = (id: string, quantity: number) => {
        const item = cart.value.items.find((i) => i.id === id);
        if (item) {
            if (quantity < 1) {
                quantity = 1;
            }
            if (quantity > 99) {
                quantity = 99;
            }
            item.quantity = quantity;
            saveCartToStorage();
        }
    };
    const removeFromCart = (item: CartItem) => {
        cart.value.items = cart.value.items.filter((i) => i.id !== item.id);
        saveCartToStorage();
        notivueSuccess(`Produk [${item.name}] berhasil dihapus dari keranjang.`);
    };
    const applyCupon = (cupon: Cupon) => {
        cart.value.cupon = {
            code: cupon.code,
            type: cupon.type,
            discount: cupon.discount,
        };
        if (cupon.type === 'percentage') {
            cart.value.total -= (cart.value.total * cupon.discount) / 100;
        } else if (cupon.type === 'fixed') {
            cart.value.total -= cupon.discount;
        }
        if (cupon.code != '') {
            notivueSuccess(`Kode [${cupon.code}] berhasil diterapkan.`);
        }
        saveCartToStorage();
    };
    const resetCart = () => {
        cart.value = {
            items: [],
            tax: 0,
            cupon: {
                code: '',
                type: '',
                discount: 0,
            },
            total: 0,
            discount: {
                product: 0,
                cupon: 0,
            },
            grandTotal: 0,
        };
        saveCartToStorage();
    };
    const saveCartToStorage = () => {
        calculateCartTotal();
        localStorage.setItem('cart', JSON.stringify(cart.value));
    };
    const initializeCart = () => {
        const storedCart = localStorage.getItem('cart');
        if (storedCart) {
            cart.value = JSON.parse(storedCart);
        }
    };
    const getCartTotalItem = () => {
        return cart.value.items.length;
    };
    const isCartEmpty = () => {
        return cart.value.items.length === 0;
    };
    const isProductInCart = (id: string) => {
        return cart.value.items.some((item) => item.id === id);
    };
    return {
        isCartEmpty,
        isProductInCart,
        getCart,
        addToCart,
        removeFromCart,
        resetCart,
        setCartQuantity,
        initializeCart,
        applyCupon,
        getCartTotalItem,
    };
}
