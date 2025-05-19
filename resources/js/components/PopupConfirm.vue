<template>
    <div v-if="isOpen" :style="overlayStyle">
        <div :style="modalStyle">
            <h3 :style="titleStyle">{{ title }}</h3>
            <p :style="messageStyle">{{ message }}</p>
            <div :style="buttonContainerStyle">
                <button @click="onCancel" :style="cancelButtonStyle">{{ cancelText }}</button>
                <button @click="onConfirm" :style="confirmButtonStyle">{{ confirmText }}</button>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { theme } from '@/helpers/theme'

const props = defineProps<{
    isOpen: boolean
    title: string
    message: string
    confirmText?: string
    cancelText?: string
}>()

const emit = defineEmits(['close', 'confirm'])

const onConfirm = () => {
    emit('confirm')
}

const onCancel = () => {
    emit('close')
}

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
    justifyContent: 'space-evenly',
    marginTop: '20px'
}

const confirmButtonStyle = {
    backgroundColor: theme.colors.primary,
    color: "white",
    border: "none",
    padding: "6px 28px",
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    borderRadius: "24px",
    cursor: "pointer",
}

const cancelButtonStyle = {
    backgroundColor: theme.colors.lightGrey,
    color: "black",
    border: "none",
    padding: "6px 28px",
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    borderRadius: "24px",
    cursor: "pointer",
}
</script>
