<template>
    <div v-if="visible" :style="overlayStyle">
        <div :style="modalStyle">
            <p :style="labelStyle">Saldo DAUR</p>
            <p :style="valueStyle">Rp. {{ wallet?.balance ?? 0 }}</p>

            <p :style="sectionTitleStyle">Pilih tujuan</p>
            <div :style="radioGroupWrapper">
                <div
                    v-for="(option, i) in options"
                    :key="i"
                    :style="radioBox">
                    <label :style="radioLabelStyle">
                        <input type="radio" v-model="selectedOption" :value="option.name" />
                        {{ option.name }}
                    </label>
                </div>
            </div>

            <div v-if="selectedOption" style="margin-top: 30px">
                <p :style="sectionTitleStyle">Masukan nomor tujuan</p>
                <input
                type="text"
                v-model="destinationNumber"
                placeholder="Masukan nomor tujuan"
                :style="nominalInputStyle">
            </div>

            <p :style="sectionTitleStyle">Masukkan nominal</p>
            <input
                type="number"
                v-model="amount"
                placeholder="Rp."
                :style="nominalInputStyle"
            />

            <div v-if="errorMessage" style="color: red; text-align:center; margin-bottom:10px;">
                {{ errorMessage }}
            </div>

            <div :style="actionButtonsWrapper">
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
import { ref} from 'vue'
import { theme } from '@/helpers/theme'
import { Wallet } from "@/interfaces/Wallet"

const props = defineProps<{ visible: boolean, wallet: Wallet | null }>()
const emit = defineEmits(['close', 'submit'])

const selectedOption = ref('')
const amount = ref('')
const isHoverClose = ref(false)
const isHoverConfirm = ref(false)
const destinationNumber = ref('')
const errorMessage = ref('')

const options = [
    { name: 'Gopay' },
    { name: 'Ovo' },
    { name: 'Dana' },
    { name: 'BCA' },
    { name: 'BRI' },
    { name: 'BNI' },
    { name: 'Mandiri' }
]

const confirm = () => {
    errorMessage.value = ""
    if (!selectedOption.value) {
        errorMessage.value = "Silakan pilih tujuan transfer"
        return
    }
    if (!destinationNumber.value) {
        errorMessage.value = "Nomor tujuan wajib diisi"
        return
    }
    if (!amount.value || isNaN(Number(amount.value))) {
        errorMessage.value = "Nominal tidak valid"
        return
    }
    if (Number(amount.value) < 20000) {
        errorMessage.value = "Nominal transfer minimal Rp. 20,000"
        return
    }
    if (props.wallet && Number(amount.value) > props.wallet.balance) {
        errorMessage.value = "Saldo tidak mencukupi"
        return
    }
    emit('submit', {
        method: selectedOption.value.toLowerCase(),
        account_info: destinationNumber.value,
        amount: Number(amount.value)
    })
}

const overlayStyle = {
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

const modalStyle = {
    backgroundColor: theme.colors.whiteElement,
    padding: '2rem',
    borderRadius: '24px',
    width: '650px',
    boxShadow: '0 4px 20px rgba(0, 0, 0, 0.1)',
}

const labelStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.semibold,
    textAlign: 'center',
    color: theme.colors.darkGrey,
}

const valueStyle = {
    fontSize: theme.fonts.size.large,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.primary,
    textAlign: 'center',
    marginBottom: '50px'
}

const sectionTitleStyle = {
    fontWeight: theme.fonts.weight.semibold,
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey,
    textAlign: 'center',
    marginBottom: '10px',
}

const optionWrapperStyle = {
    marginBottom: '1rem',
}

const optionRowStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: '0.5rem 0',
    borderBottom: '1px solid #eee',
}

const radioLabelStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '8px',
}

const cancelBtnStyle = {
    backgroundColor: theme.colors.red,
    color: theme.colors.whiteElement,
    fontWeight: theme.fonts.weight.semibold,
    padding: '10px',
    width: '160px',
    // height: '50px',
    borderRadius: '999px',
    border: 'none',
    cursor: 'pointer',
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
}

const confirmBtnStyle = {
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    padding: '10px',
    width: '160px',
    // height: '50px',
    borderRadius: '999px',
    border: 'none',
    fontWeight: theme.fonts.weight.semibold,
    cursor: 'pointer',
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
}

const buttonHoverStyle = {
    transform: 'scale(1.05)',
}

const radioGroupWrapper = {
    display: 'grid',
    gridTemplateColumns: '1fr 1fr',
    gap: '20px',
    radius: '30px',
    marginBottom: '40px',
}

const radioBox = {
    display: 'flex',
    height: '60px',
    alignItems: 'center',
    justifyContent: 'space-between',
    padding: '1rem 1.5rem',
    backgroundColor: theme.colors.lightGrey,
    borderRadius: '24px',
    fontSize: '16px',
    fontWeight: theme.fonts.weight.medium,
    cursor: 'pointer',
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
}

const buttonHoverStyle = {
    transform: 'scale(1.05)',
}

const nominalInputStyle = {
    width: '100%',
    height: '60px',
    padding: '1rem 1.25rem',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    border: 'none',
    borderRadius: '24px',
    backgroundColor: theme.colors.lightGrey,
    color: theme.colors.darkGrey,
    marginBottom: '30px',
}

const actionButtonsWrapper = {
    display: 'flex',
    justifyContent: 'right',
    gap: '20px',
    marginTop: '20px',
}

const cancelBtn = {
    backgroundColor: '#EB5757',
    color: '#fff',
    border: 'none',
    padding: '12px 24px',
    borderRadius: '999px',
    fontWeight: 600,
    fontSize: '16px',
    cursor: 'pointer',
    boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)',
}

const confirmBtn = {
    backgroundColor: '#27AE60',
    color: '#fff',
    border: 'none',
    padding: '12px 24px',
    borderRadius: '999px',
    fontWeight: 600,
    fontSize: '16px',
    cursor: 'pointer',
    boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)',
}

</script>
