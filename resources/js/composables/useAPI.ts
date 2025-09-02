import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const page = usePage();

export function useAPI() {
    const getStorage = (url: string) => {
        return `http://127.0.0.1:8000/storage/${url}`;
    };
    const checkAuthentication = () => {
        if (page.props.isAuth) {
            return page.props.jwt_token;
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
            headings['Authorization'] = `Bearer ${AuthToken}`;
        }
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
