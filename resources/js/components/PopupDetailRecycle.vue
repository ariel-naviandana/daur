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
                        <div :style="usernameStyle">{{ item.user ? item.user.name : 'Loading...' }}</div>
                    </div>
                </div>
                <div v-else :style="dateStyle">{{ formattedDate }}</div>
                <button
                    @click="closeModal"
                    @mouseover="isHoverClose = true"
                    @mouseleave="isHoverClose = false"
                    :style="[closeButtonStyle, isHoverClose ? hoverCloseStyle : {}]"
                >
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
                <div v-for="(waste, index) in (item.items || [])" :key="index" :style="itemStyle">
                    <div :style="itemLeftStyle">
                        <img
                            :src="getWasteTypeImage(waste)"
                            alt="Item Image"
                            :style="itemImageStyle"
                        />
                        <div :style="itemTextStyle">
                            <div :style="itemNameStyle">{{ getWasteTypeName(waste) }}</div>
                            <div :style="itemWeightStyle">{{ waste.quantity }} {{ getWasteTypeUnit(waste) }}</div>
                            <div v-if="waste.image" :style="proofImageContainerStyle">
                                <img
                                    :src="waste.image"
                                    alt="Proof of Waste"
                                    :style="proofImageStyle"
                                    @click="openFullScreen(waste.image)"
                                />
                            </div>
                            <div v-else :style="noImageStyle">
                                Tidak ada bukti foto
                            </div>
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
                    <h3 :style="sectionTitleStyle">{{ item.method === 'pickup' ? 'Alamat Penjemputan' : 'Alamat Drop-off' }}</h3>
                    <p :style="addressStyle">
                        {{ item.method === 'pickup' ? item.pickup_address : (item.bank ? item.bank.address : 'Loading...') }}
                    </p>
                </div>
                <div :style="pickupDetailSection">
                    <h3 :style="sectionTitleStyle">{{ item.method === 'pickup' ? 'Waktu Penjemputan' : 'Waktu Drop-off' }}</h3>
                    <p :style="addressStyle">{{ formattedTime }}</p>
                </div>
            </div>

            <div :style="{ marginBottom: '24px' }">
                <h3 :style="sectionTitleStyle">Lokasi di Map</h3>
                <div id="detail-recycle-map" style="height: 250px; width: 100%; border-radius: 8px; overflow: hidden; background: #eee;"></div>
            </div>

            <div :style="addressContainerStyle">
                <h3 :style="sectionTitleStyle">Catatan</h3>
                <p :style="addressStyle">{{ item.note || 'Tidak ada catatan' }}</p>
            </div>

            <div v-if="isAdmin && item.status === 'waiting'" :style="actionButtonsContainer">
                <button
                    @click="confirmAction('reject')"
                    :style="[rejectButtonStyle, isHoverReject ? buttonHoverStyleReject : {}]"
                    @mouseover="isHoverReject = true"
                    @mouseleave="isHoverReject = false"
                >
                    Tolak
                </button>
                <button
                    @click="confirmAction('accept')"
                    :style="[acceptButtonStyle, isHoverAccept ? buttonHoverStyleAccept : {}]"
                    @mouseover="isHoverAccept = true"
                    @mouseleave="isHoverAccept = false"
                >
                    Terima
                </button>
            </div>

            <div v-if="isAdmin && item.status === 'process'" :style="actionButtonsContainer">
                <button
                    @click="confirmAction('done')"
                    :style="[acceptButtonStyle, isHoverAccept ? buttonHoverStyleAccept : {}]"
                    @mouseover="isHoverAccept = true"
                    @mouseleave="isHoverAccept = false"
                >
                    Selesai
                </button>
            </div>

            <div v-if="!isAdmin && item.status === 'waiting'" :style="actionButtonsContainer">
                <button
                    @click="confirmAction('reject')"
                    :style="[rejectButtonStyle, isHoverReject ? buttonHoverStyleReject : {}]"
                    @mouseover="isHoverReject = true"
                    @mouseleave="isHoverReject = false"
                >
                    Batalkan
                </button>
            </div>
        </div>
    </div>

    <div v-if="isFullScreenOpen" :style="fullScreenOverlayStyle">
        <div :style="fullScreenImageContainerStyle">
            <img :src="fullScreenImage" :style="fullScreenImageStyle" alt="Full Screen Proof" />
            <button @click="closeFullScreen" :style="fullScreenCloseButtonStyle">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>

    <PopupConfirm
        v-if="confirmation"
        :message="`Apakah Anda yakin ingin ${confirmation.label.toLowerCase()} transaksi ini?`"
        @confirm="handleConfirm"
        @cancel="cancelConfirm"

    />
