<script setup lang="ts">
import ButtonMain from '@/components/buttons/ButtonMain.vue';
import { useAPI } from '@/composables/useAPI';
import { useConfirmation } from '@/composables/useConfirmation';
import { formatRupiah } from '@/composables/useHelperFunctions';
import { useNotifications } from '@/composables/useNotifications';
import AccountSettings from '@/layouts/AccountSettingsLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { CalendarClock, DiamondPercent, PercentDiamond, Settings, ShoppingCart, Workflow } from 'lucide-vue-next';
import moment from 'moment';
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
    starts_at: number;
    ends_at: number;
};

type UserVoucher = {
    id: string;
    redeemed_at: number | null;
    voucher: Voucher;
};
gsap.registerPlugin(ScrollTrigger);

const user_vouchers = ref<UserVoucher[]>((page.props.vouchers as UserVoucher[]) || []);

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
            <div class="relative min-h-52">
                <Transition name="slide-right" mode="out-in">
                    <div v-if="activeTab === 'voucher'">
                        <div v-if="user_vouchers.length === 0" class="rounded-lg border bg-transparent p-4 text-center text-sm text-gray-500">
                            Kamu belum memiliki voucher
                        </div>
                        <div v-if="user_vouchers.length > 0" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div v-for="value in user_vouchers" :key="value.id" class="relative rounded-lg border bg-transparent p-4 ps-4">
                                <div class="flex items-start">
                                    <div class="w-full space-y-2 text-sm">
                                        <div
                                            class="flex flex-wrap items-center justify-between border-b pb-2 text-sm leading-none font-bold text-foreground md:text-base"
                                        >
                                            <p>{{ value.voucher.title }}</p>
                                            <p class="flex items-center gap-2 rounded bg-primary-500 px-2 py-1 text-xs font-semibold text-background">
                                                <CalendarClock class="h-4 w-4" /> {{ moment.unix(value.voucher.ends_at).format('YYYY-MM-DD') }}
                                            </p>
                                        </div>
                                        <p class="mt-1 flex items-center gap-2 text-sm font-bold text-foreground/80">
                                            <PercentDiamond class="h-4 w-4" />
                                            {{
                                                value.voucher.type === 'percentage'
                                                    ? Math.round(value.voucher.value) + '% dari Total Belanja'
                                                    : formatRupiah(Math.round(value.voucher.value)) + ' flat'
                                            }}
                                        </p>
                                        <p class="mt-1 text-xs font-normal text-foreground/80">{{ value.voucher.description }}</p>
                                    </div>
                                </div>
                                <div class="absolute right-4 bottom-3">
                                    <img
                                        class="logo mx-auto h-10 rounded-full opacity-30 dark:brightness-150"
                                        src="/storage/images/logo.png"
                                        alt="dashboard image"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </Transition>
                <Transition name="slide-left" mode="out-in">
                    <div v-if="activeTab === 'store'">
                        <div class="rounded-lg border bg-transparent p-4 text-center text-sm text-gray-500">Halaman ini sedang dalam perbaikan
                            <Settings class="mx-auto mt-4 h-10 w-10 animate-spin text-gray-400" />
                        </div>
                    </div>
                </Transition>
            </div>
        </template>
    </AccountSettings>
</template>
