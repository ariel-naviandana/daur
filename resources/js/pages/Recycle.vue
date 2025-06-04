<template>
    <div :style="layoutStyle">
        <Navbar />
        <div :style="mainContentStyle">
            <CategoryList @category-clicked="openPopup" />

            <div :style="cartContainerStyle">
                <h2 :style="sectionTitleStyle">
                    <span :style="cartIconStyle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M9 22C9.55228 22 10 21.5523 10 21C10 20.4477 9.55228 20 9 20C8.44772 20 8 20.4477 8 21C8 21.5523 8.44772 22 9 22Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M20 22C20.5523 22 21 21.5523 21 21C21 20.4477 20.5523 20 20 20C19.4477 20 19 20.4477 19 21C19 21.5523 19.4477 22 20 22Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M1 1H5L7.68 14.39C7.77144 14.8504 8.02191 15.264 8.38755 15.5583C8.75318 15.8526 9.2107 16.009 9.68 16H19.4C19.8693 16.009 20.3268 15.8526 20.6925 15.5583C21.0581 15.264 21.3086 14.8504 21.4 14.39L23 6H6" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </span>
                    Keranjang
                </h2>

                <div :style="cartItemsContainerStyle">
                    <div v-for="item in cartItems" :key="item.waste_type_id" :style="cartItemStyle">
                        <div :style="itemLeftStyle">
                            <img :src="getWasteTypeImage(item.waste_type_id)" :alt="getWasteTypeName(item.waste_type_id)" :style="itemIconStyle" />
                            <div :style="itemDetailsStyle">
                                <span :style="itemNameStyle">{{ getWasteTypeName(item.waste_type_id) }}</span>
                                <div :style="weightContainerStyle">
                                    <button :style="minusButtonStyle" @click="updateWeight(item.waste_type_id, -1)">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                            <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                    <div :style="weightBadgeStyle">{{ item.quantity }} {{ getWasteTypeUnit(item.waste_type_id) }}</div>
                                    <button :style="plusButtonStyle" @click="updateWeight(item.waste_type_id, 1)">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                            <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                                <div :style="formGroupStyle">
                                    <label :style="labelStyle">Bukti Foto Sampah (Wajib)</label>
                                    <input
                                        type="file"
                                        accept="image/*"
                                        @change="handleFileChange($event, item.waste_type_id)"
                                        :style="inputStyle"
                                        :disabled="isUploading.value"
                                        required
                                    />
                                    <div v-if="item.image || previewImages[item.waste_type_id]" :style="previewContainerStyle">
                                        <img :src="item.image || previewImages[item.waste_type_id]" :style="previewImageStyle" />
                                    </div>
                                    <div v-if="isUploading.value && uploadingItemId.value === item.waste_type_id" :style="uploadingStyle">
                                        Mengunggah...
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span :style="priceStyle">Rp{{ item.sub_total.toLocaleString('id-ID') }}</span>
                    </div>
                </div>

                <div :style="totalContainerStyle">
                    <div :style="totalRowStyle">
                        <span>Total</span>
                        <span :style="totalPriceStyle">Rp{{ totalPrice.toLocaleString('id-ID') }}</span>
                    </div>
                </div>
            </div>

            <div :style="bookingContainerStyle">
                <h2 :style="sectionTitleStyle">
                    <span :style="bookingIconStyle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M19 4H5C3.89543 4 3 4.89543 3 6V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V6C21 4.89543 20.1046 4 19 4Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M16 2V6M8 2V6M3 10H21" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </span>
                    Booking
                </h2>

                <div :style="pickupTypeStyle">
                    <button
                        :style="getButtonStyle(true)"
                        @click="isPickup = true"
                        @mouseover="hoverPickup = true"
                        @mouseleave="hoverPickup = false"
                    >
                        Pick-up
                    </button>

                    <button
                        :style="getButtonStyle(false)"
                        @click="isPickup = false"
                        @mouseover="hoverDropoff = true"
                        @mouseleave="hoverDropoff = false"
                    >
                        Drop-off
                    </button>
                </div>

                <form @submit.prevent="openConfirmBookingPopup" :style="formStyle">
                    <div style="margin: 18px 0;">
                        <div v-if="isPickup" style="height: 250px; width: 100%; border-radius: 8px; overflow: hidden; background: #eee;">
                            <div id="pickup-map" style="height: 100%; width: 100%;"></div>
                        </div>
                        <div v-else style="height: 250px; width: 100%; border-radius: 8px; overflow: hidden; background: #eee;">
                            <div id="dropoff-map" style="height: 100%; width: 100%;"></div>
                        </div>
                    </div>
                    <div :style="formRowStyle">
                        <div :style="{ ...formGroupStyle, flex: 1.5, position: 'relative' }">
                            <label :style="labelStyle">
                                {{ isPickup ? 'Alamat Penjemputan' : 'Pilih Alamat Drop-off' }}
                            </label>
                            <div>
                                <input
                                    v-if="isPickup"
                                    v-model="addressInput"
                                    type="text"
                                    :style="inputStyle"
                                    @input="onAddressInput"
                                    @focus="showAutocomplete = true"
                                    @blur="onAddressBlur"
                                    @keydown.enter.prevent="searchAddress"
                                    placeholder="Cari atau klik map"
                                    autocomplete="off"
                                    ref="addressInputRef"
                                    required
                                />
                                <ul v-if="isPickup && showAutocomplete && addressSuggestions.length" :style="autocompleteListStyle">
                                    <li
                                        v-for="(suggestion, idx) in addressSuggestions"
                                        :key="idx"
                                        :style="autocompleteItemStyle"
                                        @mousedown.prevent="selectSuggestion(suggestion)"
                                    >
                                        {{ suggestion.formatted }}
                                    </li>
                                </ul>
                                <select
                                    v-if="!isPickup"
                                    v-model="selectedDropOff"
                                    :style="selectStyle"
                                    required
                                >
                                    <option disabled value="">Pilih alamat drop-off</option>
                                    <option v-for="location in dropOffLocations" :key="location.id" :value="location">
                                        {{ location.name }} - {{ location.address }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div :style="{ ...formGroupStyle, flex: 1 }">
                            <label :style="labelStyle">{{ isPickup ? 'Waktu Penjemputan (09:00–17:00)' : 'Waktu Drop-off (09:00–17:00)' }}</label>
                            <input
                                v-model="pickupTime"
                                type="datetime-local"
                                :min="minDateTime"
                                :max="maxDateTime"
                                step="300"
                                :style="inputStyle"
                                required
                            />
                            <div :style="{ fontSize: theme.fonts.size.small, color: theme.colors.darkGrey, marginTop: '4px' }">
                                Waktu harus antara 09:00–17:00. Jika hari ini, minimal 2 jam dari sekarang.
                            </div>
                        </div>
                    </div>
                    <div :style="formGroupStyle">
                        <label :style="labelStyle">Catatan</label>
                        <textarea
                            v-model="note"
                            :style="textareaStyle"
                            placeholder="Tambahkan catatan untuk driver"
                        ></textarea>
                    </div>
                    <button
                        type="submit"
                        @mouseover="isHover = true"
                        @mouseleave="isHover = false"
                        :style="[submitButtonStyle, isHover ? buttonHoverStyle : {}]"
                        :disabled="isUploading.value"
                    >
                        Konfirmasi
                    </button>
                </form>
            </div>
        </div>
        <PopupDetailSampah
            :isOpen="isPopupOpen"
            :selectedCategory="selectedCategory"
            @close="closePopup"
            @add="handleAddItem"
        />
        <PopupDelete
            v-if="isConfirmDeleteOpen"
            :is-open="isConfirmDeleteOpen"
            :item-name="itemToDelete?.name || ''"
            @confirm="confirmDelete"
            @close="closeConfirmPopup"
        />
        <PopupConfirm
            v-if="isConfirmBookingOpen"
            :is-open="isConfirmBookingOpen"
            title="Konfirmasi Booking"
            message="Apakah Anda yakin ingin melanjutkan booking ini?"
            confirmText="Ya"
            cancelText="Batal"
            @confirm="handleConfirmBooking"
            @close="closeConfirmBookingPopup"
        />
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch, nextTick, onUnmounted } from 'vue'
import { theme } from '@/helpers/theme'
import Navbar from '@/components/Navbar.vue'
import CategoryList from '@/components/CategoryList.vue'
import PopupDetailSampah from '@/components/PopupDetailSampah.vue'
import PopupDelete from '@/components/PopupDelete.vue'
import PopupConfirm from '@/components/PopupConfirm.vue'
import { useWasteTypeApi } from '@/composables/useWasteTypeApi'
import { useRecycleTransactionApi } from '@/composables/useRecycleTransactionApi'
import { useImageApi } from '@/composables/useImageApi'
import { Bank } from '@/interfaces/Bank'
import { Category } from '@/interfaces/Category'
import { RecycleTransaction } from '@/interfaces/RecycleTransaction'
import { RecycleTransactionItem } from '@/interfaces/RecycleTransactionItem'
import { WasteType } from '@/interfaces/WasteType'
import { User } from "@/interfaces/User"
import {useAuthStore} from "@/stores/auth"
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

