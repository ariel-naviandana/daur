<template>
    <div v-if="isOpen" :style="overlayStyle">
        <div :style="modalStyle">
            <h3 :style="titleStyle">Konfirmasi Perubahan</h3>
            <p :style="messageStyle">
                Apakah Anda yakin ingin menyimpan perubahan item <strong>{{ itemName }}</strong>?
            </p>
            <div :style="buttonContainerStyle">
                <button
                    @click="onClose"
                    :style="[cancelButtonStyle, isHoverCancel ? buttonHoverStyleCancel : {}]"
                    @mouseover="isHoverCancel = true"
                    @mouseleave="isHoverCancel = false"
                >
                    Batal
                </button>
                <button
                    @click="onConfirm"
                    :style="[confirmButtonStyle, isHoverConfirm ? buttonHoverStyleConfirm : {}]"
                    @mouseover="isHoverConfirm = true"
                    @mouseleave="isHoverConfirm = false"
                >
                    Simpan
                </button>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { theme } from '@/helpers/theme'

const isHoverCancel = ref(false)
const isHoverConfirm = ref(false)

const props = defineProps<{
    isOpen: boolean
    itemName: string
    title?: string
}>()

const emit = defineEmits(['close', 'confirm'])

const onConfirm = () => emit('confirm')
const onClose = () => emit('close')

// Styles
const overlayStyle = {
    position: 'fixed',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    zIndex: 1000
}

const modalStyle = {
    backgroundColor: 'white',
    padding: '20px',
    borderRadius: '10px',
    width: '300px',
    boxShadow: '0 2px 10px rgba(0, 0, 0, 0.2)',
    textAlign: 'center'
}

const titleStyle = {
    fontSize: '18px',
    fontWeight: 'bold',
    marginBottom: '10px',
    color: theme.colors.darkGrey
}

const messageStyle = {
    fontSize: '14px',
    color: theme.colors.darkGrey,
    marginBottom: '20px'
}

const buttonContainerStyle = {
    display: 'flex',
    justifyContent: 'space-evenly'
}

const confirmButtonStyle = {
    backgroundColor: theme.colors.primary,
    color: 'white',
    border: 'none',
    padding: "6px",
    width: '110px',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    borderRadius: '24px',
    cursor: 'pointer',
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)'
}

const cancelButtonStyle = {
    backgroundColor: theme.colors.lightGrey,
    color: 'black',
    border: 'none',
    padding: "6px",
    width: '110px',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    borderRadius: '24px',
    cursor: 'pointer',
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)'
}

const buttonHoverStyleConfirm = {
    backgroundColor: '#2d862d',
    transform: 'scale(1.05)'
}

const buttonHoverStyleCancel = {
    backgroundColor: theme.colors.grey,
    transform: 'scale(1.05)'
}
</script>
