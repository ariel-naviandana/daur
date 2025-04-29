<template>
    <div v-if="isOpen" :style="overlayStyle">
        <div :style="modalStyle">
            <div :style="headerStyle">
                <div v-if="isAdmin" :style="userInfoContainer">
                    <div :style="userIconStyle">
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
                        >
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="10" r="3"></circle>
                            <path d="M7 18.5c.9-2.3 2.5-3.5 5-3.5s4.1 1.2 5 3.5"></path>
                        </svg>
                    </div>
                    <div :style="userTextContainer">
                        <div :style="dateStyle">{{ item.date }}</div>
                        <div :style="usernameStyle">{{ item.username }}</div>
                    </div>
                </div>
                <div v-else :style="dateStyle">{{ item.date }}</div>
                <button @click="closeModal" :style="closeButtonStyle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <div :style="titleContainer">
                <h2 :style="titleStyle">Daftar Barang</h2>
                <span :style="[statusBadgeStyle, getStatusColor(item.status)]">{{ item.status }}</span>
            </div>

            <div :style="itemListStyle">
                <div v-for="(waste, index) in item.items" :key="index" :style="itemStyle">
                    <div :style="itemLeftStyle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" :style="getIconStyle(waste.type)">
                            <rect x="3" y="3" width="18" height="18" rx="4" stroke="currentColor" stroke-width="2"/>
                            <path d="M7 12H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M7 8H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M7 16H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <div :style="itemTextStyle">
                            <div :style="itemNameStyle">{{ waste.name }}</div>
                            <div :style="itemWeightStyle">{{ waste.weight }} kg</div>
                        </div>
                    </div>
                    <div :style="priceStyle">Rp{{ waste.price.toLocaleString('id-ID') }}</div>
                </div>
            </div>

            <div :style="totalContainerStyle">
                <div :style="totalWeightStyle">
                    <span>Berat</span>
                    <span :style="weightValueStyle">{{ getTotalWeight }} kg</span>
                </div>
                <div :style="totalWeightStyle">
                    <span>Total</span>
                    <span :style="[weightValueStyle, { color: theme.colors.primary }]">
                        Rp{{ getTotalPrice.toLocaleString('id-ID') }}
                    </span>
                </div>
            </div>

            <div :style="pickupStyle">
                <span :style="pickupBadgeStyle">{{ item.mode }}</span>
            </div>

            <div :style="pickupDetailsContainer">
                <div :style="pickupDetailSection">
                    <h3 :style="sectionTitleStyle">Alamat Penjemputan</h3>
                    <p :style="addressStyle">{{ item.address }}</p>
                </div>
                <div :style="pickupDetailSection">
                    <h3 :style="sectionTitleStyle">Waktu Penjemputan</h3>
                    <p :style="addressStyle">{{ item.pickupTime }}</p>
                </div>
            </div>

            <div :style="addressContainerStyle">
                <h3 :style="sectionTitleStyle">Catatan</h3>
                <p :style="addressStyle">{{ item.note }}</p>
            </div>

            <div v-if="isAdmin && item.status === 'Waiting'" :style="actionButtonsContainer">
                <button @click="handleReject" :style="rejectButtonStyle">Tolak</button>
                <button @click="handleAccept" :style="acceptButtonStyle">Terima</button>
            </div>

            <div v-if="isAdmin && item.status === 'Process'" :style="actionButtonsContainer">
                <button @click="handleDone" :style="acceptButtonStyle">Selesai</button>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { theme } from '@/config/theme'

interface WasteItem {
    type: string
    name: string
    weight: number
    price: number
}

interface RecycleDetail {
    date: string
    username?: string
    status: string
    items: WasteItem[]
    address: string
    pickupTime: string
    note: string
    mode: string
}

const props = defineProps<{
    isOpen: boolean
    item: RecycleDetail
    isAdmin?: boolean
}>()

const emit = defineEmits(['close', 'accept', 'reject', 'done'])

const isAdmin = computed(() => props.isAdmin ?? false)

const closeModal = () => {
    emit('close')
}

const handleAccept = () => {
    emit('accept')
}