delete (L.Icon.Default.prototype as any)._getIconUrl
L.Icon.Default.mergeOptions({
    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41],
})

const authStore = useAuthStore()

const { getWasteTypes } = useWasteTypeApi()
const { saveRecycleTransaction } = useRecycleTransactionApi()
const { isUploading, uploadToCloudinary } = useImageApi()

const isConfirmBookingOpen = ref(false)
const isPickup = ref(true)
const pickupTime = ref('')
const note = ref('')
const isPopupOpen = ref(false)
const selectedCategory = ref<Category | null>(null)
const cartItems = ref<RecycleTransactionItem[]>([])
const dropOffLocations = ref<Bank[]>([])
const selectedDropOff = ref<Bank | null>(null)
const wasteTypes = ref<WasteType[]>([])
const user = ref<User | null>(null)
const previewImages = ref<{ [key: number]: string }>({})
const uploadingItemId = ref<number | null>(null)
const isHover = ref(false)
const hoverPickup = ref(false)
const hoverDropoff = ref(false)

function getButtonStyle(isPick: boolean) {
    const active = isPick ? isPickup.value : !isPickup.value
    const hover = isPick ? hoverPickup.value : hoverDropoff.value

    if (active) {
        return {
            ...pickupButtonStyle,
            backgroundColor: theme.colors.primary,
            color: 'white',
            borderColor: theme.colors.primary,
        }
    } else if (hover) {
        return {
            ...pickupButtonStyle,
            backgroundColor: theme.colors.lightGrey,
        }
    } else {
        return {
            ...pickupButtonStyle,
            backgroundColor: 'white',
            color: theme.colors.darkGrey,
            borderColor: theme.colors.lightGrey,
        }
    }
}

