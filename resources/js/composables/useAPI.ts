export function useAPI() {
    const getStorage = (url: string) => {
        return `http://127.0.0.1:8000/storage/${url}`;
    };

    return {
        getStorage,
    };
}
