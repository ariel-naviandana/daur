<template>
    <li :style="cardStyle">
        <div :style="containerStyle">
            <div v-if="isAdmin" :style="profileColumnStyle">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="35"
                    height="35"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    :style="iconStyle"
                >
                    <circle cx="12" cy="12" r="10"></circle>
                    <circle cx="12" cy="10" r="3"></circle>
                    <path d="M7 18.5c.9-2.3 2.5-3.5 5-3.5s4.1 1.2 5 3.5"></path>
                </svg>
                <div :style="userInfoStyle">
                    <p :style="usernameStyle">{{ item.user_id }}</p>
                    <p :style="dateStyle">{{ formattedDate }}</p>
                </div>
            </div>

            <div v-else :style="columnStyle">
                <p :style="dateStyle">{{ formattedDate }}</p>
            </div>

            <div :style="columnStyle">
                <span :style="[statusBadgeStyle, statusBackgroundStyle]">
                    {{ capitalizedStatus }}
                </span>
            </div>

            <div :style="[columnStyle, { justifyContent: 'flex-end' }]">
                <p :style="amountStyle">
                    Rp{{ item.total_amount.toLocaleString('id-ID') }}
                </p>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    :style="arrowStyle"
                    @click="$emit('showDetail', item)"
                >
                    <path d="M9 18l6-6-6-6"/>
                </svg>
            </div>
        </div>
    </li>
</template>

<script lang="ts" setup>
import { defineProps, computed } from 'vue'
import { theme } from '@/config/theme'
import { RecycleTransaction } from '@/interfaces/RecycleTransaction'

const props = defineProps<{ item: RecycleTransaction; isAdmin?: boolean }>()
defineEmits(['showDetail'])

const isAdmin = computed(() => props.isAdmin ?? false)

const formattedDate = computed(() => {
    const date = new Date(props.item.appointment_time)
    return date.toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    })
})

const capitalizedStatus = computed(() => {
    const status = props.item.status
    return status.charAt(0).toUpperCase() + status.slice(1)
})

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

const profileColumnStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '8px',
}

const userInfoStyle = {
    display: 'flex',
    flexDirection: 'column',
}

const usernameStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.bold,
    margin: 0,
    color: theme.colors.darkGrey,
}

const iconStyle = {
    color: theme.colors.darkGrey,
}

const columnStyle = {
    display: 'flex',
    alignItems: 'center',
}

const dateStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    margin: 0,
    textAlign: 'left',
    width: '100%',
    color: isAdmin.value ? theme.colors.grey : theme.colors.darkGrey,
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
    textTransform: 'capitalize',
    width: '100px',
    height: '24px',
    textAlign: 'center',
    lineHeight: '16px',
}

const statusBackgroundStyle = computed(() => {
    const status = props.item.status.toLowerCase()
    if (status === 'success') return { backgroundColor: theme.colors.primary }
    if (status === 'waiting') return { backgroundColor: theme.colors.yellow }
    if (status === 'process') return { backgroundColor: theme.colors.blue }
    if (status === 'cancel') return { backgroundColor: theme.colors.red }
    return { backgroundColor: theme.colors.grey }
})

const amountStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.primary,
    margin: 0,
}

const arrowStyle = {
    marginLeft: '8px',
    cursor: 'pointer',
    color: theme.colors.darkGrey,
    transition: 'color 0.2s ease',
}
</script>
