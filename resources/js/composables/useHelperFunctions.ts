export function formatRupiah(value: number) {
    const num = value?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? '';
    return 'Rp ' + num;
}