const now = new Date()
const minDateTime = computed(() => {
    const minDate = new Date(now)
    minDate.setHours(minDate.getHours() + 2, 0, 0, 0)
    return formatDateTimeLocal(minDate)
})
const maxDateTime = computed(() => {
    const maxDate = new Date(now)
    maxDate.setFullYear(maxDate.getFullYear() + 1)
    maxDate.setHours(16, 55, 0, 0)
    return formatDateTimeLocal(maxDate)
})

const formatDateTimeLocal = (date: Date) => {
    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')
    const hours = String(date.getHours()).padStart(2, '0')
    const minutes = String(date.getMinutes()).padStart(2, '0')
    return `${year}-${month}-${day}T${hours}:${minutes}`
}

const formatToWIB = (date: Date) => {
    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')
    const hours = String(date.getHours()).padStart(2, '0')
    const minutes = String(date.getMinutes()).padStart(2, '0')
    const seconds = '00'
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`
}

const fetchBanks = async () => {
    try {
        const response = await fetch('/banks')
        dropOffLocations.value = await response.json()
    } catch (error) {
    }
}

const fetchWasteTypes = async () => {
    try {
        wasteTypes.value = await getWasteTypes()
    } catch (error) {
    }
}

onMounted(async () => {
    user.value = await authStore.user
    await fetchBanks()
    await fetchWasteTypes()
    setTimeout(() => {
        if (isPickup.value) initializePickupMap()
        else initializeDropoffMap()
    }, 100)
})

const openConfirmBookingPopup = () => {
    if (validateForm()) {
        isConfirmBookingOpen.value = true
    } else {
        alert('Harap isi semua input yang diperlukan sebelum melanjutkan.')
    }
}

const closeConfirmBookingPopup = () => {
    isConfirmBookingOpen.value = false
}

const validateForm = () => {
    const allItemsHaveImages = cartItems.value.every(item => item.image)
    if (!allItemsHaveImages) {
        alert('Harap unggah bukti foto untuk semua item di keranjang.')
        return false
    }
    if (isPickup.value) {
        if (addressInput.value.trim() === '') {
            alert('Harap masukkan alamat penjemputan.')
            return false
        }
    } else {
        if (!selectedDropOff.value?.id) {
            alert('Harap pilih lokasi drop-off.')
            return false
        }
    }
    if (!pickupTime.value) {
        alert('Harap pilih waktu penjemputan atau drop-off.')
        return false
    }
    const selectedDateTime = new Date(pickupTime.value)
    const today = new Date()
    const isToday = selectedDateTime.toDateString() === today.toDateString()
    const minTimeToday = new Date(today)
    minTimeToday.setHours(today.getHours() + 2, 0, 0, 0)
    const hours = selectedDateTime.getHours()
    const minutes = selectedDateTime.getMinutes()
    if (hours < 9 || (hours >= 17) || (hours === 16 && minutes > 55)) {
        alert('Waktu harus antara pukul 09:00 dan 17:00.')
        return false
    }
    if (isToday && selectedDateTime < minTimeToday) {
        alert('Untuk hari ini, waktu harus setidaknya 2 jam dari sekarang.')
        return false
    }
    return true
}

const handleConfirmBooking = () => {
    isConfirmBookingOpen.value = false
    submitTransaction()
}

const redirectToRiwayat = () => {
    window.location.href = '/riwayat'
}

const submitTransaction = async () => {
    try {
        const payload: RecycleTransaction & { items: { waste_type_id: number; quantity: number; sub_total: number; image?: string }[] } = {
            user_id: user.value!.id,
            bank_id: isPickup.value ? null : selectedDropOff.value?.id || null,
            pickup_address: isPickup.value ? addressInput.value : null,
            latitude: isPickup.value ? pickupLat.value : dropoffLat.value,
            longitude: isPickup.value ? pickupLng.value : dropoffLng.value,
            appointment_time: formatToWIB(new Date(pickupTime.value)),
            method: isPickup.value ? 'pickup' : 'dropoff',
            status: 'waiting',
            note: note.value,
            total_quantity: null,
            total_amount: totalPrice.value,
            created_at: formatToWIB(new Date()),
            updated_at: formatToWIB(new Date()),
            items: cartItems.value.map(item => ({
                waste_type_id: item.waste_type_id,
                quantity: item.quantity,
                sub_total: item.sub_total,
                image: item.image
            }))
        }

        const success = await saveRecycleTransaction(payload)
        if (success) {
            alert('Transaksi berhasil dibuat!')
            redirectToRiwayat()
        } else {
            alert('Gagal membuat transaksi. Silakan coba lagi.')
        }
    } catch (error) {
        alert('Terjadi kesalahan saat membuat transaksi. Silakan coba lagi.')
    }
}

const openPopup = (category: Category) => {
    selectedCategory.value = category
    isPopupOpen.value = true
}

const closePopup = () => {
    isPopupOpen.value = false
}

const handleAddItem = (item: RecycleTransactionItem) => {
    const existingItem = cartItems.value.find(cartItem => cartItem.waste_type_id === item.waste_type_id)

    if (existingItem) {
        existingItem.quantity += item.quantity
        existingItem.sub_total += item.sub_total
        existingItem.image = existingItem.image || item.image
    } else {
        cartItems.value.push({ ...item, name: getWasteTypeName(item.waste_type_id) })
    }
}

const handleFileChange = async (event: Event, wasteTypeId: number) => {
    const file = (event.target as HTMLInputElement).files?.[0]
    if (!file) return

    const validTypes = ['image/jpeg', 'image/png', 'image/gif']
    const maxSize = 5 * 1024 * 1024
    if (!validTypes.includes(file.type)) {
        alert('Hanya file JPEG, PNG, atau GIF yang diperbolehkan.')
        return
    }
    if (file.size > maxSize) {
        alert('Ukuran file maksimum adalah 5MB.')
        return
    }

    const reader = new FileReader()
    reader.onload = () => {
        previewImages.value[wasteTypeId] = reader.result as string
    }
    reader.readAsDataURL(file)

    uploadingItemId.value = wasteTypeId
    const imageUrl = await uploadToCloudinary(file)
    uploadingItemId.value = null

    if (imageUrl) {
        const item = cartItems.value.find(item => item.waste_type_id === wasteTypeId)
        if (item) {
            item.image = imageUrl
        }
    }
}

const totalPrice = computed(() => cartItems.value.reduce((sum, item) => sum + item.sub_total, 0))

const isConfirmDeleteOpen = ref(false)
const itemToDelete = ref<RecycleTransactionItem | null>(null)

const confirmDelete = () => {
    if (itemToDelete.value) {
        cartItems.value = cartItems.value.filter(item => item.waste_type_id !== itemToDelete.value.waste_type_id)
        delete previewImages.value[itemToDelete.value.waste_type_id]
        closeConfirmPopup()
    }
}

const closeConfirmPopup = () => {
    isConfirmDeleteOpen.value = false
    itemToDelete.value = null
}

const updateWeight = (wasteTypeId: number, change: number) => {
    const item = cartItems.value.find(item => item.waste_type_id === wasteTypeId)
    if (!item) return

    const newQuantity = item.quantity + change
    const wasteType = wasteTypes.value.find(wt => wt.id === wasteTypeId)

    if (!wasteType) {
        return
    }

    if (newQuantity > 0) {
        item.quantity = newQuantity
        item.sub_total = newQuantity * wasteType.price_per_unit
    } else {
        itemToDelete.value = { ...item, name: wasteType.name }
        isConfirmDeleteOpen.value = true
    }
}

const getWasteTypeName = (wasteTypeId: number) => {
    const wasteType = wasteTypes.value.find(wt => wt.id === wasteTypeId)
    return wasteType ? wasteType.name : ''
}

const getWasteTypeImage = (wasteTypeId: number) => {
    const wasteType = wasteTypes.value.find(wt => wt.id === wasteTypeId)
    return wasteType.image ? wasteType.image : '/images/icon_article.svg'
}

const getWasteTypeUnit = (wasteTypeId: number) => {
    const wasteType = wasteTypes.value.find(wt => wt.id === wasteTypeId)
    return wasteType ? wasteType.unit : ''
}

let pickupMap: L.Map | null = null
let dropoffMap: L.Map | null = null
let pickupMarker: L.Marker | null = null
let dropoffMarker: L.Marker | null = null

const pickupLat = ref(-6.2)
const pickupLng = ref(106.816666)
const dropoffLat = ref<number|null>(null)
const dropoffLng = ref<number|null>(null)

const OPENCAGE_API_KEY = 'e5f8659f848545f486b84de646bd104d'
const addressInput = ref('')
const addressSuggestions = ref<{ formatted: string, lat: number, lng: number }[]>([])
const showAutocomplete = ref(false)
const addressInputRef = ref<HTMLInputElement | null>(null)
let autocompleteTimeout: any = null

const geocode = async (address: string) => {
    const url = `https://api.opencagedata.com/geocode/v1/json?q=${encodeURIComponent(address)}&key=${OPENCAGE_API_KEY}&language=id&countrycode=id&limit=5`
    const res = await fetch(url)
    const data = await res.json()
    return data.results?.map((r: any) => ({
        formatted: r.formatted,
        lat: r.geometry.lat,
        lng: r.geometry.lng
    })) ?? []
}

