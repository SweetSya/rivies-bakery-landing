<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps({
    id: String,
    name: String,
    price: Number,
    image: String,
    slug: String,
    status: {
        label: String,
        isReady: Boolean,
    },
    discount: {
        type: Number,
        default: 0,
    },
    category: {
        name: String,
    },
});

function formatRupiah(value: number) {
    const num = value?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? '';
    return 'Rp. ' + num;
}
</script>

<template>
    <Link :href="`/products/${slug}`" :key="id" class="group/card mx-auto w-full max-w-[220px] overflow-hidden">
        <div class="relative h-56 w-full overflow-hidden rounded-md">
            <img loading="lazy" src="/storage/images/logo.png" class="h-full w-full scale-105 object-cover group-hover/card:scale-100" alt="" />
            <span class="absolute top-2 right-2 rounded-sm bg-primary-700 px-2.5 py-0.5 text-xs font-medium text-background dark:bg-primary-300">{{
                category.name
            }}</span>
            <span
                :class="status.isReady ? 'bg-green-500/90' : 'bg-red-600/90'"
                class="absolute right-2 bottom-2 rounded-sm px-2.5 py-0.5 text-xs font-medium text-base-100"
                >{{ status.label }}</span
            >
        </div>
        <div class="relative space-y-2 py-3">
            <p class="w-full overflow-hidden text-base leading-tight text-ellipsis text-foreground">{{ name }}</p>
            <p
                :class="discount != 0 ? 'absolute bottom-4 left-20 text-xs line-through opacity-60' : ''"
                class="w-full overflow-hidden text-base leading-tight font-bold text-ellipsis text-foreground/80"
            >
                {{ formatRupiah(price ?? 0) }}
            </p>
            <p
                v-if="discount != 0"
                class="w-full overflow-hidden text-base leading-tight font-bold text-ellipsis text-primary-700 dark:text-primary-500"
            >
                {{ formatRupiah(price - (price * discount) / 100 ?? 0) }}
            </p>
        </div>
        <div class="flex w-full gap-2">
            <Link
                :href="`/products/${slug}`"
                class="inline-block grow-1 rounded bg-primary-600 px-3 py-1 text-center text-sm font-semibold text-base-50 hover:bg-primary-700"
                >Lihat</Link
            >
        </div>
    </Link>
</template>
