<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import { useAPI } from '@/composables/useAPI';
import { formatRupiah } from '@/composables/useHelperFunctions';
import { Link } from '@inertiajs/vue3';
import { Clock, ShoppingBag } from 'lucide-vue-next';

const { getStorage } = useAPI();

defineProps<{
    id: string;
    name: string;
    price: number;
    thumbnail: string;
    slug: string;
    discount: number;
    images?: Array<string>;
    status: {
        label: string;
        isReady: boolean;
    };
    category: {
        name: string;
        id: string;
        slug: string;
        image?: string;
    }[];
}>();

</script>

<template>
    <div :key="id" class="group/card mx-auto w-full max-w-[220px] overflow-hidden">
        <div class="relative h-36 w-full overflow-hidden rounded-md xs:h-44 md:h-56">
            <Link :href="`/products/detail?slug=${slug}`">
                <img loading="lazy" :src="getStorage(thumbnail)" class="h-full w-full scale-105 object-cover group-hover/card:scale-100" alt="" />
                <div class="absolute top-1 right-0 flex w-full flex-wrap justify-end gap-1 px-1 text-xs font-medium text-background">
                    <span class="rounded-sm border border-border bg-background px-2 py-2" v-for="value in category" :key="value.id">
                        <img class="max-w-3" :src="getStorage(value.image || '')" alt="" />
                    </span>
                </div>
                <span
                    class="absolute right-2 bottom-2 flex items-center gap-1 rounded-sm border border-border bg-background px-2.5 py-0.5 text-xs font-medium text-nowrap text-foreground"
                    ><Clock class="h-3 w-3" /> {{ status.label }}</span
                ></Link
            >
        </div>
        <div class="relative space-y-2 py-3">
            <p class="w-full overflow-hidden text-xs leading-tight text-ellipsis whitespace-nowrap text-foreground md:text-base">{{ name }}</p>
            <p
                :class="discount != 0 ? 'w-fit rounded border border-primary-500 bg-primary-500/30' : ''"
                class="overflow-hidden p-1 text-xs leading-tight font-bold text-ellipsis text-foreground md:text-base"
            >
                {{ formatRupiah(price - (discount ?? 0)) }}
            </p>
        </div>
        <div class="flex w-full gap-2">
            <ButtonMain type="button" :extend-class="'!w-full !font-semibold !text-xs !py-2'" :href="`/products/detail?slug=${slug}`">
                Lihat
            </ButtonMain>
            <!-- <ButtonMain type="button" :extend-class="'!w-fit'" @click="$emit('handleAddToCart')">
                <ShoppingBag class="h-3 w-3" />
            </ButtonMain> -->
        </div>
    </div>
</template>
