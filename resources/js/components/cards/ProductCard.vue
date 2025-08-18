<script setup lang="ts">
import { formatRupiah } from '@/composables/useHelperFunctions';
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps<{
    id: string;
    name: string;
    price: number;
    image: string;
    slug: string;
    status: {
        label: string;
        isReady: boolean;
    };
    discount?: number;
    category: {
        name: string;
    };
}>();
</script>

<template>
    <Link :href="`/products/${slug}`" :key="id" class="group/card mx-auto w-full max-w-[220px] overflow-hidden">
        <div class="relative h-56 w-full overflow-hidden rounded-md">
            <img loading="lazy" src="/storage/images/logo.png" class="h-full w-full scale-105 object-cover group-hover/card:scale-100" alt="" />
            <span class="absolute top-2 right-2 rounded-sm bg-primary-700 px-2.5 py-0.5 text-xs font-medium text-background dark:bg-primary-300">{{
                category.name
            }}</span>
            <span
                :class="status.isReady ? 'bg-green-500/80 backdrop-blur-md border-green-600' : 'bg-red-600/80 backdrop-blur-md border-red-600'"
                class="absolute right-2 border bottom-2 rounded-sm px-2.5 py-0.5 text-xs font-medium text-base-100"
                >{{ status.label }}</span
            >
        </div>
        <div class="relative space-y-2 py-3">
            <p class="w-full overflow-hidden text-base leading-tight text-ellipsis text-foreground">{{ name }}</p>
            <p
                :class="discount != 0 ? 'w-fit rounded border border-primary-500 bg-primary-500/30' : ''"
                class="overflow-hidden p-1 text-base leading-tight font-bold text-ellipsis text-foreground"
            >
                {{ formatRupiah(price - (price * (discount ?? 0)) / 100) }}
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
