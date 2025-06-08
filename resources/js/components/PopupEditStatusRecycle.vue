<template>
    <div :style="overlayStyle">
        <div :style="popupStyle">
            <h3 :style="titleStyle">Konfirmasi</h3>
            <p :style="messageStyle">{{ message }}</p>
            <div :style="buttonGroupStyle">
                <button
                    type="button"
                    :style="[cancelButtonStyle, isHoverCancel ? buttonHoverStyleCancel : {}]"
                    @mouseover="isHoverCancel = true"
                    @mouseleave="isHoverCancel = false"
                    @click="$emit('cancel')"
                >
                    Batal
                </button>
                <button
                    type="button"
                    :style="[confirmButtonStyle, isHoverConfirm ? buttonHoverStyleConfirm : {}]"
                    @mouseover="isHoverConfirm = true"
                    @mouseleave="isHoverConfirm = false"
                    @click="$emit('confirm')"
                >
                    Ya
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { theme } from '@/helpers/theme'

const props = defineProps({
    message: { type: String, required: true }
})
const emit = defineEmits(['confirm', 'cancel'])

const isHoverCancel = ref(false)
const isHoverConfirm = ref(false)

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
    zIndex: 1500,
}

const popupStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '16px',
    padding: '24px',
    width: '320px',
    boxShadow: '0 4px 6px rgba(0, 0, 0, 0.1)',
    textAlign: 'center',
}

const titleStyle = {
    marginBottom: '16px',
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.darkGrey,
}

const messageStyle = {
    marginBottom: '24px',
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey,
}

const buttonGroupStyle = {
    display: 'flex',
    justifyContent: 'center',
    gap: '16px',
}

const cancelButtonStyle = {
    padding: "6px",
    width: '110px',
    borderRadius: '24px',
    backgroundColor: theme.colors.lightGrey,
    color: theme.colors.darkGrey,
    border: 'none',
    cursor: 'pointer',
    fontSize: theme.fonts.size.base,
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
    transition: '0.2s ease-in-out',
}

const confirmButtonStyle = {
    padding: "6px",
    width: '110px',
    borderRadius: '24px',
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    border: 'none',
    cursor: 'pointer',
    fontSize: theme.fonts.size.base,
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
    transition: '0.2s ease-in-out',
}

const buttonHoverStyleCancel = {
    backgroundColor: theme.colors.grey,
    transform: 'scale(1.05)',
}

const buttonHoverStyleConfirm = {
    backgroundColor: '#2d862d',
    transform: 'scale(1.05)',
}
</script>

<style scoped>
::-webkit-scrollbar {
    display: none;
}
</style>
