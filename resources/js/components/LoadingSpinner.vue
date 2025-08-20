<script setup lang="ts">
defineProps({
    extendClass: String,
    message: String,
});
</script>

<template>
    <div :class="extendClass" class="flex gap-4 flex-col items-center justify-center">
        <div class="loader"></div>
        <p class="text-foreground/90">{{ message }}</p>
    </div>
</template>