const reverseGeocode = async (lat: number, lng: number) => {
    const url = `https://api.opencagedata.com/geocode/v1/json?q=${lat}+${lng}&key=${OPENCAGE_API_KEY}&language=id&countrycode=id&limit=1`
    const res = await fetch(url)
    const data = await res.json()
    if (data.results && data.results.length > 0) {
        return data.results[0].formatted
    }
    return ''
}

const setPickupLatLng = (lat: number, lng: number) => {
    pickupLat.value = +lat.toFixed(7)
    pickupLng.value = +lng.toFixed(7)
    pickupMarker?.setLatLng([lat, lng])
    pickupMap?.setView([lat, lng])
}

const selectSuggestion = (suggestion: { formatted: string, lat: number, lng: number }) => {
    addressInput.value = suggestion.formatted
    setPickupLatLng(suggestion.lat, suggestion.lng)
    showAutocomplete.value = false
}

const onAddressInput = async () => {
    clearTimeout(autocompleteTimeout)
    autocompleteTimeout = setTimeout(async () => {
        if (addressInput.value.length > 2) {
            const results = await geocode(addressInput.value)
            addressSuggestions.value = results
            showAutocomplete.value = true
        } else {
            addressSuggestions.value = []
            showAutocomplete.value = false
        }
    }, 300)
}

