<template>
    <div :style="layoutStyle">
        <div :style="upperSection">
            <!-- Tombol Kembali -->
            <a href="/profile">
            <button
                :style="[backButtonStyle, isHover ? buttonHoverStyle : {}]"
                @mouseover="isHover = true"
                @mouseleave="isHover = false"
                @click=""
            >
                <img src="/public/images/back-btn.svg" alt="">
                <div style="display: flex; justify-content: center; align-items: center;">
                    <span>Kembali ke profile</span>
                </div>
            </button>
            </a>

            <h1 :style="userNameStyle">{{ user?.name }}</h1>
        </div>

        <!-- Card Saldo -->
        <SaldoCard v-if="userId !== null" :userId="userId" @tarikSaldo="showModal = true" />
        <WithdrawalCard
            :visible="showModal"
            :wallet="wallet"
            @close="showModal = false"
            @submit="handleWithdraw"
        />

        <div :style="mutasiWrapperStyle">
            <div :style="mutasiHeaderStyle">
                <h2 :style="mutasiTitleStyle">Mutasi saldo</h2>
                <div :style="mutasiButtonGroupStyle">
                    <button
                        :style="[filterDownloadButtonStyle, isHoverDownload ? buttonHoverStyle : {}]"
                        @mouseover="isHoverDownload = true" @mouseleave="isHoverDownload = false"
                        @click="downloadMutasi"
                    >
                        Download
                    </button>
                    <select
                        v-model="filterMonth"
                        :style="filterDownloadButtonStyle"
                    >
                        <option value="this">Bulan ini</option>
                        <option value="prev2">2 Bulan Lalu</option>
                        <option value="prev3">3 Bulan Lalu</option>
                    </select>
                </div>
            </div>
            <div v-if="Object.keys(filteredGroupedTransactions).length">
                <div v-for="(items, group) in filteredGroupedTransactions" :key="group" style="margin-bottom:2rem;">
                    <h3 :style="mutasiTitleTextStyle">{{ group }}</h3>
                    <MutasiSaldoCard v-for="item in items" :key="item.id" :item="item" />
                </div>
            </div>
            <div v-else style="text-align:center;color:#888;padding:2rem;">
                Belum ada mutasi saldo.
            </div>
            <div v-else style="text-align:center;color:#888;padding:2rem;">
                Belum ada mutasi saldo.
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref , computed} from 'vue'
import { onMounted } from "vue";
import { theme } from '@/helpers/theme'
import { WalletTransaction } from "@/interfaces/WalletTransaction"
import { useWalletTransactionApi } from "@/composables/useWalletTransactionApi"
import { useWalletApi } from "@/composables/useWalletApi"
import { useAuthApi} from "@/composables/useAuthApi";
import SaldoCard from "@/components/SaldoCard.vue"
import WithdrawalCard from "@/components/PopupWithdrawal.vue"
import MutasiSaldoCard from "@/components/MutasiSaldoCard.vue"
import {Wallet} from "@/interfaces/Wallet"
import {useAuthStore} from "@/stores/auth"
import {User} from "@/interfaces/User"

const showModal = ref(false)
const userId = ref<number | null>(null)
const user = ref<any>(null)
const wallet = ref<Wallet | null>(null)
const transactions = ref([])
const isHover = ref(false)


const isHoverDownload = ref(false)
const isHoverFilter = ref(false)
const filterMonth = ref('this')

const { getCurrentUser } = useAuthApi()
const authStore = useAuthStore()
const { getWallet } = useWalletApi()
const { saveWalletTransaction } = useWalletTransactionApi()
const { getWalletTransactions } = useWalletTransactionApi()

onMounted(async () => {
    userId.value = user.id
    wallet.value = await getWallet(user.id)
})

onMounted(async () => {
    user.value = await authStore.user
    userId.value = user.value.id
    wallet.value = await getWallet(userId.value)
    await fetchWalletTransaction()
})

const sortedTransactions = computed(() =>
    [...transactionById.value].sort((a, b) => new Date(b.created_at).getTime() - new Date(a.created_at).getTime())
)

function formatMonthYear(dateStr: string) {
    const date = new Date(dateStr)
    // Format: Juni 2025
    return date.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' })
}
const groupedTransactions = computed(() => {
    const groups: Record<string, any[]> = {}
    for (const item of sortedTransactions.value) {
        const groupKey = formatMonthYear(item.created_at)
        if (!groups[groupKey]) groups[groupKey] = []
        groups[groupKey].push(item)
    }
    return groups
})

