<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import { useAPI } from '@/composables/useAPI';
import { useConfirmation } from '@/composables/useConfirmation';
import { useNotifications } from '@/composables/useNotifications';
import AccountSettings from '@/layouts/AccountSettingsLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { DiamondPercent, ShoppingCart } from 'lucide-vue-next';
import { Swiper } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import { ref } from 'vue';

// Register Library
Swiper.use([Navigation, Pagination]);
const { notivueError, notivueSuccess, notivueInfo } = useNotifications();
const { fetchAPI } = useAPI();
const page = usePage();

type Voucher = {
    id: string;
    code: string;
    title: string;
    description: string;
    type: 'percentage' | 'fixed';
    value: number;
    cost: number;
    starts_at: string;
    ends_at: string;
};

gsap.registerPlugin(ScrollTrigger);

const vouchers = ref<Voucher[]>((page.props.vouchers as Voucher[]) || []);

const { showConfirmation } = useConfirmation();

const activeTab = ref<'voucher' | 'store'>('voucher');

defineOptions({
    components: {
        AccountSettings,
    },
});
</script>

<template>
    <AccountSettings>
        <!-- Loading -->
        <!-- Title -->
        <template #settingsHeader>
            <span v-if="activeTab === 'voucher'">Vouchermu</span>
            <span v-if="activeTab === 'store'">Toko Point</span>
        </template>
        <!-- Description -->
        <template #settingsDescription>
            <span v-if="activeTab === 'voucher'">Kamu dapat melihat mengelola voucher disini</span>
            <span v-if="activeTab === 'store'">Kamu dapat transaksi point disini</span>
        </template>
        <!-- Content -->
        <template #settingsContent>
            <div class="mb-8 flex justify-start gap-4 border-b pb-4">
                <ButtonMain
                    id="add-new-address"
                    extendClass="grow text-xs md:text-base"
                    :outline="activeTab != 'voucher'"
                    @click="activeTab = 'voucher'"
                    type="button"
                >
                    Vouchermu <DiamondPercent class="h-4 w-4" />
                </ButtonMain>
                <ButtonMain
                    id="add-new-address"
                    extendClass="grow text-xs md:text-base"
                    :outline="activeTab != 'store'"
                    @click="activeTab = 'store'"
                    type="button"
                >
                    Toko Point <ShoppingCart class="h-4 w-4" />
                </ButtonMain>
            </div>
            <div class="relative">
                <Transition name="slide-right" mode="out-in">
                    <div v-if="activeTab === 'voucher'">
                        <div v-if="vouchers.length === 0" class="rounded-lg border bg-transparent p-4 text-center text-sm text-gray-500">
                            Kamu belum memiliki voucher
                        </div>
                    </div>
                </Transition>
                <Transition name="slide-left" mode="out-in">
                    <div v-if="activeTab === 'store'">Toko Point</div>
                </Transition>
            </div>
        </template>
    </AccountSettings>
</template>