const onAddressBlur = () => {
    setTimeout(() => {
        showAutocomplete.value = false
    }, 200)
}

const searchAddress = async () => {
    if (addressInput.value.length > 2) {
        const results = await geocode(addressInput.value)
        addressSuggestions.value = results
        if (results.length > 0) {
            selectSuggestion(results[0])
        }
    }
}

const getCurrentPosition = (): Promise<{ lat: number, lng: number }> => {
    return new Promise((resolve) => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    resolve({
                        lat: pos.coords.latitude,
                        lng: pos.coords.longitude
                    })
                },
                () => {
                    resolve({
                        lat: pickupLat.value,
                        lng: pickupLng.value
                    })
                },
                { enableHighAccuracy: true, timeout: 5000 }
            )
        } else {
            resolve({
                lat: pickupLat.value,
                lng: pickupLng.value
            })
        }
    })
}

const initializePickupMap = async () => {
    await nextTick()
    if (pickupMap) return
    const pos = await getCurrentPosition()
    pickupLat.value = pos.lat
    pickupLng.value = pos.lng
    pickupMap = L.map('pickup-map').setView([pickupLat.value, pickupLng.value], 15)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(pickupMap)
    pickupMarker = L.marker([pickupLat.value, pickupLng.value], { draggable: true }).addTo(pickupMap)
    pickupMarker.on('dragend', async () => {
        const pos = pickupMarker!.getLatLng()
        setPickupLatLng(pos.lat, pos.lng)
        const addressStr = await reverseGeocode(pos.lat, pos.lng)
        if (addressStr) addressInput.value = addressStr
    })
    pickupMap.on('click', async (e: any) => {
        setPickupLatLng(e.latlng.lat, e.latlng.lng)
        const addressStr = await reverseGeocode(e.latlng.lat, e.latlng.lng)
        if (addressStr) addressInput.value = addressStr
    })
}

