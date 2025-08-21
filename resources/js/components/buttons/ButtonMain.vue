<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import LoadingSpinner from '../LoadingSpinner.vue';
const props = defineProps<{
    id?: string;
    type: 'button' | 'submit' | 'reset';
    disabled?: boolean;
    isLoading?: boolean;
    extendClass?: string;
    href?: string;
}>();
// Declare the click event
const emit = defineEmits<{
    click: [event: MouseEvent];
}>();

const handleClick = (event: MouseEvent) => {
    // Fix: use isLoading instead of loading
    if (!props.disabled && !props.isLoading) {
        emit('click', event);
    }
};
</script>

<template>
    <button
        v-if="!props.href"
        :id="props.id || 'button-' + Math.random().toString(36)"
        :type="props.type"
        :disabled="props.disabled"
        @click="handleClick"
        :class="{
            'flex cursor-pointer items-center justify-center gap-2 rounded bg-primary-500 px-5 py-2.5 text-sm font-medium text-background hover:opacity-85 focus:ring-4 focus:ring-primary-300 focus:outline-none dark:bg-primary-400 dark:focus:ring-primary-800': true,
            [props.extendClass || '']: true,
            'pointer-events-none opacity-70': props.disabled,
            'opacity-50': props.isLoading,
        }"
    >
        <LoadingSpinner :extendClass="'scale-25 h-4'" v-show="isLoading" />
        <slot v-if="!isLoading" name="content" />
    </button>
    <Link
        v-if="props.href"
        :href="props.href || '#'"
        :id="props.id || 'button-' + Math.random().toString(36)"
        :type="props.type"
        :disabled="props.disabled"
        @click="handleClick"
        :class="{
            'flex cursor-pointer items-center justify-center gap-2 rounded bg-primary-500 px-5 py-2.5 text-sm font-medium text-background hover:opacity-85 focus:ring-4 focus:ring-primary-300 focus:outline-none dark:bg-primary-400 dark:focus:ring-primary-800': true,
            [props.extendClass || '']: true,
            'pointer-events-none opacity-70': props.disabled,
            'opacity-50': props.isLoading,
        }"
    >
        <LoadingSpinner :extendClass="'scale-25 h-4'" v-show="isLoading" />
        <slot v-if="!isLoading" name="content" />
    </Link>
</template>
