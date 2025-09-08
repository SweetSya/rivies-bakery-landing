<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<{
    id?: string;
    label?: string;
    type: 'text' | 'password' | 'email' | 'number' | 'textarea' | 'custom';
    disabled?: boolean;
    placeholder?: string;
    helperText?: string;
    errorText?: string;
    prefix?: boolean;
    prefixClass?: string;
    required?: boolean;
    inputClass?: string;
}>();

const modelValue = defineModel<string | number>();

const refId = ref(props.id || 'input-' + (crypto?.randomUUID?.() ?? Math.random().toString(36)));
</script>

<template>
    <label v-if="props.label" :for="refId" class="mb-1 block text-sm font-medium">
        {{ props.label }} <span class="text-primary-500">{{ props.required ? '*' : '' }}</span>
    </label>

    <div class="relative w-full">
        <span
            v-if="props.prefix"
            class="absolute top-0 left-0 flex h-10.5 w-10.5 items-center justify-center rounded-s px-6 text-foreground"
            :class="prefixClass"
        >
            <slot name="prefix" />
        </span>
        <input
            v-if="['text', 'password', 'email', 'number'].includes(props.type)"
            v-model="modelValue"
            :id="refId"
            :type="props.type"
            :disabled="props.disabled"
            :placeholder="props.placeholder"
            :aria-invalid="!!props.errorText"
            :required="props.required"
            :aria-describedby="props.errorText ? refId + '-error' : undefined"
            :class="{
                'base-input w-full rounded': true,
                [props.inputClass || '']: true,
                '!ps-14': props.prefix,
                'pointer-events-none opacity-70': props.disabled,
            }"
        />
        <textarea
            v-if="props.type === 'textarea'"
            v-model="modelValue"
            :id="refId"
            :rows="4"
            :disabled="props.disabled"
            :placeholder="props.placeholder"
            :aria-invalid="!!props.errorText"
            :required="props.required"
            :aria-describedby="props.errorText ? refId + '-error' : undefined"
            :class="{
                'base-input w-full rounded py-2.5': true,
                [props.inputClass || '']: true,
                '!ps-14': props.prefix,
                'pointer-events-none opacity-70': props.disabled,
            }"
        ></textarea>
        <slot v-if="props.type === 'custom'" name="custom" />
        <p v-if="props.errorText" :id="refId + '-error'" class="mt-2 text-xs text-red-600 md:text-sm dark:text-red-400">
            {{ props.errorText }}
        </p>
        <p v-else-if="props.helperText" class="mt-2 text-xs text-muted-foreground md:text-sm">
            {{ props.helperText }}
        </p>
    </div>
</template>
