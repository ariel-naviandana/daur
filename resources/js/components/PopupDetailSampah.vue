<template>
    <div v-if="isOpen" :style="overlayStyle">
        <div :style="modalStyle">
            <div :style="headerStyle">
                <div :style="titleContainerStyle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" :style="iconStyle">
                        <path d="M19 4H5C3.89543 4 3 4.89543 3 6V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V6C21 4.89543 20.1046 4 19 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16 2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3 10H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2 :style="headingStyle">Plastik</h2>
                </div>
                <button @click="closeModal" :style="closeButtonStyle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <div :style="gridContainerStyle">
                <div v-for="(item, index) in wasteItems" :key="index"
                     :style="[cardStyle, item.active ? activeCardStyle : {}]"
                     @click="selectItem(index)">
                    <div :style="imageContainerStyle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                            <path d="M3 16L7 12L11 16L17 10L21 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <p :style="labelStyle">{{ item.name }}</p>
                </div>
            </div>

            <div :style="quantityContainerStyle">
                <button @click="decreaseQuantity" :style="minusButtonStyle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <span :style="quantityStyle">{{ quantity }} kg</span>
                <button @click="increaseQuantity" :style="plusButtonStyle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <button @click="addItems" :style="addButtonStyle">
                Tambah
            </button>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { theme } from '@/config/theme'

const props = defineProps<{
    isOpen: boolean
}>()

const emit = defineEmits(['close', 'add'])

const quantity = ref(1)
const wasteItems = ref([
    { name: 'Gelas Plastik', active: true },
    { name: 'Bungkus Makanan', active: false },
    { name: 'Botol Warna', active: false },
    { name: 'Sedotan', active: false },
    { name: 'Gelas Warna', active: false },
    { name: 'Botol Bening', active: false },
    { name: 'Perabot Plastik', active: false },
    { name: 'Mainan Plastik', active: false },
])

const selectItem = (index: number) => {
    wasteItems.value = wasteItems.value.map((item, i) => ({
        ...item,
        active: i === index
    }))
}

const increaseQuantity = () => {
    quantity.value++
}

const decreaseQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--
    }
}

const closeModal = () => {
    emit('close')
}

const addItems = () => {
    const selectedItem = wasteItems.value.find(item => item.active)
    emit('add', {
        type: selectedItem?.name,
        quantity: quantity.value
    })
    closeModal()
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
    zIndex: 1000,
}

const modalStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '16px',
    padding: '24px',
    width: '90%',
    maxWidth: '600px',
    maxHeight: '90vh',
    overflow: 'auto',
}

const headerStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: '24px',
}

const titleContainerStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '12px',
}

const iconStyle = {
    color: theme.colors.primary,
}

const headingStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    margin: 0,
    color: theme.colors.darkGrey,
}

const closeButtonStyle = {
    background: 'none',
    border: 'none',
    cursor: 'pointer',
    padding: '4px',
    color: theme.colors.darkGrey,
}

const gridContainerStyle = {
    display: 'grid',
    gridTemplateColumns: 'repeat(auto-fill, minmax(120px, 1fr))',
    gap: '16px',
    marginBottom: '24px',
}

const cardStyle = {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    padding: '16px',
    borderRadius: '12px',
    border: `2px solid #E5E7EB`,
    cursor: 'pointer',
    transition: 'all 0.2s ease',
}

const activeCardStyle = {
    borderColor: theme.colors.primary,
    backgroundColor: '#F0FDF4',
}

const imageContainerStyle = {
    width: '48px',
    height: '48px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: '8px',
    color: theme.colors.darkGrey,
}

const labelStyle = {
    margin: 0,
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    color: theme.colors.darkGrey,
    textAlign: 'center',
}

const quantityContainerStyle = {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    gap: '12px',
    marginBottom: '24px',
}

const minusButtonStyle = {
    width: '40px',
    height: '40px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    background: theme.colors.red,
    border: 'none',
    borderRadius: '50%',
    cursor: 'pointer',
    color: theme.colors.whiteElement,
    padding: 0,
}

const plusButtonStyle = {
    width: '40px',
    height: '40px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    background: theme.colors.primary,
    border: 'none',
    borderRadius: '50%',
    cursor: 'pointer',
    color: theme.colors.whiteElement,
    padding: 0,
}

const quantityStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.medium,
    color: theme.colors.darkGrey,
    minWidth: '60px',
    textAlign: 'center',
}

const addButtonStyle = {
    width: '120px',
    padding: '8px',
    justifyContent: 'center',
    alignItems: 'center',
    display: 'flex',
    margin: '0 auto',
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    border: 'none',
    borderRadius: '24px',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.bold,
    cursor: 'pointer',
    transition: 'background-color 0.2s ease',
}
</script>

<style scoped>
::-webkit-scrollbar {
    display: none;
}
</style>
