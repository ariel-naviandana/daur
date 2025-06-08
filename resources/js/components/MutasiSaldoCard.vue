<template>
    <li :style="cardStyle">
        <div :style="gridContainer">
            <!-- Kiri: Icon dan judul + tanggal -->
            <div :style="leftSection">
                <div :style="transactionIcon">
                    <img :src="iconSrc" style="width: 36px; height: 36px;" />
                </div>
                <div>
                    <p :style="transactionNameInfo">
                        {{ item.type === 'deposit' ? 'Saldo masuk' : 'Penarikan' }}
                    </p>
                    <p :style="transactionDate">{{ formattedDate }}</p>
                </div>
            </div>
            <!-- Tengah: Tujuan transfer hanya untuk withdrawal -->
            <div :style="tujuanTransfer">
                <p v-if="item.type === 'withdrawal'" style="color: #aaa; font-size: 0.95rem; margin:0;">
                    {{ item.method ? (item.method.toUpperCase() + ' — ') : '' }}
                    {{ item.account_info ?? '-' }}
                </p>
                <p v-else style="margin:0;">-</p>
            </div>
            <!-- Nominal -->
            <div :style="amountWrapper">
                <p :style="[item.type === 'deposit' ? mutasiAmountPlusStyle : mutasiAmountMinusStyle]">
                    {{ item.type === 'deposit' ? '+' : '-' }}Rp. {{ formatRupiah(item.amount) }}
                </p>
            </div>
            <!-- Status -->
            <div :style="statusWrapper">
                <span :style="[statusBadgeStyle, statusBackgroundStyle]">{{ statusText(item.status) }}</span>
            </div>
        </div>
    </li>
</template>

<script setup lang="ts">
import { computed } from "vue"
import { theme } from '@/helpers/theme'

const props = defineProps({
    item: { type: Object, required: true }
})

const iconSrc = computed(() =>
    props.item.type === 'withdrawal'
        ? '/images/money-out.svg'
        : '/images/money-in.svg'
)

const formattedDate = computed(() =>
    new Date(props.item.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) +
    ', ' + new Date(props.item.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
)

function formatRupiah(amount: number) {
    return Number(amount).toLocaleString('id-ID')
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
    marginBottom: '16px',
    fontFamily: theme.fonts.family,
    boxShadow: '0 2px 6px rgba(0,0,0,0.05)',
    listStyle: 'none',
    width: '100%',
    maxWidth: '900px'
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
    minWidth: 0,
    overflow: 'hidden'
}

const tujuanTransfer = {
    fontSize: theme.fonts.size.small,
    color: theme.colors.darkGrey,
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
    fontSize: theme.fonts.size.small,
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
    marginRight: '24px',
}

const mutasiAmountMinusStyle = {
    color: theme.colors.red,
    fontWeight: theme.fonts.weight.bold,
    whiteSpace: 'nowrap',
    marginRight: '24px',
}

const statusBadgeStyle = {
    display: 'inline-flex',
    alignItems: 'center',
    justifyContent: 'center',
    padding: '0 12px',
    borderRadius: '10px',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    color: theme.colors.whiteElement,
    background: theme.colors.primary,
    textTransform: 'capitalize',
    width: '100px',
    height: '28px',
    textAlign: 'center',
    lineHeight: '16px',
}

const statusBackgroundStyle = computed(() => {
    const status = (props.item.status || '').toLowerCase()
    if (status === 'approved') return { backgroundColor: theme.colors.primary }
    if (status === 'waiting') return { backgroundColor: theme.colors.yellow }
    if (status === 'rejected') return { backgroundColor: theme.colors.red }
    return { backgroundColor: theme.colors.grey }
})
</script>
