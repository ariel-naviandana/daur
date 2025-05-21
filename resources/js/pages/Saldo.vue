<template>
    <div :style="layoutStyle">
        <div :style="upperSection">
            <!-- Tombol Kembali -->
            <a href="/profile">
            <button
                :style="[backButtonStyle, isHover ? buttonHoverStyle : {}]"
                @mouseover="isHover = true"
                @mouseleave="isHover = false"
                @click=""
            >
                <img src="/public/images/back-btn.svg" alt="">
                <div style="display: flex; justify-content: center; align-items: center;">
                    <span>Kembali ke profile</span>
                </div>
            </button>
            </a>

            <!-- Nama User -->
            <h1 :style="userNameStyle">Rudy Tabootie</h1>
        </div>

        <!-- Card Saldo -->
        <SaldoCard @click="showModal = true" @tarikSaldo="showModal = true" />
        <WithdrawalCard
            :visible="showModal"
            @close="showModal = false"
            @submit="handleWithdraw"
        />

        <!-- Section Mutasi Saldo -->
        <div :style="mutasiWrapperStyle">
            <div :style="mutasiHeaderStyle">
                <h2 :style="mutasiTitleStyle">Mutasi saldo</h2>
                <div :style="mutasiButtonGroupStyle">
                    <button :style="[filterDownloadButtonStyle, isHoverDownload ? buttonHoverStyle : {}]"
                        @mouseover="isHoverDownload = true" @mouseleave="isHoverDownload = false"
                        @click="downloadMutasi">
                        Download
                    </button>
                    <button :style="[filterDownloadButtonStyle, isHoverFilter ? buttonHoverStyle : {}]"
                        @mouseover="isHoverFilter = true" @mouseleave="isHoverFilter = false" @click="toggleFilter">
                        Filter
                    </button>
                </div>
            </div>

            <!-- Dummy Card Mutasi -->
            <div :style="mutasiCardStyle">
                <div :style="mutasiIconWrapperStyle">
                    <img src="/public/images/money-out.svg">
                </div>
                <div :style="mutasiInfoStyle">
                    <p :style="mutasiTitleTextStyle">Saldo masuk</p>
                    <p :style="mutasiDateStyle">10 Februari 2025, 08:45</p>
                </div>
                <p :style="mutasiAmountPlusStyle">+Rp. 50,000</p>
            </div>
        </div>

    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { theme } from '@/helpers/theme'
import SaldoCard from "@/components/SaldoCard.vue"
import WithdrawalCard from "@/components/WithdrawalCard.vue"

const showModal = ref(false)
const isHover = ref(false)

const handleWithdraw = (data: { destination: string; amount: string }) => {
    console.log('Penarikan ke:', data.destination)
    console.log('Jumlah:', data.amount)
    showModal.value = false
}

const isHoverDownload = ref(false)
const isHoverFilter = ref(false)

const downloadMutasi = () => {
    alert('Download file mutasi saldo (dummy)')
}

const toggleFilter = () => {
    alert('Filter dropdown (dummy)')
}

const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family,
    padding: '2rem',
}

const backButtonStyle = {
    width: '230px',
    height: '50px',
    backgroundColor: 'transparent',
    color: theme.colors.primary,
    fontWeight: theme.fonts.weight.bold,
    border: `1px solid ${theme.colors.primary}`,
    display: 'flex',
    borderRadius: '999px',
    padding: '8px 20px',
    cursor: 'pointer',
    marginBottom: '1rem',
    gap: '10px',
    justifyContent: 'center',
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
}

const userNameStyle = {
    fontSize: theme.fonts.size.heading,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.darkGrey,
    marginBottom: '1.5rem',
}

const upperSection = {
    maxWidth: '900px',
    margin: '0 auto',
}

const mutasiWrapperStyle = {
    maxWidth: '900px',
    margin: '0 auto',
}

const mutasiHeaderStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: '1rem',
}

const mutasiTitleStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.darkGrey,
}

const mutasiButtonGroupStyle = {
    display: 'flex',
    gap: '12px',
}

const filterDownloadButtonStyle = {
    backgroundColor: theme.colors.primary,
    color: 'white',
    border: 'none',
    borderRadius: '999px',
    padding: '8px',
    width: '130px',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    cursor: 'pointer',
    transition: '0.2s ease',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
}

const buttonHoverStyle = {
    transform: 'scale(1.05)',
}

const mutasiCardStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '12px',
    padding: '1rem',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
    boxShadow: '0 2px 8px rgba(0,0,0,0.05)',
    marginBottom: '1rem',
}

const mutasiIconWrapperStyle = {
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

const mutasiInfoStyle = {
    flex: 1,
}

const mutasiTitleTextStyle = {
    fontWeight: theme.fonts.weight.bold,
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey,
    marginBottom: '4px',
}

const mutasiDateStyle = {
    fontSize: '0.85rem',
    color: '#888',
}

const mutasiAmountPlusStyle = {
    color: theme.colors.primary,
    fontWeight: theme.fonts.weight.bold,
}
</script>

<style scoped></style>
