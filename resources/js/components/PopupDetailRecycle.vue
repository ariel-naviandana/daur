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
                        <div :style="dateStyle">{{ formattedDate }}</div>
                        <div :style="usernameStyle">{{ (user ? user.name : "Loading...") }}</div>
                    </div>
                </div>
                <div v-else :style="dateStyle">{{ formattedDate }}</div>
                <button @click="closeModal" :style="closeButtonStyle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <div :style="titleContainer">
                <h2 :style="titleStyle">Daftar Barang</h2>
                <span :style="[statusBadgeStyle, getStatusColor(item.status)]">{{ item.status.charAt(0).toUpperCase() + item.status.slice(1) }}</span>
            </div>

            <div :style="itemListStyle">
                <div v-for="(waste, index) in (recycleItems ? recycleItems : [])" :key="index" :style="itemStyle">
                    <div :style="itemLeftStyle">
                        <img
                            :src="getWasteTypeImage(index)"
                            alt="Item Image"
                            :style="itemImageStyle"
                        />
                        <div :style="itemTextStyle">
                            <div :style="itemNameStyle">{{ getWasteTypeName(index) }}</div>
                            <div :style="itemWeightStyle">{{ waste.quantity }} {{ getWasteTypeUnit(index) }}</div>
                        </div>
                    </div>
                    <div :style="priceStyle">Rp{{ waste.sub_total.toLocaleString('id-ID') }}</div>
                </div>
            </div>

            <div :style="totalContainerStyle">
                <div :style="totalWeightStyle">
                    <span>Total</span>
                    <span :style="[weightValueStyle, { color: theme.colors.primary }]">
                        Rp{{ item.total_amount.toLocaleString('id-ID') }}
                    </span>
                </div>
            </div>

            <div :style="pickupStyle">
                <span :style="pickupBadgeStyle">{{ formatMethod(item.method) }}</span>
            </div>

            <div :style="pickupDetailsContainer">
                <div :style="pickupDetailSection">
                    <h3 :style="sectionTitleStyle">{{ item.method == "pickup" ? "Alamat Penjemputan" : "Alamat Drop-off" }}</h3>
                    <p :style="addressStyle">
                        {{ item.method == "pickup" ? item.pickup_address : (bank ? bank.address : "Loading...") }}
                    </p>
                </div>
                <div :style="pickupDetailSection">
                    <h3 :style="sectionTitleStyle">{{ item.method == "pickup" ? "Waktu Penjemputan" : "Waktu Drop-off" }}</h3>
                    <p :style="addressStyle">{{ formattedTime }}</p>
                </div>
            </div>

            <div :style="addressContainerStyle">
                <h3 :style="sectionTitleStyle">Catatan</h3>
                <p :style="addressStyle">{{ item.note }}</p>
            </div>

            <div v-if="isAdmin && item.status === 'waiting'" :style="actionButtonsContainer">
                <button @click="handleReject" :style="rejectButtonStyle">Tolak</button>
                <button @click="handleAccept" :style="acceptButtonStyle">Terima</button>
            </div>

            <div v-if="isAdmin && item.status === 'process'" :style="actionButtonsContainer">
                <button @click="handleDone" :style="acceptButtonStyle">Selesai</button>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import {computed, onMounted, ref} from 'vue'
import { theme } from '@/helpers/theme'
import {RecycleTransactionItem} from "@/interfaces/RecycleTransactionItem"
import axios from "axios"
import {User} from "@/interfaces/User"
import {Bank} from "@/interfaces/Bank"
import {WasteType} from "@/interfaces/WasteType"

const props = defineProps<{
    isOpen: boolean
    item: RecycleTransactionItem
    isAdmin?: boolean
}>()

const bank = ref<Bank | null>(null)
const user = ref<User | null>(null)

const recycleItems = ref<RecycleTransactionItem[]>([])
const wasteTypes = ref<WasteType[]>([])

const fetchWasteTypes = async () => {
    try {
        const { data } = await axios.get('/waste-types')
        wasteTypes.value = data.map((wasteType: WasteType) => ({
            ...wasteType
        }))
    } catch (error) {
        console.error('Error fetching waste types:', error)
    }
}

const fetchRecycleItems = async () => {
    try {
        const { data } = await axios.get('/recycle-transaction-items/transaction/' + props.item.id)
        recycleItems.value = data.map((recycleItem: RecycleTransactionItem) => ({
            ...recycleItem
        }))
    } catch (error) {
        console.error('Error fetching recycle items:', error)
    }
}

const fetchBank = async () => {
    try {
        const { data } = await axios.get('/banks/' + props.item.bank_id)
        bank.value = data
    } catch (error) {
        console.error('Error fetching bank:', error)
    }
}

const fetchUser = async () => {
    try {
        const { data } = await axios.get('/users/' + props.item.user_id)
        user.value = data
    } catch (error) {
        console.error('Error fetching user:', error)
    }
}

onMounted(() => {
    fetchWasteTypes()
    fetchRecycleItems()
    fetchBank()
    fetchUser()
})

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

const formattedDate = computed(() => {
    const date = new Date(props.item.appointment_time)
    return date.toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    })
})

const formattedTime = computed(() => {
    const date = new Date(props.item.appointment_time)
    return date.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    }).replace(/\./g, ':')
})

const getStatusColor = (status: string) => {
    if (status === 'success') {
        return { backgroundColor: theme.colors.primary }
    } else if (status === 'cancel') {
        return { backgroundColor: theme.colors.red }
    } else if (status === 'process') {
        return { backgroundColor: theme.colors.blue }
    } else {
        return { backgroundColor: theme.colors.yellow }
    }
}

const formatMethod = (method: string) => {
    if (method === 'pickup') {
        return 'Pick-up'
    } else if (method === 'dropoff') {
        return 'Drop-off'
    } else {
        return method
    }
}

const getWasteTypeName = (index: number) => {
    const wasteType = wasteTypes.value?.find(wasteType => wasteType.id === recycleItems.value[index].waste_type_id)
    return wasteType ? wasteType.name : ''
}

const getWasteTypeImage = (index: number) => {
    const wasteType = wasteTypes.value?.find(wasteType => wasteType.id === recycleItems.value[index].waste_type_id)
    return wasteType ? wasteType.image : ''
}

const getWasteTypeUnit = (index: number) => {
    const wasteType = wasteTypes.value?.find(wasteType => wasteType.id === recycleItems.value[index].waste_type_id)
    return wasteType ? wasteType.unit : ''
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

const itemImageStyle = {
    width: '24px',
    height: '24px',
    borderRadius: '4px',
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
    color: theme.colors.primary,
    fontWeight: theme.fonts.weight.medium,
}

const totalContainerStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    padding: '16px 0',
    borderTop: `1px solid ${theme.colors.lightGrey}`,
    borderBottom: `1px solid ${theme.colors.lightGrey}`,
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