</template>

<script lang="ts" setup>
import { computed, ref, watch, nextTick, onMounted, onUnmounted } from 'vue'
import { theme } from '@/helpers/theme'
import { RecycleTransaction } from '@/interfaces/RecycleTransaction'
import { RecycleTransactionItem } from '@/interfaces/RecycleTransactionItem'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

import PopupConfirm from './PopupConfirm.vue'
const isHoverReject = ref(false)
const isHoverAccept = ref(false)
const isHoverClose = ref(false)

const props = defineProps<{
    isOpen: boolean
    item: RecycleTransaction
    isAdmin?: boolean
}>()

const emit = defineEmits(['close', 'accept', 'reject', 'done'])

const isAdmin = computed(() => props.isAdmin ?? false)
const isFullScreenOpen = ref(false)
const item = computed(() => props.item)

const confirmation = ref<null | {
    type: 'accept' | 'reject' | 'done',
    label: string
}>(null)

const confirmAction = (type: 'accept' | 'reject' | 'done') => {
    let label = ''
    if (type === 'accept') label = 'Terima'
    else if (type === 'reject') label = isAdmin.value && item.value.status === 'waiting' ? 'Tolak' : 'Batalkan'
    else if (type === 'done') label = 'Selesaikan'
    confirmation.value = { type, label }
}

const handleConfirm = () => {
    if (!confirmation.value) return
    const type = confirmation.value.type
    if (type === 'accept') emit('accept')
    else if (type === 'reject') emit('reject')
    else if (type === 'done') emit('done')
    confirmation.value = null
}

const cancelConfirm = () => {
    confirmation.value = null
}

const fullScreenImage = ref<string>('')

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

const openFullScreen = (imageUrl: string) => {
    fullScreenImage.value = imageUrl
    isFullScreenOpen.value = true
}

const closeFullScreen = () => {
    isFullScreenOpen.value = false
    fullScreenImage.value = ''
}

const formattedDate = computed(() => {
    const date = new Date(props.item.appointment_time)
    return date.toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    })
})

const formattedTime = computed(() => {
    const date = new Date(props.item.appointment_time)
    return date.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
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
    }
    return method
}

const getWasteTypeName = (item: RecycleTransactionItem) => {
    return item.waste_type?.name || ''
}

const getWasteTypeImage = (item: RecycleTransactionItem) => {
    return item.waste_type?.image || '/images/icon_article.svg'
}

const getWasteTypeUnit = (item: RecycleTransactionItem) => {
    return item.waste_type?.unit || ''
}

let map: L.Map | null = null
let marker: L.Marker | null = null

const initMap = async () => {
    await nextTick()
    if (!props.item.latitude || !props.item.longitude) return
    if (map) return
    map = L.map('detail-recycle-map', { attributionControl: false, zoomControl: true }).setView([props.item.latitude, props.item.longitude], 15)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map)
    marker = L.marker([props.item.latitude, props.item.longitude], { draggable: false }).addTo(map)
}

const destroyMap = () => {
    if (map) {
        map.remove()
        map = null
        marker = null
    }
}

watch(
    () => props.isOpen,
    value => {
        if (value) setTimeout(() => initMap(), 200)
        else destroyMap()
    }
)

onMounted(() => {
    if (props.isOpen) setTimeout(() => initMap(), 200)
})
onUnmounted(() => {
    destroyMap()
})

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
    zIndex: 1000
}

const modalStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '16px',
    padding: '24px',
    width: '90%',
    maxWidth: '600px',
    maxHeight: '90vh',
    overflow: 'auto'
}

const headerStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: '16px'
}

const userInfoContainer = {
    display: 'flex',
    alignItems: 'center',
    gap: '12px'
}

const userIconStyle = {
    color: theme.colors.darkGrey
}

const userTextContainer = {
    display: 'flex',
    flexDirection: 'column'
}

