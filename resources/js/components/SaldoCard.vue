<template>
    <div :style="saldoCardStyle">
        <div>
            <p :style="saldoLabelStyle">Saldo DAUR</p>
            <p :style="saldoAmountStyle">Rp. 25,000</p>
        </div>
        <button
            :style="[penarikanButtonStyle, isHover ? penarikanHoverStyle : {}]"
            @mouseover="isHover = true"
            @mouseleave="isHover = false"
            @click="tarikSaldo"
        >
            <img src="/public/images/withdraw-icon.svg" alt=""/>
            <div style="display: flex; justify-content: center; align-items: center;">
                <span>Penarikan dana</span>
            </div>
        </button>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { theme } from '@/helpers/theme'

const isHover = ref(false)

const penarikanButtonStyle = {
    width: '220px',
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
    gap: '10px',
    justifyContent: 'center',
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
}

const saldoCardStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '24px',
    boxShadow: '0 4px 20px rgba(0, 0, 0, 0.08)',
    padding: '2rem',
    display: 'flex',
    flexDirection: 'column',
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
</script>
