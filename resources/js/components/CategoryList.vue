<template>
    <div :style="categoryListContainerStyle">
        <button :style="navigationButtonStyle" @click="scrollCategoryList('left')">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <div ref="categoryListRef" :style="categoryListStyle">
            <button v-for="category in categories" :key="category.id" :style="categoryItemStyle">
                <img :src="category.icon" :alt="category.name" :style="categoryIconStyle" />
                <span :style="categoryLabelStyle">{{ category.name }}</span>
            </button>
        </div>

        <button :style="navigationButtonStyle" @click="scrollCategoryList('right')">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { theme } from '@/config/theme'

const getIconPath = (type: string): string => {
    if (type === "kertas") {
        return "/images/ic_jenis_kertas.svg";
    } else if (type === "plastik") {
        return "/images/ic_jenis_botol_plastik.svg";
    } else if (type === "kaca") {
        return "/images/ic_jenis_botol_kaca.svg";
    } else if (type === "besi") {
        return "/images/ic_jenis_besi.svg";
    } else if (type === "aluminium") {
        return "/images/ic_jenis_aluminium.svg";
    } else if (type === "kardus") {
        return "/images/ic_jenis_kardus.svg";
    }
    return "/images/ic_jenis_kertas.svg";
}

const categories = [
    { id: 1, name: 'Kertas', icon: getIconPath('kertas') },
    { id: 2, name: 'Plastik', icon: getIconPath('plastik') },
    { id: 3, name: 'Kaca', icon: getIconPath('kaca') },
    { id: 4, name: 'Besi', icon: getIconPath('besi') },
    { id: 5, name: 'Aluminium', icon: getIconPath('aluminium') },
    { id: 6, name: 'Kardus', icon: getIconPath('kardus') },
    { id: 7, name: 'Kain', icon: getIconPath('kertas') },
    { id: 8, name: 'Elektronik', icon: getIconPath('plastik') },
    { id: 9, name: 'Baterai', icon: getIconPath('kaca') },
    { id: 10, name: 'Logam', icon: getIconPath('besi') },
    { id: 11, name: 'Kayu', icon: getIconPath('aluminium') },
    { id: 12, name: 'Karet', icon: getIconPath('kardus') },
    { id: 13, name: 'Pakaian', icon: getIconPath('kertas') },
    { id: 14, name: 'Tas', icon: getIconPath('plastik') },
]

const categoryListRef = ref<HTMLElement | null>(null)

const scrollCategoryList = (direction: 'left' | 'right') => {
    if (categoryListRef.value) {
        const scrollAmount = categoryListRef.value.clientWidth;
        categoryListRef.value.scrollBy({
            left: direction === 'left' ? -scrollAmount : scrollAmount,
            behavior: 'smooth'
        });
    }
}

const categoryListContainerStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    gap: '16px',
    padding: '16px 0'
}

const navigationButtonStyle = {
    width: '40px',
    height: '40px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    border: 'none',
    borderRadius: '50%',
    backgroundColor: 'white',
    color: theme.colors.darkGrey,
    cursor: 'pointer',
    boxShadow: '0 1px 3px rgba(0, 0, 0, 0.1)',
    flexShrink: 0
}

const categoryListStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    gap: '16px',
    overflow: 'hidden',
    overflowX: 'auto',
    padding: '8px 4px',
    margin: '0 -4px'
}

const categoryItemStyle = {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    margin: '0 10px',
    gap: '8px',
    padding: '16px',
    border: 'none',
    background: 'white',
    cursor: 'pointer',
    borderRadius: '16px',
    boxShadow: '0 2px 4px rgba(0, 0, 0, 0.05)',
    minWidth: '100px'
}

const categoryIconStyle = {
    width: '40px',
    height: '40px',
    borderRadius: '12px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center'
}

const categoryLabelStyle = {
    fontSize: '0.875rem',
    color: theme.colors.darkGrey,
    fontWeight: 500
}
</script>

<style scoped>
::-webkit-scrollbar {
    display: none;
}
</style>