const handleDone = () => {
    emit('done')
}

const handleReject = () => {
    emit('reject')
}

const getTotalWeight = computed(() => {
    return props.item.items.reduce((total, item) => total + item.weight, 0)
})

const getTotalPrice = computed(() => {
    return props.item.items.reduce((total, item) => total + item.price, 0)
})

const getIconStyle = (type: string) => ({
    color: type === 'Koran' ? '#FFA726' :
        type === 'Gelas Kaca' ? theme.colors.primary : '#2196F3',
})

const getStatusColor = (status: string) => {
    if (status === 'Success') {
        return { backgroundColor: theme.colors.primary }
    } else if (status === 'Cancel') {
        return { backgroundColor: theme.colors.red }
    } else if (status === 'Process') {
        return { backgroundColor: theme.colors.blue }
    } else {
        return { backgroundColor: theme.colors.yellow }
    }
}

const overlayStyle = {
    position: 'fixed',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    zIndex: 1000,
}

const modalStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '16px',
    padding: '24px',
    width: '90%',
    maxWidth: '600px',
    maxHeight: '90vh',
    overflow: 'auto',
}

const headerStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: '16px',
}

const userInfoContainer = {
    display: 'flex',
    alignItems: 'center',
    gap: '12px',
}

const userIconStyle = {
    color: theme.colors.darkGrey,
}

const userTextContainer = {
    display: 'flex',
    flexDirection: 'column',
}

const dateStyle = {
    color: theme.colors.darkGrey,
    fontSize: theme.fonts.size.base,
}

const usernameStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.black,
}

const closeButtonStyle = {
    background: 'none',
    border: 'none',
    cursor: 'pointer',
    padding: '4px',
    color: theme.colors.darkGrey,
}

const titleContainer = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: '24px',
}

const titleStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    margin: 0,
}

const statusBadgeStyle = {
    padding: '3px 12px',
    color: theme.colors.whiteElement,
    borderRadius: '6px',
    fontSize: theme.fonts.size.base,
}

const itemListStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '16px',
    marginBottom: '24px',
}

const itemStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
}

const itemLeftStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '12px',
}

const itemTextStyle = {
    display: 'flex',
    flexDirection: 'column',
}

const itemNameStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
}

const itemWeightStyle = {
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey,
}

const priceStyle = {
    fontSize: theme.fonts.size.base,
    color: theme.colors.black,
    fontWeight: theme.fonts.weight.medium,
}

const totalContainerStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    padding: '16px 0',
    borderTop: '1px solid #E5E7EB',
    borderBottom: '1px solid #E5E7EB',
    marginBottom: '24px',
}

const totalWeightStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '4px',
    color: theme.colors.darkGrey,
    fontSize: theme.fonts.size.base,
}

const weightValueStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.primary,
}

const pickupStyle = {
    marginBottom: '24px',
}

const pickupBadgeStyle = {
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    padding: '6px 12px',
    borderRadius: '6px',
    fontSize: theme.fonts.size.base,
}

const pickupDetailsContainer = {
    display: 'grid',
    gridTemplateColumns: '2fr 1fr',
    gap: '24px',
    marginBottom: '24px',
}

const pickupDetailSection = {
    flex: 1,
}

const addressContainerStyle = {
    marginBottom: '24px',
}

const sectionTitleStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.bold,
    marginBottom: '8px',
    color: theme.colors.darkGrey,
}

const addressStyle = {
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey,
    margin: 0,
    lineHeight: '1.5',
}

const actionButtonsContainer = {
    display: 'flex',
    gap: '16px',
    justifyContent: 'center',
}

const buttonBaseStyle = {
    padding: '6px 28px',
    borderRadius: '30px',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    color: theme.colors.whiteElement,
    cursor: 'pointer',
    border: 'none',
}

const rejectButtonStyle = {
    ...buttonBaseStyle,
    backgroundColor: theme.colors.red,
}

const acceptButtonStyle = {
    ...buttonBaseStyle,
    backgroundColor: theme.colors.primary,
}
</script>

<style scoped>
::-webkit-scrollbar {
    display: none;
}
</style>
