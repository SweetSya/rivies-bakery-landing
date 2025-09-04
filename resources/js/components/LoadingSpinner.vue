<script setup lang="ts">
defineProps({
    extendClass: String,
    message: String,
});
</script>

<template>
    <div :class="extendClass" class="flex flex-col items-center justify-center gap-4">
        <div class="loader"></div>
        <p class="text-foreground/90 text-center">{{ message }}</p>
    </div>
</template>
