<template>
    <div :style="overlayStyle">
        <div :style="popupStyle">
            <h2 :style="titleStyle">{{ bank ? 'Edit Bank Sampah' : 'Tambah Bank Sampah' }}</h2>
            <form @submit.prevent="save">
                <div :style="formGroupStyle">
                    <label :style="labelStyle">Nama Bank Sampah</label>
                    <input v-model="form.name" type="text" :style="inputStyle" required />
                </div>
                <div :style="formGroupStyle">
                    <label :style="labelStyle">Alamat</label>
                    <input
                        v-model="form.address"
                        type="text"
                        :style="inputStyle"
                        @change="onAddressChange"
                        placeholder="Cari atau klik map"
                    />
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
                    <button type="button" :style="cancelButtonStyle" @click="$emit('close')">Batal</button>
                    <button type="submit" :style="saveButtonStyle">Simpan</button>
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

const props = defineProps<{ bank?: Bank | null }>()
const emit = defineEmits(['close', 'saved'])
const form = ref<Bank>({
    id: 0,
    name: '',
    address: '',
    phone: '',
    latitude: -6.2,
    longitude: 106.816666,
})
const { saveBank } = useBankApi()

let map: L.Map | null = null
let marker: L.Marker | null = null

const mapStyle = {
    width: '100%',
    height: '250px',
    borderRadius: '8px',
    marginBottom: '8px'
}

const geocode = async (address: string) => {
    const url = `https://api.opencagedata.com/geocode/v1/json?q=${encodeURIComponent(address)}&key=e5f8659f848545f486b84de646bd104d&language=id&countrycode=id&limit=1`
    const res = await fetch(url)
    const data = await res.json()
    if (data.results && data.results.length > 0) {
        return {
            lat: data.results[0].geometry.lat,
            lng: data.results[0].geometry.lng,
            address: data.results[0].formatted
        }
    }
    return null
}

const reverseGeocode = async (lat: number, lng: number) => {
    const url = `https://api.opencagedata.com/geocode/v1/json?q=${lat}+${lng}&key=e5f8659f848545f486b84de646bd104d&language=id&countrycode=id&limit=1`
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

const onAddressChange = async () => {
    if (form.value.address && form.value.address.length > 3) {
        const result = await geocode(form.value.address)
        if (result) {
            setLatLng(result.lat, result.lng)
            if (map) map.setView([result.lat, result.lng], 17)
        }
    }
}

const initializeMap = async () => {
    await nextTick()
    if (map) return

    map = L.map('leaflet-map').setView([form.value.latitude ?? -6.2, form.value.longitude ?? 106.816666], 13)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map)

    marker = L.marker([form.value.latitude ?? -6.2, form.value.longitude ?? 106.816666], { draggable: true }).addTo(map)

    marker.on('dragend', async (e: any) => {
        const pos = marker!.getLatLng()
        setLatLng(pos.lat, pos.lng)
        const address = await reverseGeocode(pos.lat, pos.lng)
        if (address) form.value.address = address
    })

    map.on('click', async (e: any) => {
        const { lat, lng } = e.latlng
        setLatLng(lat, lng)
        const address = await reverseGeocode(lat, lng)
        if (address) form.value.address = address
    })
}

watch(
    () => props.bank,
    (newVal) => {
        form.value = newVal
            ? {
                ...newVal,
                latitude: newVal.latitude ?? -6.2,
                longitude: newVal.longitude ?? 106.816666,
            }
            : { id: 0, name: '', address: '', phone: '', latitude: -6.2, longitude: 106.816666 }
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

const buttonGroupStyle = {
    display: 'flex',
    justifyContent: 'flex-end',
    gap: '12px',
}

const cancelButtonStyle = {
    padding: '8px 16px',
    borderRadius: '6px',
    backgroundColor: theme.colors.lightGrey,
    color: theme.colors.darkGrey,
    fontSize: theme.fonts.size.base,
    border: 'none',
    cursor: 'pointer',
}

const saveButtonStyle = {
    padding: '8px 16px',
    borderRadius: '6px',
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    fontSize: theme.fonts.size.base,
    border: 'none',
    cursor: 'pointer',
}
</script>

<style scoped>
@import "leaflet/dist/leaflet.css";
::-webkit-scrollbar {
    display: none;
}
</style>