const initializeDropoffMap = async () => {
    await nextTick()
    if (dropoffMap) return
    let bank = selectedDropOff.value || dropOffLocations.value[0]
    let lat = bank?.latitude ?? -6.2
    let lng = bank?.longitude ?? 106.816666
    dropoffLat.value = lat
    dropoffLng.value = lng
    dropoffMap = L.map('dropoff-map').setView([lat, lng], 15)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(dropoffMap)
    dropoffMarker = L.marker([lat, lng], { draggable: false }).addTo(dropoffMap)
}

watch(selectedDropOff, async (bank) => {
    if (!dropoffMap || !bank) return
    dropoffLat.value = bank.latitude
    dropoffLng.value = bank.longitude
    dropoffMap.setView([bank.latitude, bank.longitude], 15)
    dropoffMarker?.setLatLng([bank.latitude, bank.longitude])
}, { immediate: false })

watch(isPickup, async (val) => {
    await nextTick()
    if (val) {
        setTimeout(() => {
            if (!pickupMap) initializePickupMap()
            setTimeout(() => { pickupMap?.invalidateSize() }, 100)
        }, 100)
    } else {
        setTimeout(() => {
            if (!dropoffMap) initializeDropoffMap()
            setTimeout(() => { dropoffMap?.invalidateSize() }, 100)
        }, 100)
    }
})

onUnmounted(() => {
    pickupMap?.remove()
    dropoffMap?.remove()
    pickupMap = null
    dropoffMap = null
})

const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family
}

const mainContentStyle = {
    maxWidth: '1200px',
    margin: '0 auto',
    padding: '24px'
}

const cartContainerStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '12px',
    padding: '24px',
    marginTop: '24px'
}

const sectionTitleStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    margin: 0,
    display: 'flex',
    alignItems: 'center',
    gap: '12px'
}

const cartIconStyle = {
    color: theme.colors.darkGrey
}

const cartItemsContainerStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '16px',
    marginTop: '20px'
}

const cartItemStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: '16px',
    backgroundColor: theme.colors.whiteBg,
    borderRadius: '8px'
}

const itemLeftStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '12px'
}

const itemIconStyle = {
    width: '40px',
    height: '40px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center'
}

const itemDetailsStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '8px'
}

const itemNameStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium
}

const weightContainerStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '4px'
}

const weightBadgeStyle = {
    padding: '4px 8px',
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey,
    minWidth: '50px',
    textAlign: 'center'
}