// --- FILTER BY BULAN ---
function getFilterMonthDateRange(filter: string) {
    const now = new Date()
    let from = new Date(now.getFullYear(), now.getMonth(), 1)
    let to = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59, 999)
    if (filter === 'prev2') {
        // 2 bulan lalu
        from = new Date(now.getFullYear(), now.getMonth() - 1, 1)
        to = new Date(now.getFullYear(), now.getMonth(), 0, 23, 59, 59, 999)
    } else if (filter === 'prev3') {
        // 3 bulan lalu
        from = new Date(now.getFullYear(), now.getMonth() - 2, 1)
        to = new Date(now.getFullYear(), now.getMonth() - 1, 0, 23, 59, 59, 999)
    }
    return { from, to }
}

const filteredGroupedTransactions = computed(() => {
    // Tanpa filter: semua bulan (jika mau)
    if (!filterMonth.value) return groupedTransactions.value

    // Filter sesuai bulan
    const { from, to } = getFilterMonthDateRange(filterMonth.value)
    const filtered: Record<string, any[]> = {}
    for (const [group, items] of Object.entries(groupedTransactions.value)) {
        // Ambil salah satu tanggal dari item untuk cek bulan
        // Atau filter per item, lebih presisi
        const filteredItems = items.filter(item => {
            const date = new Date(item.created_at)
            return date >= from && date <= to
        })
        if (filteredItems.length) {
            filtered[group] = filteredItems
        }
    }
    return filtered
})

const fetchWalletTransaction = async () => {
    transactions.value = await getWalletTransactions()
}

const transactionById = computed(() => {
    if(!wallet.value) return []
    return transactions.value.filter(t => t.wallet_id === wallet.value.id)
})

const handleWithdraw = async (payload: WalletTransaction) => {
    if (!wallet.value?.id) {
        alert("Wallet tidak ditemukan")
        return
    }
    payload.wallet_id = wallet.value.id
    payload.type = "withdrawal"
    payload.status = "waiting"

    const success = await saveWalletTransaction(payload)
    if (success) {
        showModal.value = false
        alert("Permintaan penarikan berhasil dikirim!")
        await reloadWallet()
        await fetchWalletTransaction()
    } else {
        alert("Transfer gagal")
    }
}

const reloadWallet = async () => {
    if (userId.value) {
        wallet.value = await getWallet(userId.value)
    }
}

const downloadMutasi = () => {
    alert('Download file mutasi saldo (dummy)')
}

const toggleFilter = () => {
    alert('Filter dropdown (dummy)')
}

const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family,
    padding: '2rem',
    overflow: 'hidden'
}

const backButtonStyle = {
    width: '230px',
    height: '50px',
    backgroundColor: 'transparent',
    color: theme.colors.primary,
    fontWeight: theme.fonts.weight.bold,
    border: `1px solid ${theme.colors.primary}`,
    display: 'flex',
    borderRadius: '999px',
    padding: '8px 20px',
    cursor: 'pointer',
    marginBottom: '1rem',
    gap: '10px',
    justifyContent: 'center',
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
}

const userNameStyle = {
    fontSize: theme.fonts.size.heading,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.darkGrey,
    marginBottom: '14px',
}

const upperSection = {
    maxWidth: '900px',
    margin: '0 auto',
}

const mutasiWrapperStyle = {
    maxWidth: '900px',
    margin: '0 auto',
}

const mutasiHeaderStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: '1rem',
}

const mutasiTitleStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.darkGrey,
}

const mutasiButtonGroupStyle = {
    display: 'flex',
    gap: '12px',
}

const filterDownloadButtonStyle = {
    backgroundColor: theme.colors.primary,
    color: 'white',
    border: 'none',
    borderRadius: '999px',
    padding: '8px',
    width: '130px',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    cursor: 'pointer',
    transition: '0.2s ease',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
}

const buttonHoverStyle = {
    transform: 'scale(1.05)',
}

const mutasiCardStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '12px',
    padding: '1rem',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
    boxShadow: '0 2px 8px rgba(0,0,0,0.05)',
    marginBottom: '1rem',
}

const mutasiIconWrapperStyle = {
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

const mutasiInfoStyle = {
    flex: 1,
    borderRadius: '30px'
}

const mutasiTitleTextStyle = {
    fontWeight: theme.fonts.weight.bold,
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey,
    margin: '24px 0 8px 0',
    letterSpacing: '0.5px'
}

const mutasiDateStyle = {
    fontSize: '0.85rem',
    color: '#888',
}

const mutasiAmountMinusStyle = {
    color: theme.colors.red,
    fontWeight: theme.fonts.weight.bold,
}



</script>

<style scoped></style>
