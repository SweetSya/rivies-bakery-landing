import { ref } from 'vue';

declare global {
    interface Window {
        snap: any;
    }
}

export function useMidtrans() {
    const snapLoaded = ref(false);
    const snapLoading = ref(false);

    const loadMidtrans = () => {
        return new Promise((resolve, reject) => {
            if (window.snap) {
                snapLoaded.value = true;
                resolve(window.snap);
                return;
            }

            if (snapLoading.value) return;

            snapLoading.value = true;
            const script = document.createElement('script');
            script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
            script.type = 'text/javascript';
            script.setAttribute('data-client-key', import.meta.env.VITE_MIDTRANS_CLIENT_KEY || '');

            script.onload = () => {
                snapLoading.value = false;
                resolve(window.snap);
            };

            script.onerror = () => {
                snapLoading.value = false;
                reject(new Error('Failed to load Midtrans Snap.js'));
            };

            document.head.appendChild(script);
        });
    };

    const pay = async (snapToken: string, callbacks?: any) => {
        try {
            await loadMidtrans();
            return window.snap.pay(snapToken, callbacks);
        } catch (error) {
            console.error('Midtrans payment error:', error);
            throw error;
        }
    };

    return {
        snapLoaded,
        snapLoading,
        loadMidtrans,
        pay,
    };
}
