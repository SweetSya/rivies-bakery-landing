export const formatRupiah = (value: number) => {
    const num = value?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? '';
    return 'Rp ' + num;
};

export const isMobileDevice = () => {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
};
