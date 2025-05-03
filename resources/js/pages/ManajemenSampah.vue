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
                <h1 :style="headingStyle">Manajemen Sampah</h1>
            </div>
            <div :style="tabsContainerStyle">
                <button
                    :style="[tabStyle, activeTab === 'category' ? activeTabStyle : {}]"
                    @click="activeTab = 'category'"
                >
                    Kategori Sampah
                </button>
                <button
                    :style="[tabStyle, activeTab === 'wastetype' ? activeTabStyle : {}]"
                    @click="activeTab = 'wastetype'"
                >
                    Jenis Sampah
                </button>
            </div>
            <div v-if="activeTab === 'category'">
                <CategoryManagement />
            </div>
            <div v-else-if="activeTab === 'wastetype'">
                <WasteTypeManagement />
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import Navbar from '../components/Navbar.vue'
import CategoryManagement from '../components/ManajemenCategory.vue'
import WasteTypeManagement from '../components/ManajemenWasteType.vue'
import { theme } from '@/config/theme'

const activeTab = ref<'category' | 'wastetype'>('category')

const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family,
}

const contentStyle = {
    maxWidth: '1200px',
    margin: '0 auto',
    color: theme.colors.darkGrey,
}

const headingContainerStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '12px',
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

const tabsContainerStyle = {
    display: 'flex',
    gap: '16px',
    marginBottom: '16px',
}

const tabStyle = {
    padding: '8px 16px',
    borderRadius: '6px',
    border: `1px solid ${theme.colors.lightGrey}`,
    fontSize: theme.fonts.size.base,
    cursor: 'pointer',
    backgroundColor: theme.colors.whiteElement,
    color: theme.colors.darkGrey,
}

const activeTabStyle = {
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
}
</script>
