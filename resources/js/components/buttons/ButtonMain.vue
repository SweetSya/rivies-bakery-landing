<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    id?: string;
    type: 'button' | 'submit' | 'reset';
    disabled?: boolean;
    isLoading?: boolean;
    extendClass?: string;
    href?: string;
    outline?: boolean;
}>();

const emit = defineEmits<{
    click: [event: MouseEvent];
}>();

const handleClick = (event: MouseEvent) => {
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
            'flex cursor-pointer items-center justify-center gap-2 rounded px-5 py-2.5 text-sm font-medium text-background hover:opacity-85': true,
            [props.outline
                ? 'border border-primary-500 bg-transparent text-primary-500 hover:bg-primary-500 hover:text-background dark:border-primary-400 dark:hover:bg-primary-400'
                : 'bg-primary-500 dark:bg-primary-400']: true,
            [props.extendClass || '']: true,
            'pointer-events-none opacity-70': props.disabled,
            'pointer-events-none opacity-50': props.isLoading,
        }"
    >
        <p v-show="isLoading">Loading..</p>
        <slot v-if="!isLoading" />
    </button>
    <Link
        v-if="props.href"
        :href="props.href || '#'"
        :id="props.id || 'button-' + Math.random().toString(36)"
        :type="props.type"
        :disabled="props.disabled"
        @click="handleClick"
        :class="{
            'flex cursor-pointer items-center justify-center gap-2 rounded px-5 py-2.5 text-sm font-medium text-background hover:opacity-85': true,
            [props.outline
                ? 'border border-primary-500 bg-transparent text-primary-500 hover:bg-primary-500 hover:text-background dark:border-primary-400 dark:hover:bg-primary-400'
                : 'bg-primary-500 dark:bg-primary-400']: true,
            [props.extendClass || '']: true,
            'pointer-events-none opacity-70': props.disabled,
            'portrait: opacity-50': props.isLoading,
        }"
    >
        <p v-show="isLoading">Loading..</p>
        <slot v-if="!isLoading" />
    </Link>
</template>
