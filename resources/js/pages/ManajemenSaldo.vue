<template>
    <div :style="layoutStyle">
        <Navbar />

        <h2 :style="titleStyle">Manajemen Transaksi</h2>

        <div :style="contentWrapperStyle">
            <div :style="tabWrapperStyle">
                <button
                    :style="getTabButtonStyle('masuk')"
                    @click="activeTab = 'masuk'"
                    @mouseenter="hoveredTab = 'masuk'"
                    @mouseleave="hoveredTab = ''"
                >
                    Saldo Masuk
                </button>
                <button
                    :style="getTabButtonStyle('keluar')"
                    @click="activeTab = 'keluar'"
                    @mouseenter="hoveredTab = 'keluar'"
                    @mouseleave="hoveredTab = ''"
                >
                    Saldo Keluar
                </button>
                <button
                    :style="getTabButtonStyle('penarikan')"
                    @click="activeTab = 'penarikan'"
                    @mouseenter="hoveredTab = 'penarikan'"
                    @mouseleave="hoveredTab = ''"
                >
                    Permintaan Penarikan
                </button>
            </div>

            <div :style="transactionRow">
                <div :style="transactionIcon">
                    <img src="/images/ic-transaction.svg">
                </div>
                <div :style="transactionInfo">
                    <p :style="transactionNameInfo">Rudi Tabootie</p>
                    <p :style="transactionDate">10 Februari 2025, 08:45</p>
                </div>
                <p :style="mutasiAmountPlusStyle">Rp. 50,000</p>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import Navbar from '@/components/Navbar.vue'
import { theme } from '@/helpers/theme'

const activeTab = ref<'masuk' | 'keluar' | 'penarikan'>('masuk')
const hoveredTab = ref('')

const getTabButtonStyle = (tab: string) => {
    const isActive = activeTab.value === tab
    const isHovered = hoveredTab.value === tab
    if (isActive) {
        console.log('Aktif:', tab, tabButtonActiveStyle)
    }

    if (isActive) {
        return {
            ...tabButtonBaseStyle,
            ...tabButtonActiveStyle,
        }
    } else {
        return {
            ...tabButtonBaseStyle,
            ...tabButtonInactiveStyle,
            backgroundColor: isHovered ? theme.colors.lightGrey : theme.colors.white,
        }
    }
}

const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family,
    overflowX : 'hidden'
}

const titleStyle = {
    fontWeight: theme.fonts.weight.bold,
    fontSize: theme.fonts.size.heading,
    color : theme.colors.darkGrey,
    padding : '30px'
}

const contentWrapperStyle = {
    display: 'flex',
    // maxWidth: '900px',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    padding: '20px',
}

const tabWrapperStyle = {
    display: 'flex',
    width: '900px',
    height: '50px',
    border: `1px solid ${theme.colors.primary}`,
    borderRadius: '999px',
    overflow: 'hidden',
}

const tabButtonBaseStyle = {
    flex: 1,
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    border: 'none',
    outline: 'none',
    cursor: 'pointer',
    transition: '0.3s',
}

const tabButtonActiveStyle = {
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
}

const tabButtonInactiveStyle = {
    backgroundColor: theme.colors.white,
    color: theme.colors.text,
}

const transactionRow = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '20px',
    padding: '1rem',
    display: 'flex',
    alignItems: 'center',
    width: '900px',
    // maxWidth: '900px',
    justifyContent: 'space-between',
    boxShadow: '0 2px 8px rgba(0,0,0,0.05)',
    marginTop: '3rem',
}

const transactionIcon = {
    backgroundColor: 'transparent',
    width: '40px',
    height: '40px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    color: theme.colors.primary,
    fontSize: '20px',
    marginRight: '1rem',
}

const transactionInfo = {
    flex: 1,
}

const transactionNameInfo = {
    fontFamily: theme.fonts.family,
    fontWeight: theme.fonts.weight.bold,
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey,
    marginBottom: '4px',
}

const transactionDate = {
    fontFamily: theme.fonts.family,
    fontSize: '0.85rem',
    color: theme.colors.grey,
}

const mutasiAmountPlusStyle = {
    color: theme.colors.primary,
    fontWeight: theme.fonts.weight.bold,
}

</script>