const weightButtonStyle = {
    width: '24px',
    height: '24px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    padding: '0',
    borderRadius: '50%',
    border: 'none',
    cursor: 'pointer',
    color: 'white'
}

const minusButtonStyle = {
    ...weightButtonStyle,
    backgroundColor: theme.colors.red
}

const plusButtonStyle = {
    ...weightButtonStyle,
    backgroundColor: theme.colors.primary
}

const priceStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.semibold,
    color: theme.colors.primary
}

const totalContainerStyle = {
    marginTop: '20px',
    padding: '16px',
    backgroundColor: theme.colors.whiteBg,
    borderRadius: '8px'
}

const totalRowStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    marginBottom: '8px',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium
}

const totalPriceStyle = {
    color: theme.colors.primary,
    fontWeight: theme.fonts.weight.bold
}

const bookingContainerStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '12px',
    padding: '24px',
    marginTop: '24px'
}

const bookingIconStyle = {
    color: theme.colors.darkGrey
}

const pickupTypeStyle = {
    display: 'flex',
    gap: '12px',
    marginTop: '20px'
}

const pickupButtonStyle = {
    flex: 1,
    padding: '12px',
    borderRadius: '8px',
    border: `1px solid ${theme.colors.lightGrey}`,
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    cursor: 'pointer'
}

const formStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '20px',
    marginTop: '24px'
}

const formRowStyle = {
    display: 'flex',
    gap: '16px',
    width: '100%'
}

const formGroupStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '8px'
}

const labelStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    color: theme.colors.darkGrey
}

const inputStyle = {
    padding: '12px',
    borderRadius: '8px',
    border: `1px solid ${theme.colors.lightGrey}`,
    fontSize: theme.fonts.size.base,
    fontFamily: theme.fonts.family,
    width: '100%',
    boxSizing: 'border-box'
}

const autocompleteListStyle = {
    position: 'absolute',
    top: '44px',
    left: 0,
    right: 0,
    background: '#fff',
    border: `1px solid ${theme.colors.lightGrey}`,
    zIndex: 10,
    listStyle: 'none',
    margin: 0,
    padding: 0,
    borderRadius: '0 0 8px 8px',
    maxHeight: '180px',
    overflowY: 'auto'
}

const autocompleteItemStyle = {
    padding: '8px 12px',
    cursor: 'pointer',
    borderBottom: `1px solid ${theme.colors.lightGrey}`,
    background: '#fff',
    fontSize: theme.fonts.size.base,
}

const textareaStyle = {
    padding: '12px',
    borderRadius: '8px',
    border: `1px solid ${theme.colors.lightGrey}`,
    fontSize: theme.fonts.size.base,
    fontFamily: theme.fonts.family,
    minHeight: '100px',
    resize: 'vertical'
}

const selectStyle = {
    width: '100%',
    padding: '8px 12px',
    paddingRight: '36px',
    fontSize: theme.fonts.size.base,
    borderRadius: '6px',
    border: `1px solid ${theme.colors.grey}`,
    fontFamily: theme.fonts.family,
    appearance: 'none',
    backgroundImage: `url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e")`,
    backgroundRepeat: 'no-repeat',
    backgroundPosition: 'right 8px center',
    backgroundSize: '16px'
}

const previewContainerStyle = {
    marginTop: '12px',
    textAlign: 'center'
}

const previewImageStyle = {
    width: '100px',
    height: '100px',
    objectFit: 'cover',
    borderRadius: '6px'
}

const uploadingStyle = {
    marginTop: '8px',
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey
}

const submitButtonStyle = {
    marginLeft: 'auto',
    marginRight: 'auto',
    padding: '12px 24px',
    borderRadius: '24px',
    border: 'none',
    backgroundColor: theme.colors.primary,
    color: 'white',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.semibold,
    cursor: 'pointer',
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
}

const buttonStyle = {
    backgroundColor: theme.colors.primary,
    color: 'white',
    fontWeight: theme.fonts.weight.semibold,
    fontSize: theme.fonts.size.base,
    border: 'none',
    borderRadius: '999px',
    padding: '10px 30px',
    cursor: 'pointer',
}

const buttonHoverStyle = {
    backgroundColor: '#2d862d',
    transform: 'scale(1.05)',
}
</script>

<style scoped>
@import "leaflet/dist/leaflet.css";
::-webkit-scrollbar {
    display: none;
}
</style>
