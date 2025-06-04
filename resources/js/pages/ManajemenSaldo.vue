<template>
    <div :style="layoutStyle">
        <Navbar />

        <div :style="contentWrapperStyle">
            <div :style="headingContainerStyle">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="28"
                    height="28"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    :style="iconStyle"
                >
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    <path d="M9 12h6"></path>
                    <path d="M9 16h6"></path>
                    <path d="M12 8L16 12"></path>
                </svg>
                <h1 :style="headingStyle">Manajemen Saldo</h1>
            </div>
            <div :style="tabWrapperStyle">
                <button
                    :style="getTabButtonStyle('masuk')"
                    @click="activeTab = 'masuk'"
                    @mouseenter="hoveredTab = 'masuk'"
                    @mouseleave="hoveredTab = ''"
                >
                    Saldo Masuk
                </button>
                <button
                    :style="getTabButtonStyle('keluar')"
                    @click="activeTab = 'keluar'"
                    @mouseenter="hoveredTab = 'keluar'"
                    @mouseleave="hoveredTab = ''"
                >
                    Saldo Keluar
                </button>
                <button
                    :style="getTabButtonStyle('penarikan')"
                    @click="activeTab = 'penarikan'"
                    @mouseenter="hoveredTab = 'penarikan'"
                    @mouseleave="hoveredTab = ''"
                >
                    Permintaan Penarikan
                </button>
            </div>

        <!-- Dynamic Card Rendering -->
            <div v-if="activeTab === 'masuk'">
                <ul style="padding:0;">
                    <SaldoMasukCard
                        v-for="trx in saldoMasuk"
                        :key="trx.id"
                        :transaction="trx"
                    />
                </ul>
            </div>
            <div v-if="activeTab === 'keluar'">
                <ul style="padding:0;">
                    <SaldoKeluarCard
                        v-for="trx in saldoKeluar"
                        :key="trx.id"
                        :transaction="trx"
                    />
                </ul>
            </div>
            <div v-if="activeTab === 'penarikan'">
                <ul style="padding:0;">
                    <PermintaanPenarikanCard
                        v-for="trx in permintaanPenarikan"
                        :key="trx.id"
                        :transaction="trx"
                        @approve="handleApprove"
                        @reject="handleReject"
                    />
                </ul>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import {computed, onMounted, ref} from 'vue'
import Navbar from '@/components/Navbar.vue'
import { theme } from '@/helpers/theme'

import SaldoMasukCard from "@/components/SaldoMasukCard.vue"
import SaldoKeluarCard from "@/components/SaldoKeluarCard.vue"
import PermintaanPenarikanCard from "@/components/PermintaanPenarikanCard.vue"
import { WalletTransaction } from "@/interfaces/WalletTransaction"
import { useWalletTransactionApi } from "@/composables/useWalletTransactionApi"
import {useUserApi} from "@/composables/useUserApi"
import {User} from "@/interfaces/User"

const { getUsers } = useUserApi()
const { getWalletTransactions, saveWalletTransaction } = useWalletTransactionApi()
const activeTab = ref<'masuk' | 'keluar' | 'penarikan'>('masuk')
const hoveredTab = ref('')
const transactions = ref<WalletTransaction[]>([])
const users = ref<User[]>([])

const userMap = computed(() => {
    const map: Record<number, string> = {}
    users.value.forEach(u => { map[u.id] = u.name })
    return map
})

const fetchTransactions = async () => {
    try {
        transactions.value = await getWalletTransactions()
    } catch (error) {
        console.error('Error fetching transactions:', error)
    }
}

const fetchUsername = async () => {
    try {
        users.value = await getUsers()
    } catch (error) {
        console.error('Error fetching username: ', error)
    }
}

onMounted(() => {
    fetchTransactions()
    fetchUsername()
})

// Filtered
const saldoMasuk = computed(() =>
    transactions.value
        .filter(t => t.type === 'deposit')
        .sort((a, b) => new Date(b.created_at ?? '').getTime() - new Date(a.created_at ?? '').getTime())
)

const saldoKeluar = computed(() =>
    transactions.value
        .filter(t => t.type === 'withdrawal')
        .sort((a, b) => new Date(b.created_at ?? '').getTime() - new Date(a.created_at ?? '').getTime())
)

const permintaanPenarikan = computed(() =>
    transactions.value
        .filter(t => t.type === 'withdrawal' && t.status === 'waiting')
        .sort((a, b) => new Date(b.created_at ?? '').getTime() - new Date(a.created_at ?? '').getTime())
)

async function handleApprove(trx: WalletTransaction) {
    await saveWalletTransaction({ ...trx, status: 'approved' })
    await fetchTransactions()
}
async function handleReject(trx: WalletTransaction) {
    await saveWalletTransaction({ ...trx, status: 'rejected' })
    await fetchTransactions()
}

const getTabButtonStyle = (tab: string) => {
    const isActive = activeTab.value === tab
    const isHovered = hoveredTab.value === tab
    if (isActive) {
        console.log('Aktif:', tab, tabButtonActiveStyle)
    }

    if (isActive) {
        return {
            ...tabButtonBaseStyle,
            ...tabButtonActiveStyle,
        }
    } else {
        return {
            ...tabButtonBaseStyle,
            ...tabButtonInactiveStyle,
            backgroundColor: isHovered ? theme.colors.lightGrey : theme.colors.white,
        }
    }
}

const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family,
    overflowX : 'hidden'
}

const titleStyle = {
    fontWeight: theme.fonts.weight.bold,
    fontSize: theme.fonts.size.heading,
    color : theme.colors.darkGrey,
    padding : '30px'
}

const iconStyle = { color: theme.colors.darkGrey }

const headingStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.darkGrey,
    margin: 0,
}

const contentWrapperStyle = {

    maxWidth: '1200px',
    margin: '0 auto',
    // flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    padding: '20px',
}

const headingContainerStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '12px',
    marginBottom: '20px',
}



const tabWrapperStyle = {
    display: 'flex',
    maxWidth: '1200px',
    height: '50px',
    border: `1px solid ${theme.colors.primary}`,
    borderRadius: '999px',
    overflow: 'hidden',
}

const tabButtonBaseStyle = {
    flex: 1,
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    border: 'none',
    outline: 'none',
    cursor: 'pointer',
    transition: '0.3s',
}

const tabButtonActiveStyle = {
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
}

const tabButtonInactiveStyle = {
    backgroundColor: theme.colors.white,
    color: theme.colors.text,
}

const transactionIcon = {
    backgroundColor: 'transparent',
    width: '40px',
    height: '40px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    color: theme.colors.primary,
    fontSize: '20px',
    marginRight: '1rem',
}

const transactionInfo = {
    flex: 1,
}

const transactionNameInfo = {
    fontFamily: theme.fonts.family,
    fontWeight: theme.fonts.weight.bold,
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey,
    marginBottom: '4px',
}

const mutasiAmountPlusStyle = {
    color: theme.colors.primary,
    fontWeight: theme.fonts.weight.bold,
}

</script>
