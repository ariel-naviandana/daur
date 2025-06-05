<template>
    <li :style="cardStyle">
        <div :style="gridContainer">
            <div :style="leftSection">
                <div :style="transactionIcon">
                    <img src="/public/images/ic-transaction.svg" style="width: 36px; height: 36px;" />
                </div>
                <div>
                    <p :style="transactionNameInfo">{{ userName }}</p>
                    <p :style="transactionDate">{{ formatDate(props.transaction.created_at) }}</p>
                </div>
            </div>

            <div :style="tujuanTransfer">
                <p style="color: #aaa; fontSize: 0.85rem;">
                    {{ transaction.method ? (transaction.method.toUpperCase() + ' — ') : '' }}
                    {{ transaction.account_info ?? '-' }}
                </p>
            </div>

            <div :style="amountWrapper">
                <p :style="mutasiAmountPlusStyle">Rp. {{ formatRupiah(props.transaction.amount) }}</p>
            </div>

            <div :style="statusWrapper">
                <span :style="[statusBadgeStyle, statusBackgroundStyle]">{{ statusText(props.transaction.status) }}</span>
            </div>
        </div>
    </li>
</template>

<script lang="ts" setup>
import {computed, ref} from "vue";
import { theme } from '@/helpers/theme'
import { WalletTransaction } from "@/interfaces/WalletTransaction"

const props = defineProps<{ transaction: WalletTransaction, userName: string }>()
function formatDate(dateStr?: string) {
    if (!dateStr) return '-'
    const d = new Date(dateStr)
    return d.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) +
        ', ' + d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
}

function formatRupiah(amount: number) {
    return amount.toLocaleString('id-ID')
}

function statusText(status: string) {
    if (status === 'approved') return 'Approved'
    if (status === 'rejected') return 'Rejected'
    return 'Waiting'
}

const cardStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '16px',
    padding: '16px 24px',
    marginTop: '20px',
    marginBottom: '16px',
    fontFamily: theme.fonts.family,
    boxShadow: '0 2px 6px rgba(0,0,0,0.05)',
    listStyle: 'none',
    width: '100%',
}

const gridContainer = {
    display: 'grid',
    gridTemplateColumns: '3fr 3fr 2fr 2fr',
    alignItems: 'center',
    gap: '16px',
    width: '100%',
}

const leftSection = {
    display: 'flex',
    alignItems: 'center',
    gap: '16px',
    overflow: 'hidden',
    minWidth: 0,
}

const tujuanTransfer = {
    fontSize: '0.85rem',
    color: theme.colors.grey,
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis',
}

const transactionIcon = {
    width: '36px',
    height: '36px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    borderRadius: '50%',
}

const transactionNameInfo = {
    fontWeight: theme.fonts.weight.bold,
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey,
    margin: 0,
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis',
}

const transactionDate = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    color: theme.colors.grey,
    margin: 0,
}

const amountWrapper = {
    textAlign: 'right',
    whiteSpace: 'nowrap',
}

const statusWrapper = {
    display: 'flex',
    justifyContent: 'flex-end',
}

const mutasiAmountPlusStyle = {
    color: theme.colors.primary,
    fontWeight: theme.fonts.weight.bold,
    whiteSpace: 'nowrap',
    marginRight: '24px', // Tambahkan ini
}

const statusBadgeStyle = {
    display: 'inline-flex',
    alignItems: 'center',
    justifyContent: 'center',
    padding: '14px 4px',
    borderRadius: '10px',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    color: theme.colors.whiteElement,
    background: theme.colors.primary,
    textTransform: 'capitalize',
    width: '100px',
    height: '24px',
    textAlign: 'center',
    lineHeight: '16px',
}

const statusBackgroundStyle = computed(() => {
    const status = props.transaction.status.toLowerCase()
    if (status === 'approved') return { backgroundColor: theme.colors.primary }
    if (status === 'waiting') return { backgroundColor: theme.colors.yellow }
    if (status === 'rejected') return { backgroundColor: theme.colors.red }
    return { backgroundColor: theme.colors.grey }
})

const methodStyle = {
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.darkGrey,
    marginRight: '4px',
}

const accountInfoStyle = {
    fontWeight: 'normal', // atau theme.fonts.weight.regular kalau ada
    color: theme.colors.grey,
}
</script>
