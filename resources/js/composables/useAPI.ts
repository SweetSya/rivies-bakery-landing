import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const page = usePage();

export function useAPI() {
    const getStorage = (url: string) => {
        const storageUrl =
            import.meta.env.VITE_APP_ENV === 'production' ? import.meta.env.VITE_API_STORAGE_PRODUCTION : import.meta.env.VITE_API_STORAGE;
        return `${storageUrl}${url}`;
    };
    const checkAuthentication = () => {
        if (page.props.isAuthed) {
        return page.props.auth.token;
        }
        return null;
    };
    const fetchAPI = (
        endpoint: string,
        config: {
            method?: 'GET' | 'POST' | 'DELETE' | 'PUT';
            data?: any;
            headers?: Record<string, string>;
        } = {
            method: 'GET',
            data: {},
            headers: {},
        },
    ) => {
        const headings = { ...config.headers };
        const AuthToken = checkAuthentication();
        if (AuthToken) {
            headings['Authorization-User'] = `Bearer ${AuthToken}`;
        }
        console.log(headings);
        // Use 'data' for GET, 'data' for others
        const axiosConfig: any = {
            method: config.method,
            url: `${endpoint}`,
            headers: headings,
            validateStatus: () => true,
        };
        // Set responseType to blob for download endpoints
        if (endpoint.includes('download')) {
            axiosConfig.responseType = 'blob';
        }
        if (config.method === 'POST') {
            axiosConfig.data = config.data;
        }
        return axios(axiosConfig);
    };
    return {
        getStorage,
        fetchAPI,
    };
}
