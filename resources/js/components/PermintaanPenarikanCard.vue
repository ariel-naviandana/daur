<template>
    <li :style="cardStyle">
        <div :style="gridContainer">
            <div :style="leftSection">
                <div :style="transactionIcon">
                    <img src="/images/ic-transaction.svg" style="width: 36px; height: 36px;" />
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

            <div :style="actionWrapper">
                <button
                    :style="[acceptButton, isHoverAccept ? buttonHoverStyleAccept : {}]"
                    @mouseover="isHoverAccept = true"
                    @mouseleave="isHoverAccept = false"
                    @click="confirmAction('approve')"
                >
                    Setujui
                </button>
                <button
                    :style="[rejectButton, isHoverReject ? buttonHoverStyleReject : {}]"
                    @mouseover="isHoverReject = true"
                    @mouseleave="isHoverReject = false"
                    @click="confirmAction('reject')"
                >
                    Tolak
                </button>
            </div>
        </div>

        <PopupConfirm
            v-if="confirmation"
            :message="`Apakah Anda yakin ingin ${confirmation === 'approve' ? 'menyetujui' : 'menolak'} transaksi ini?`"
            @confirm="handleConfirm"
            @cancel="cancelConfirm"
        />
    </li>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { theme } from '@/helpers/theme'
import { WalletTransaction } from '@/interfaces/WalletTransaction'
import PopupConfirm from './PopupConfirm.vue'

const props = defineProps<{
    transaction: WalletTransaction
    userName: string
}>()

const emit = defineEmits(['approve', 'reject'])

const confirmation = ref<null | 'approve' | 'reject'>(null)
const isHoverAccept = ref(false)
const isHoverReject = ref(false)

function formatDate(dateStr?: string) {
    if (!dateStr) return '-'
    const d = new Date(dateStr)
    return d.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) +
        ', ' + d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
}

function formatRupiah(amount: number) {
    return amount.toLocaleString('id-ID')
}

const confirmAction = (action: 'approve' | 'reject') => {
    confirmation.value = action
}

const handleConfirm = () => {
    if (confirmation.value === 'approve') {
        emit('approve', props.transaction)
    } else if (confirmation.value === 'reject') {
        emit('reject', props.transaction)
    }
    confirmation.value = null
}

const cancelConfirm = () => {
    confirmation.value = null
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
    width: '100%'
}

const gridContainer = {
    display: 'grid',
    gridTemplateColumns: '3fr 3fr 2fr 2fr',
    alignItems: 'center',
    gap: '16px',
    width: '100%'
}

const leftSection = {
    display: 'flex',
    alignItems: 'center',
    gap: '16px',
    overflow: 'hidden',
    minWidth: 0
}

const transactionIcon = {
    width: '36px',
    height: '36px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    borderRadius: '50%'
}

const transactionNameInfo = {
    fontWeight: theme.fonts.weight.bold,
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey,
    margin: 0,
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis'
}

const transactionDate = {
    fontSize: theme.fonts.size.small,
    fontWeight: theme.fonts.weight.medium,
    color: theme.colors.grey,
    margin: 0
}

const tujuanTransfer = {
    fontSize: theme.fonts.size.small,
    color: theme.colors.grey,
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis'
}

const amountWrapper = {
    textAlign: 'right',
    whiteSpace: 'nowrap'
}

const mutasiAmountPlusStyle = {
    color: theme.colors.primary,
    fontWeight: theme.fonts.weight.bold,
    whiteSpace: 'nowrap',
    marginRight: '24px'
}

const actionWrapper = {
    display: 'flex',
    justifyContent: 'flex-end',
    gap: '10px'
}

const acceptButton = {
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    padding: '6px 12px',
    width: '88px',
    borderRadius: '6px',
    border: 'none',
    cursor: 'pointer',
    fontSize: theme.fonts.size.small,
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
    transition: '0.2s ease-in-out'
}

const rejectButton = {
    backgroundColor: theme.colors.red,
    color: theme.colors.whiteElement,
    padding: '6px 12px',
    width: '88px',
    borderRadius: '6px',
    border: 'none',
    cursor: 'pointer',
    fontSize: theme.fonts.size.small,
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
    transition: '0.2s ease-in-out'
}

const buttonHoverStyleAccept = {
    backgroundColor: '#2d862d',
    transform: 'scale(1.05)'
}

const buttonHoverStyleReject = {
    backgroundColor: '#B5271D',
    transform: 'scale(1.05)'
}
</script>
