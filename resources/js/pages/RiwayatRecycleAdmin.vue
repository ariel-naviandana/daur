<template>
    <div :style="layoutStyle">
        <Navbar />
        <div :style="contentStyle" class="p-6">
            <div :style="headingContainerStyle">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="28"
                    height="28"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    :style="iconStyle"
                >
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    <path d="M9 12h6"></path>
                    <path d="M9 16h6"></path>
                    <path d="M12 8L16 12"></path>
                </svg>
                <h1 :style="headingStyle">Manajemen Recycle</h1>
            </div>

            <div class="filters">
                <div :style="leftFilterStyle">
                    <select v-model="selectedFilter" :style="selectStyle">
                        <option value="all">Semua</option>
                        <option value="success">Success</option>
                        <option value="waiting">Waiting</option>
                        <option value="cancel">Cancel</option>
                    </select>
                    <select v-model="selectedSort" :style="selectStyle">
                        <option value="latest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="highest">Tertinggi</option>
                        <option value="lowest">Terendah</option>
                        <option value="alphabet-asc">Username (A-Z)</option>
                        <option value="alphabet-desc">Username (Z-A)</option>
                    </select>
                </div>

                <div :style="searchWrapperStyle">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        :style="searchIconStyle"
                    >
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input
                        type="text"
                        v-model="searchQuery"
                        placeholder="Cari nama pengguna"
                        :style="searchInputStyle"
                    />
                </div>
            </div>

            <div v-if="filteredAndSearchedHistory.length === 0" :style="noResultsStyle">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="48"
                    height="48"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    :style="noResultsIconStyle"
                >
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <p :style="noResultsTextStyle">Tidak ada hasil yang ditemukan</p>
                <p :style="noResultsDescStyle">
                    Coba sesuaikan filter atau kata kunci pencarian Anda
                </p>
            </div>

            <ul v-else class="history-list">
                <RecycleCardAdmin
                    v-for="(item, index) in filteredAndSearchedHistory"
                    :key="index"
                    :item="item"
                    @showDetail="openPopup"
                />
            </ul>
        </div>

        <PopupDetailRecycleAdmin
            v-if="selectedItem"
            :isOpen="showPopup"
            :item="selectedItem"
            @close="closePopup"
            @accept="closePopup"
            @reject="closePopup"
        />
    </div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue'
import Navbar from '../components/Navbar.vue'
import RecycleCardAdmin from '../components/RecycleCardAdmin.vue'
import PopupDetailRecycleAdmin from '../components/PopupDetailRecycleAdmin.vue'
import { theme } from '@/config/theme'

interface HistoryItem {
    date: string
    status: string
    amount: number
    username: string
    items: {
        type: string
        name: string
        weight: number
        price: number
    }[]
    address: string
    pickupTime: string
    note: string
}

const selectedFilter = ref<string>('all')
const selectedSort = ref<string>('latest')
const searchQuery = ref('')
const showPopup = ref(false)
const selectedItem = ref<HistoryItem | null>(null)

const history = ref<HistoryItem[]>([
    {
        date: "Selasa, 18 Maret 2025",
        status: "Waiting",
        amount: 45000,
        username: "Ariel Naviandana",
        items: [
            { type: 'Koran', name: 'Koran', weight: 5, price: 15000 },
            { type: 'Gelas Kaca', name: 'Gelas Kaca', weight: 7, price: 15000 },
            { type: 'Botol Plastik', name: 'Botol Plastik', weight: 9, price: 15000 }
        ],
        address: 'Jl. Veteran Malang, Ketawanggede, Kec. Lowokwaru, Kota Malang, Jawa Timur 65145',
        pickupTime: '12.00 WIB',
        note: 'Di fakultas ilmu komputer, saya tunggu di dekat pintu masuk parkiran gedung f'
    },
    {
        date: "Senin, 17 Maret 2025",
        status: "Success",
        amount: 105000,
        username: "Rudy Tabootie",
        items: [
            { type: 'Koran', name: 'Koran', weight: 10, price: 30000 },
            { type: 'Gelas Kaca', name: 'Gelas Kaca', weight: 15, price: 45000 },
            { type: 'Botol Plastik', name: 'Botol Plastik', weight: 10, price: 30000 }
        ],
        address: 'Jl. Veteran Malang, Ketawanggede, Kec. Lowokwaru, Kota Malang, Jawa Timur 65145',
        pickupTime: '14.00 WIB',
        note: 'Di fakultas ilmu komputer'
    },
    {
        date: "Minggu, 16 Maret 2025",
        status: "Success",
        amount: 60000,
        username: "Jamal",
        items: [
            { type: 'Koran', name: 'Koran', weight: 8, price: 24000 },
            { type: 'Gelas Kaca', name: 'Gelas Kaca', weight: 12, price: 36000 }
        ],
        address: 'Jl. Veteran Malang, Ketawanggede, Kec. Lowokwaru, Kota Malang, Jawa Timur 65145',
        pickupTime: '10.00 WIB',
        note: 'Di gedung F'
    },
    {
        date: "Sabtu, 15 Maret 2025",
        status: "Cancel",
        amount: 62000,
        username: "Rudy Tabootie",
        items: [
            { type: 'Botol Plastik', name: 'Botol Plastik', weight: 12, price: 36000 },
            { type: 'Koran', name: 'Koran', weight: 8, price: 26000 }
        ],
        address: 'Jl. Veteran Malang, Ketawanggede, Kec. Lowokwaru, Kota Malang, Jawa Timur 65145',
        pickupTime: '15.00 WIB',
        note: 'Di depan gedung'
    },
    {
        date: "Kamis, 14 Maret 2025",
        status: "Success",
        amount: 60000,
        username: "Ariel Naviandana",
        items: [
            { type: 'Gelas Kaca', name: 'Gelas Kaca', weight: 20, price: 60000 }
        ],
        address: 'Jl. Veteran Malang, Ketawanggede, Kec. Lowokwaru, Kota Malang, Jawa Timur 65145',
        pickupTime: '09.00 WIB',
        note: 'Di fakultas ilmu komputer'
    }
])

