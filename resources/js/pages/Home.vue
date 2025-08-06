<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

defineOptions({
    components: {
        AppLayout,
    },
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>Home</title>
            <meta name="description" content="Home page description" />
        </Head>
        Test
    </AppLayout>
</template>
