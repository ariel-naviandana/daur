<template>
    <div v-if="visible" :style="overlayStyle">
        <div :style="modalStyle">
            <p :style="labelStyle">Saldo DAUR</p>
            <p :style="valueStyle">Rp. 25,000</p>

            <p :style="sectionTitleStyle">Pilih tujuan</p>
            <div :style="optionWrapperStyle">
                <div
                    v-for="(option, i) in options"
                    :key="i"
                    :style="optionRowStyle"
                >
                    <label :style="radioLabelStyle">
                        <input type="radio" v-model="selectedOption" :value="option.name" />
                        {{ option.name }}
                    </label>
                    <span>{{ option.number }}</span>
                </div>
            </div>

            <p :style="sectionTitleStyle">Masukkan nominal</p>
            <input
                type="text"
                v-model="amount"
                placeholder="Rp."
                :style="inputStyle"
            />

            <div :style="buttonRowStyle">
                <button
                    :style="[cancelBtnStyle, isHoverClose ? buttonHoverStyle : {}]"
                    @mouseover="isHoverClose = true"
                    @mouseleave="isHoverClose = false"
                    @click="$emit('close')"
                >
                    Batal
                </button>
                <button
                    :style="[confirmBtnStyle, isHoverConfirm ? buttonHoverStyle : {}]"
                    @mouseover="isHoverConfirm = true"
                    @mouseleave="isHoverConfirm = false"
                    @click="confirm"
                >
                    Konfirmasi
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { theme } from '@/helpers/theme'

defineProps<{ visible: boolean }>()
const emit = defineEmits(['close', 'submit'])

const selectedOption = ref('')
const amount = ref('')
const isHoverClose = ref(false)
const isHoverConfirm = ref(false)

const options = [
    { name: 'Gopay', number: '081291219002' },
    { name: 'Ovo', number: '081291219002' },
    { name: 'Dana', number: '081291219002' },
    { name: 'Bank Uwang', number: '1019273038' },
]

const confirm = () => {
    emit('submit', { destination: selectedOption.value, amount: amount.value })
}
</script>

<script lang="ts">
export const overlayStyle = {
    position: 'fixed',
    top: '0',
    left: '0',
    width: '100vw',
    height: '100vh',
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    zIndex: 1000
}

export const modalStyle = {
    backgroundColor: theme.colors.whiteElement,
    padding: '2rem',
    borderRadius: '24px',
    width: '500px',
    boxShadow: '0 4px 20px rgba(0, 0, 0, 0.1)',
}

export const labelStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.semibold,
    color: theme.colors.darkGrey,
}

export const valueStyle = {
    fontSize: theme.fonts.size.large,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.primary,
    marginBottom: '1.5rem'
}

export const sectionTitleStyle = {
    fontWeight: theme.fonts.weight.semibold,
    fontSize: theme.fonts.size.base,
    marginBottom: '0.5rem',
    color: theme.colors.darkGrey,
}

export const optionWrapperStyle = {
    marginBottom: '1rem',
}

export const optionRowStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: '0.5rem 0',
    borderBottom: '1px solid #eee',
}

export const radioLabelStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '8px',
}

export const inputStyle = {
    width: '100%',
    padding: '10px',
    fontSize: theme.fonts.size.base,
    borderRadius: '8px',
    border: '1px solid #ccc',
    marginBottom: '1.5rem'
}

export const buttonRowStyle = {
    display: 'flex',
    justifyContent: 'center',
    gap: '1rem'
}

export const cancelBtnStyle = {
    backgroundColor: '#ccc',
    color: '#000',
    padding: '10px',
    width: '160px',
    borderRadius: '999px',
    border: 'none',
    fontWeight: theme.fonts.weight.semibold,
    cursor: 'pointer',
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
}

export const confirmBtnStyle = {
    backgroundColor: theme.colors.primary,
    color: '#fff',
    padding: '10px',
    width: '160px',
    borderRadius: '999px',
    border: 'none',
    fontWeight: theme.fonts.weight.semibold,
    cursor: 'pointer',
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
}

const buttonHoverStyle = {
    transform: 'scale(1.05)',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
    transition: '0.2s ease-in-out',
}
</script>
