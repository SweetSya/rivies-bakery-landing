<script setup lang="ts">
import { useCart, type CartItem } from '@/composables/useCart';
import { formatRupiah } from '@/composables/useHelperFunctions';
import { Link } from '@inertiajs/vue3';
import { Minus, Plus, Trash2 } from 'lucide-vue-next';

const { setCartQuantity, removeFromCart } = useCart();

const props = defineProps<CartItem>();
</script>

<template>
    <div :key="props.id" class="rounded-lg border border-base-400/20 bg-base-50/5 p-4 shadow-sm md:p-6">
        <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
            <a href="#" class="w-20 shrink-0 md:order-1">
                <img class="h-20 w-20 rounded-lg object-cover" :src="props.image" alt="imac image" />
            </a>

            <label for="counter-input" class="sr-only">Choose quantity:</label>
            <div class="flex items-center justify-between md:order-3 md:justify-end">
                <div class="flex items-center">
                    <button
                        @click="setCartQuantity(props.id, props.quantity - 1)"
                        type="button"
                        class="inline-flex h-5 w-5 shrink-0 cursor-pointer items-center justify-center rounded-md border border-gray-300 hover:bg-foreground/10 focus:ring-2 focus:ring-gray-100 focus:outline-none dark:border-gray-600 dark:focus:ring-gray-700"
                    >
                        <Minus class="h-5 w-5" />
                    </button>
                    <input
                        type="number"
                        max="99"
                        min="1"
                        class="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:ring-0 focus:outline-none dark:text-white"
                        placeholder=""
                        :value="props.quantity"
                        required
                        @change="setCartQuantity(props.id, parseInt(($event.target as HTMLInputElement)?.value ?? '1'))"
                    />
                    <button
                        @click="setCartQuantity(props.id, props.quantity + 1)"
                        type="button"
                        class="inline-flex h-5 w-5 shrink-0 cursor-pointer items-center justify-center rounded-md border border-gray-300 hover:bg-foreground/10 focus:ring-2 focus:ring-gray-100 focus:outline-none dark:border-gray-600 dark:focus:ring-gray-700"
                    >
                        <Plus class="h-5 w-5" />
                    </button>
                </div>
                <div class="text-end md:order-4 md:w-32">
                    <p class="text-base font-bold text-gray-900 dark:text-white">
                        {{ formatRupiah(props.price * props.quantity - props.price * props.quantity * (props.discount / 100)) }}
                    </p>
                    <sup class="text-primary-600 line-through">
                        {{ formatRupiah(props.price * props.quantity) }}
                    </sup>
                </div>
            </div>

            <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                <Link :href="`/products/${props.slug}`" class="text-base font-medium text-gray-900 hover:underline dark:text-white">{{ name }}</Link>
                <div class="relative z-0">
                    <input
                        type="text"
                        id="floating_standard"
                        class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-1 text-xs text-gray-900 focus:border-blue-600 focus:ring-0 focus:outline-none dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                        placeholder="Catatan.."
                    />
                </div>
            </div>
            <div>
                <div class="flex w-full items-center justify-end gap-4">
                    <button
                        @click="removeFromCart(props)"
                        type="button"
                        class="inline-flex cursor-pointer items-center text-sm font-medium text-primary-700 hover:underline hover:opacity-80 dark:text-primary-500"
                    >
                        <Trash2 class="me-1.5 h-5 w-5" />
                    </button>
                </div>
        </div>
        </div>
    </div>
</template>