const filteredAndSearchedHistory = computed(() => {
    let filtered = history.value

    if (searchQuery.value)
        filtered = filtered.filter(item =>
            item.username.toLowerCase().includes(searchQuery.value.toLowerCase())
        )

    if (selectedFilter.value !== 'all')
        filtered = filtered.filter(item =>
            item.status.toLowerCase() === selectedFilter.value
        )

    if (selectedSort.value === 'latest')
        filtered = filtered.sort((a, b) => new Date(b.date).getTime() - new Date(a.date).getTime())
    else if (selectedSort.value === 'highest')
        filtered = filtered.sort((a, b) => b.amount - a.amount)
    else if (selectedSort.value === 'lowest')
        filtered = filtered.sort((a, b) => a.amount - b.amount)
    else if (selectedSort.value === 'alphabet-asc')
        filtered = filtered.sort((a, b) => a.username.localeCompare(b.username))
    else if (selectedSort.value === 'alphabet-desc')
        filtered = filtered.sort((a, b) => b.username.localeCompare(a.username))
    else
        filtered = filtered.sort((a, b) => new Date(a.date).getTime() - new Date(b.date).getTime())

    return filtered
})

const openPopup = (item: HistoryItem) => {
    selectedItem.value = item
    showPopup.value = true
}

const closePopup = () => {
    showPopup.value = false
    selectedItem.value = null
}

const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family,
}

const contentStyle = {
    color: theme.colors.darkGrey,
}

const headingContainerStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '12px',
    size: theme.fonts.size.subheading,
    marginBottom: '20px',
}

const iconStyle = {
    color: theme.colors.darkGrey,
}

const headingStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    margin: 0,
}

const leftFilterStyle = {
    display: 'flex',
    gap: '12px',
}

const selectStyle = {
    padding: '8px 12px',
    paddingRight: '36px',
    fontSize: theme.fonts.size.base,
    borderRadius: '6px',
    border: `1px solid #ccc`,
    fontFamily: theme.fonts.family,
    appearance: 'none',
    backgroundImage: `url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e")`,
    backgroundRepeat: 'no-repeat',
    backgroundPosition: 'right 8px center',
    backgroundSize: '16px',
}

const searchWrapperStyle = {
    display: 'flex',
    alignItems: 'center',
    backgroundColor: theme.colors.whiteElement,
    border: '1px solid #E5E7EB',
    borderRadius: '100px',
    padding: '8px 16px',
    width: '300px',
}

const searchIconStyle = {
    color: '#9CA3AF',
    marginRight: '12px',
}

const searchInputStyle = {
    border: 'none',
    outline: 'none',
    width: '100%',
    fontSize: theme.fonts.size.base,
    backgroundColor: 'transparent',
    color: theme.colors.darkGrey,
    fontFamily: theme.fonts.family,
}

const noResultsStyle = {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    padding: '48px 0',
    textAlign: 'center',
}

const noResultsIconStyle = {
    color: theme.colors.grey,
    marginBottom: '16px',
}

const noResultsTextStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.darkGrey,
    margin: '0 0 8px 0',
}

const noResultsDescStyle = {
    fontSize: theme.fonts.size.sm,
    color: theme.colors.grey,
    margin: 0,
}
</script>

<style scoped>
.filters {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.history-list {
    list-style: none;
    padding: 0;
}

input::placeholder {
    color: #9CA3AF;
}

input:focus {
    outline: none;
}

[style*="flex-direction: column"] {
    display: flex;
}
</style>
