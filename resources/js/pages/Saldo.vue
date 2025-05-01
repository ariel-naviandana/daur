<template>
    <div :style="layoutStyle">
        <div :style="upperSection">
            <!-- Tombol Kembali -->
            <div :style="backButtonStyle">
                <img src="/public/images/back-btn.svg" alt="">
                <button @click="">
                    Kembali ke profile
                </button>
            </div>

            <!-- Nama User -->
            <h1 :style="userNameStyle">Rudy Tabootie</h1>
        </div>
        <!-- Card Saldo -->
        <!-- Card Saldo -->
        <div :style="saldoCardStyle">
            <div>
                <p :style="saldoLabelStyle">Saldo DAUR</p>
                <p :style="saldoAmountStyle">Rp. 25,000</p>
            </div>
            <button :style="[penarikanButtonStyle, isHover ? penarikanHoverStyle : {}]" @mouseover="isHover = true"
                @mouseleave="isHover = false" @click="tarikSaldo">
                <img src="/public/images/withdraw-icon.svg" alt="" style="margin-right: 10px" />
                Penarikan dana
            </button>
        </div>


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
import { theme } from '@/config/theme'

const tarikSaldo = () => {
    alert('Penarikan dana (dummy logic)')
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
    width: '300px',
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
    gap: '10px'
}

const penarikanButtonStyle = {
    width: '300px',
    height: '50px',
    backgroundColor: theme.colors.primary,
    color: 'white',
    fontWeight: theme.fonts.weight.semibold,
    fontSize: theme.fonts.size.base,
    display: 'flex',
    borderRadius: '999px',
    padding: '8px 20px',
    border: 'none',
    cursor: 'pointer',
    marginBottom:'10px',
    gap: '10px'
}

const userNameStyle = {
    fontSize: theme.fonts.size.heading,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.darkGrey,
    marginBottom: '1.5rem',
}

const saldoCardStyle = {
  backgroundColor: theme.colors.whiteElement,
  borderRadius: '24px',
  boxShadow: '0 4px 20px rgba(0, 0, 0, 0.08)',
  padding: '2rem',
  display: 'flex',
  flexDirection: 'column', // arahkan isi ke bawah
  justifyContent: 'space-between',
  width: '900px',
  height: '240px',
  margin: '0 auto',
  marginBottom: '2rem',
}

const saldoLabelStyle = {
    fontWeight: theme.fonts.weight.semibold,
    fontSize: theme.fonts.size.medium,
    color: theme.colors.darkGrey,
    marginBottom: '10px',
}

const saldoAmountStyle = {
    fontSize: theme.fonts.size.large,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.primary,
    marginBottom: '30px'
}

const penarikanHoverStyle = {
    backgroundColor: '#2d862d',
    transform: 'scale(1.05)',
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
    padding: '8px 16px',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    cursor: 'pointer',
    transition: '0.2s ease',
}

const buttonHoverStyle = {
    backgroundColor: '#2d862d',
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