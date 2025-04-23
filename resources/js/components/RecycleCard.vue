<template>
    <li :style="cardStyle">
        <div :style="containerStyle">
            <div :style="columnStyle">
                <p :style="dateStyle">{{ item.date }}</p>
            </div>

            <div :style="columnStyle">
                <span :style="[statusBadgeStyle, statusBackgroundStyle]">
                    {{ item.status }}
                </span>
            </div>

            <div :style="[columnStyle, { justifyContent: 'flex-end' }]">
                <p :style="amountStyle">
                    Rp{{ item.amount.toLocaleString('id-ID') }}
                </p>
                <span style="font-size: 18px; color: #ccc; margin-left: 8px;">›</span>
            </div>
        </div>
    </li>
</template>

<script lang="ts" setup>
import { defineProps, computed } from 'vue'
import { theme } from '../config/theme'

interface HistoryItem {
    date: string
    status: string
    amount: number
}

const props = defineProps<{ item: HistoryItem }>()

const cardStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '12px',
    padding: '16px',
    marginBottom: '12px',
    boxShadow: '0 1px 2px rgba(0,0,0,0.05)',
    fontFamily: theme.fonts.family,
    color: theme.colors.darkGrey,
    listStyle: 'none',
}

const containerStyle = {
    display: 'grid',
    gridTemplateColumns: '1fr 120px 1fr',
    gap: '16px',
    alignItems: 'center',
    width: '100%',
}

const columnStyle = {
    display: 'flex',
    alignItems: 'center',
}

const dateStyle = {
    fontSize: '14px',
    fontWeight: 500,
    margin: 0,
    textAlign: 'left',
    width: '100%',
}

const statusBadgeStyle = {
    display: 'inline-flex',
    alignItems: 'center',
    justifyContent: 'center',
    padding: '4px 12px',
    borderRadius: '8px',
    fontSize: '12px',
    fontWeight: 500,
    color: '#fff',
    textTransform: 'capitalize',
    width: '100px',
    height: '24px',
    textAlign: 'center',
    lineHeight: '16px',
}

const statusBackgroundStyle = computed(() => {
    const status = props.item.status.toLowerCase()
    if (status === 'success') return { backgroundColor: '#4caf50' }
    if (status === 'waiting') return { backgroundColor: '#ff9800' }
    if (status === 'cancel') return { backgroundColor: '#f44336' }
    return { backgroundColor: theme.colors.grey }
})

const amountStyle = {
    fontSize: '14px',
    fontWeight: 600,
    color: '#2e7d32',
    margin: 0,
}
</script>
