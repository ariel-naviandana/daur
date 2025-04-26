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
            <ul class="history-list">
                <RecycleCardAdmin
                    v-for="(item, index) in filteredHistory"
                    :key="index"
                    :item="item"
                />
            </ul>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue'
import Navbar from '../components/Navbar.vue'
import RecycleCardAdmin from '../components/RecycleCardAdmin.vue'
import { theme } from '@/config/theme'

interface HistoryItem {
    date: string;
    status: string;
    amount: number;
    username: string;
}

const selectedFilter = ref<string>('all')
const selectedSort = ref<string>('latest')

const history = ref<HistoryItem[]>([
    { date: "Selasa, 18 Maret 2025", status: "Waiting", amount: 56000, username: "Ariel Naviandana" },
    { date: "Senin, 17 Maret 2025", status: "Success", amount: 105000, username: "Rudy Tabootie" },
    { date: "Minggu, 16 Maret 2025", status: "Success", amount: 60000, username: "Jamal" },
    { date: "Sabtu, 15 Maret 2025", status: "Cancel", amount: 62000, username: "Rudy Tabootie" },
    { date: "Kamis, 14 Maret 2025", status: "Success", amount: 60000, username: "Ariel Naviandana" },
])

const filteredHistory = computed(() => {
    let filtered = history.value
    if (selectedFilter.value !== 'all')
        filtered = filtered.filter(item => item.status.toLowerCase() === selectedFilter.value)

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

const selectStyle = {
    padding: '8px 12px',
    fontSize: theme.fonts.size.base,
    borderRadius: '6px',
    border: `1px solid #ccc`,
    fontFamily: theme.fonts.family,
}
</script>

<style scoped>
.filters {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
}
.history-list {
    list-style: none;
    padding: 0;
}
</style>
