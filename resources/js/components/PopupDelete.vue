<template>
    <div v-if="isOpen" :style="overlayStyle">
        <div :style="modalStyle">
            <h3 :style="titleStyle">Konfirmasi Hapus</h3>
            <p :style="messageStyle">
                Apakah Anda yakin ingin menghapus item <strong>{{ itemName }}</strong> dari keranjang?
            </p>
            <div :style="buttonContainerStyle">
                <button @click="onClose" :style="cancelButtonStyle">Batal</button>
                <button @click="onConfirm" :style="confirmButtonStyle">Hapus</button>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { defineProps, defineEmits } from "vue"
import { theme } from "@/config/theme"

const props = defineProps<{
    isOpen: boolean
    itemName: string
}>()

const emit = defineEmits(['close', 'confirm'])

const onConfirm = () => {
    emit("confirm")
}

const onClose = () => {
    emit("close")
}

const overlayStyle = {
    position: "fixed",
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    backgroundColor: "rgba(0, 0, 0, 0.5)",
    display: "flex",
    alignItems: "center",
    justifyContent: "center",
    zIndex: 1000,
}

const modalStyle = {
    backgroundColor: "white",
    padding: "20px",
    borderRadius: "10px",
    width: "300px",
    boxShadow: "0 2px 10px rgba(0, 0, 0, 0.2)",
    textAlign: "center",
}

const titleStyle = {
    fontSize: "18px",
    fontWeight: "bold",
    marginBottom: "10px",
    color: theme.colors.darkGrey,
}

const messageStyle = {
    fontSize: "14px",
    color: theme.colors.darkGrey,
    marginBottom: "20px",
}

const buttonContainerStyle = {
    display: "flex",
    justifyContent: "space-evenly",
}

const confirmButtonStyle = {
    backgroundColor: theme.colors.red,
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
