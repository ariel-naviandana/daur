<template>
    <div :style="overlayStyle">
        <div :style="popupStyle">
            <h2 :style="titleStyle">{{ bank ? 'Edit Bank Sampah' : 'Tambah Bank Sampah' }}</h2>
            <form @submit.prevent="save" autocomplete="off">
                <div :style="formGroupStyle">
                    <label :style="labelStyle">Nama Bank Sampah</label>
                    <input v-model="form.name" type="text" :style="inputStyle" required />
                </div>
                <div :style="formGroupStyle" style="position:relative;">
                    <label :style="labelStyle">Alamat</label>
                    <div :style="formGroupStyle">
                        <input
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
                        />
                        <ul v-if="showAutocomplete && addressSuggestions.length" :style="autocompleteListStyle">
                            <li
                                v-for="(suggestion, idx) in addressSuggestions"
                                :key="idx"
                                :style="autocompleteItemStyle"
                                @mousedown.prevent="selectSuggestion(suggestion)"
                            >
                                {{ suggestion.formatted }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div :style="formGroupStyle">
                    <label :style="labelStyle">Pilih Lokasi di Map</label>
                    <div id="leaflet-map" :style="mapStyle"></div>
                </div>
                <div :style="formGroupStyle">
                    <label :style="labelStyle">Telepon</label>
                    <input v-model="form.phone" type="text" :style="inputStyle" />
                </div>
                <div :style="buttonGroupStyle">
                    <button
                        type="button"
                        :style="[cancelButtonStyle, isHoverCancel ? buttonHoverStyleCancel : {}]"
                        @mouseover="isHoverCancel = true"
                        @mouseleave="isHoverCancel = false"
                        @click="$emit('close')"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        :style="[saveButtonStyle, isHoverSave ? buttonHoverStyleSave : {}]"
                        @mouseover="isHoverSave = true"
                        @mouseleave="isHoverSave = false"
                    >
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, watch, onMounted, nextTick } from 'vue'
import { theme } from '@/helpers/theme'
import { useBankApi } from '@/composables/useBankApi'
import { Bank } from '@/interfaces/Bank'
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

const OPENCAGE_API_KEY = 'e5f8659f848545f486b84de646bd104d'

const isHoverCancel = ref(false)
const isHoverSave = ref(false)

const props = defineProps<{ bank?: Bank | null }>()
const emit = defineEmits(['close', 'saved'])
const defaultLat = ref(-6.2)
const defaultLng = ref(106.816666)
const form = ref<Bank>({
    id: 0,
    name: '',
    address: '',
    phone: '',
    latitude: defaultLat.value,
    longitude: defaultLng.value,
})
const { saveBank } = useBankApi()

let map: L.Map | null = null
let marker: L.Marker | null = null

const addressInput = ref('')
const addressSuggestions = ref<{ formatted: string, lat: number, lng: number }[]>([])
const showAutocomplete = ref(false)
const addressInputRef = ref<HTMLInputElement | null>(null)
let autocompleteTimeout: any = null

const mapStyle = {
    width: '100%',
    height: '250px',
    borderRadius: '8px',
    marginBottom: '8px'
}

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

const setLatLng = (lat: number, lng: number) => {
    form.value.latitude = +lat.toFixed(7)
    form.value.longitude = +lng.toFixed(7)
    marker?.setLatLng([lat, lng])
    map?.setView([lat, lng])
}

const selectSuggestion = (suggestion: { formatted: string, lat: number, lng: number }) => {
    addressInput.value = suggestion.formatted
    form.value.address = suggestion.formatted
    setLatLng(suggestion.lat, suggestion.lng)
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
                        lat: defaultLat.value,
                        lng: defaultLng.value
                    })
                },
                { enableHighAccuracy: true, timeout: 5000 }
            )
        } else {
            resolve({
                lat: defaultLat.value,
                lng: defaultLng.value
            })
        }
    })
}

const initializeMap = async () => {
    await nextTick()
    if (!props.bank) {
        const pos = await getCurrentPosition()
        defaultLat.value = pos.lat
        defaultLng.value = pos.lng
        form.value.latitude = pos.lat
        form.value.longitude = pos.lng
    }
    if (map) return

    map = L.map('leaflet-map').setView([form.value.latitude ?? defaultLat.value, form.value.longitude ?? defaultLng.value], 13)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map)

    marker = L.marker([form.value.latitude ?? defaultLat.value, form.value.longitude ?? defaultLng.value], { draggable: true }).addTo(map)

    marker.on('dragend', async () => {
        const pos = marker!.getLatLng()
        setLatLng(pos.lat, pos.lng)
        const address = await reverseGeocode(pos.lat, pos.lng)
        if (address) {
            form.value.address = address
            addressInput.value = address
        }
    })

    map.on('click', async (e: any) => {
        const { lat, lng } = e.latlng
        setLatLng(lat, lng)
        const address = await reverseGeocode(lat, lng)
        if (address) {
            form.value.address = address
            addressInput.value = address
        }
    })
}

watch(
    () => props.bank,
    (newVal) => {
        form.value = newVal
            ? {
                ...newVal,
                latitude: newVal.latitude ?? defaultLat.value,
                longitude: newVal.longitude ?? defaultLng.value,
            }
            : { id: 0, name: '', address: '', phone: '', latitude: defaultLat.value, longitude: defaultLng.value }
        addressInput.value = form.value.address ?? ''
        nextTick(() => {
            if (map && marker && form.value.latitude && form.value.longitude) {
                marker.setLatLng([form.value.latitude, form.value.longitude])
                map.setView([form.value.latitude, form.value.longitude])
            }
        })
    },
    { immediate: true }
)

onMounted(() => {
    initializeMap()
})

const save = async () => {
    form.value.address = addressInput.value
    await saveBank(form.value)
    emit('saved')
    emit('close')
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

const popupStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '16px',
    padding: '24px',
    width: '90%',
    maxWidth: '420px',
    maxHeight: '90vh',
    overflowY: 'auto',
    boxShadow: '0 4px 6px rgba(0, 0, 0, 0.1)',
}

const titleStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    marginBottom: '16px',
    color: theme.colors.darkGrey,
}

const formGroupStyle = {
    marginBottom: '16px',
    display: 'flex',
    flexDirection: 'column',
}

const labelStyle = {
    fontSize: theme.fonts.size.base,
    marginBottom: '8px',
    color: theme.colors.darkGrey,
    fontWeight: theme.fonts.weight.medium,
}

const inputStyle = {
    padding: '10px 12px',
    fontSize: theme.fonts.size.base,
    borderRadius: '6px',
    border: `1px solid ${theme.colors.lightGrey}`,
    outline: 'none',
    fontFamily: theme.fonts.family,
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
const buttonGroupStyle = {
    display: 'flex',
    justifyContent: 'flex-end',
    gap: '12px',
}

const cancelButtonStyle = {
    padding: '8px 16px',
    borderRadius: '8px',
    backgroundColor: theme.colors.lightGrey,
    color: theme.colors.darkGrey,
    fontSize: theme.fonts.size.base,
    border: 'none',
    cursor: 'pointer',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
    transition: '0.2s ease-in-out',
}

const saveButtonStyle = {
    padding: '8px 16px',
    borderRadius: '8px',
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    fontSize: theme.fonts.size.base,
    border: 'none',
    cursor: 'pointer',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
    transition: '0.2s ease-in-out',
}

const buttonHoverStyleCancel = {
    backgroundColor: theme.colors.grey,
    transform: 'scale(1.05)',
}

const buttonHoverStyleSave = {
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