const dateStyle = {
    color: theme.colors.darkGrey,
    fontSize: theme.fonts.size.base
}

const usernameStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.black
}

const closeButtonStyle = {
    background: 'none',
    border: 'none',
    cursor: 'pointer',
    padding: '4px',
    color: theme.colors.darkGrey
}

const titleContainer = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: '24px'
}

const titleStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    margin: 0
}

const statusBadgeStyle = {
    padding: '3px 12px',
    color: theme.colors.whiteElement,
    borderRadius: '6px',
    fontSize: theme.fonts.size.base
}

const itemListStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '16px',
    marginBottom: '24px'
}

const itemStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center'
}

const itemLeftStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '12px'
}

const itemImageStyle = {
    width: '24px',
    height: '24px',
    borderRadius: '4px'
}

const itemTextStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '4px'
}

const itemNameStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium
}

const itemWeightStyle = {
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey
}

const proofImageContainerStyle = {
    marginTop: '8px'
}

const proofImageStyle = {
    width: '60px',
    height: '60px',
    objectFit: 'cover',
    borderRadius: '4px',
    cursor: 'pointer'
}

const noImageStyle = {
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey,
    marginTop: '8px'
}

const priceStyle = {
    fontSize: theme.fonts.size.base,
    color: theme.colors.primary,
    fontWeight: theme.fonts.weight.semibold
}

const totalContainerStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    padding: '16px 0',
    borderTop: `1px solid ${theme.colors.lightGrey}`,
    borderBottom: `1px solid ${theme.colors.lightGrey}`,
    marginBottom: '24px'
}

const totalWeightStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '4px',
    color: theme.colors.darkGrey,
    fontSize: theme.fonts.size.base
}

const weightValueStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.primary
}

const pickupStyle = {
    marginBottom: '24px'
}

const pickupBadgeStyle = {
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    padding: '6px 12px',
    borderRadius: '6px',
    fontSize: theme.fonts.size.base
}

const pickupDetailsContainer = {
    display: 'grid',
    gridTemplateColumns: '2fr 1fr',
    gap: '24px',
    marginBottom: '24px'
}

const pickupDetailSection = {
    flex: 1
}

const addressContainerStyle = {
    marginBottom: '24px'
}

const sectionTitleStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.bold,
    marginBottom: '8px',
    color: theme.colors.darkGrey
}

const addressStyle = {
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey,
    margin: 0,
    lineHeight: '1.5'
}

const actionButtonsContainer = {
    display: 'flex',
    gap: '16px',
    justifyContent: 'center'
}

const buttonBaseStyle = {
    padding: '6px 28px',
    borderRadius: '30px',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.semibold,
    color: theme.colors.whiteElement,
    cursor: 'pointer',
    border: 'none'
}

const rejectButtonStyle = {
    ...buttonBaseStyle,
    backgroundColor: theme.colors.red,
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
}

const acceptButtonStyle = {
    ...buttonBaseStyle,
    backgroundColor: theme.colors.primary,
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
}

const fullScreenOverlayStyle = {
    position: 'fixed',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    backgroundColor: 'rgba(0, 0, 0, 0.8)',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    zIndex: 2000
}

const fullScreenImageContainerStyle = {
    position: 'relative',
    maxWidth: '90%',
    maxHeight: '90vh'
}

const fullScreenImageStyle = {
    width: '100%',
    height: 'auto',
    maxHeight: '90vh',
    objectFit: 'contain',
    borderRadius: '8px'
}

const fullScreenCloseButtonStyle = {
    position: 'absolute',
    top: '-30px',
    right: '-30px',
    background: 'none',
    border: 'none',
    cursor: 'pointer',
    padding: '4px',
    color: theme.colors.whiteElement
}

const buttonHoverStyleReject = {
    transform: 'scale(1.05)',
    backgroundColor: '#B5271D',
}

const buttonHoverStyleAccept = {
    transform: 'scale(1.05)',
    backgroundColor: '#2d862d',
}

const hoverCloseStyle = {
    color: theme.colors.black,
    transform: 'scale(1.05)',
}
</script>

<style scoped>
@import "leaflet/dist/leaflet.css";
::-webkit-scrollbar {
    display: none
}
</style>
