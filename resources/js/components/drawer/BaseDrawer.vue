<script setup lang="ts">
import { Drawer, DrawerInterface, DrawerOptions } from 'flowbite';
import { X } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import LoadingSpinner from '../LoadingSpinner.vue';

const props = defineProps<{
    id: string;
    title: string;
    isLoading?: boolean;
    position: 'bottom' | 'left' | 'right';
    loading?: {
        extendClass: string;
        message: string;
    };
}>();

const drawer = ref<DrawerInterface | null>(null);

const open = () => {
    drawer.value?.show();
};
const close = () => {
    drawer.value?.hide();
};

const classPosition = ref<string>('');
const classTitle = ref<string>('');
switch (props.position) {
    case 'bottom':
        classTitle.value = 'rounded-t-lg';
        classPosition.value =
            'fixed !w-full md:!w-[620px] right-0 bottom-0 left-0 z-40 w-full translate-y-full items-center justify-center overflow-y-auto transition-transform rounded-t-lg bg-background shadow-md shadow-foreground/10';
        break;
    case 'left':
        classTitle.value = 'rounded-tr-lg';
        classPosition.value =
            'fixed !w-full md:!w-[620px] top-0 left-0 z-40 h-screen overflow-y-auto transition-transform -translate-x-full rounded-r-lg bg-background shadow-md shadow-foreground/10';
        break;
    case 'right':
        classTitle.value = 'rounded-tl-lg';
        classPosition.value =
            'fixed !w-full md:!w-[620px] top-0 right-0 z-40 h-screen overflow-y-auto transition-transform translate-x-full rounded-l-lg bg-background shadow-md shadow-foreground/10';
        break;
}
onMounted(() => {
    const drawerElement = document.getElementById(props.id);

    if (drawerElement) {
        const drawerOptions: DrawerOptions = {
            placement: props.position ?? 'left',
            backdrop: true,
            bodyScrolling: false,
            edge: false,
            edgeOffset: '',
            backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-30',
            onHide: () => {
                // console.log('drawer is hidden');
            },
            onShow: () => {
                // console.log('drawer is shown');
            },
            onToggle: () => {
                // console.log('drawer has been toggled');
            },
        };

        drawer.value = new Drawer(drawerElement, drawerOptions);
    }
});
defineExpose({
    open,
    close,
});
</script>

<template>
    <div :id="id" tabindex="-1" :class="classPosition">
        <div
            :class="classTitle"
            class="flex items-center justify-between border-b border-gray-200 bg-primary-400 p-3 md:p-4 dark:border-gray-600 dark:bg-primary-600"
        >
            <h3 class="flex items-center gap-4 text-lg font-extrabold text-background"><slot name="icon" /> {{ title }}</h3>
            <X class="h-5 w-5 cursor-pointer text-background hover:opacity-80" @click="close()" />
        </div>

        <LoadingSpinner
            :extendClass="props.loading?.extendClass ?? 'h-40'"
            :message="props.loading?.message ?? 'Sedang memproses..'"
            v-show="isLoading"
        />
        <div v-show="!isLoading" class="p-4 md:p-5">
            <slot name="content" />
        </div>
    </div>
</template>
