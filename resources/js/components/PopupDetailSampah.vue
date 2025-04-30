<template>
    <div v-if="isOpen" :style="styles.overlayStyle">
        <div :style="styles.modalStyle">
            <div :style="styles.headerStyle">
                <div :style="styles.titleContainerStyle">
                    <img :src="selectedCategory?.icon" :alt="selectedCategory?.name || 'Icon Sampah'" :style="styles.iconStyle" />
                    <h2 :style="styles.headingStyle">{{ selectedCategory?.name || 'Detail Sampah' }}</h2>
                </div>
                <button @click="closeModal" :style="styles.closeButtonStyle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <div :style="styles.gridContainerStyle">
                <div v-for="(item, index) in wasteItems" :key="index"
                     :style="[styles.cardStyle, item.active ? styles.activeCardStyle : {}]"
                     @click="selectItem(index)">
                    <div :style="styles.imageContainerStyle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                            <path d="M3 16L7 12L11 16L17 10L21 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <p :style="styles.labelStyle">{{ item.name }}</p>
                    <p :style="styles.priceStyle">Rp{{ item.pricePerKg.toLocaleString('id-ID') }}</p>
                </div>
            </div>

            <div :style="styles.quantityContainerStyle">
                <button @click="decreaseQuantity" :style="styles.minusButtonStyle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <span :style="styles.quantityStyle">{{ quantity }} kg</span>
                <button @click="increaseQuantity" :style="styles.plusButtonStyle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <button @click="addItems" :style="styles.addButtonStyle">
                Tambah
            </button>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { theme } from '@/config/theme'

const props = defineProps<{
    isOpen: boolean,
    selectedCategory: { id: number; name: string; icon: string } | null
}>()

const emit = defineEmits(['close', 'add'])

const quantity = ref(1)
const wasteItems = ref([
    { name: 'Gelas Plastik', pricePerKg: 5000, active: false },
    { name: 'Bungkus Makanan', pricePerKg: 3000, active: false },
    { name: 'Botol Warna', pricePerKg: 4000, active: false },
    { name: 'Sedotan', pricePerKg: 2000, active: false },
    { name: 'Gelas Warna', pricePerKg: 4500, active: false },
    { name: 'Botol Bening', pricePerKg: 5500, active: false },
    { name: 'Perabot Plastik', pricePerKg: 6000, active: false },
    { name: 'Mainan Plastik', pricePerKg: 3500, active: false },
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
    quantity.value = 1
}

const addItems = () => {
    const selectedItem = wasteItems.value.find(item => item.active)
    emit('add', {
        type: selectedItem?.name,
        quantity: quantity.value,
        pricePerKg: selectedItem?.pricePerKg
    })
    closeModal()
}

const styles = {
    overlayStyle: {
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
    },
    modalStyle: {
        backgroundColor: theme.colors.whiteElement,
        borderRadius: '16px',
        padding: '24px',
        width: '90%',
        maxWidth: '600px',
        maxHeight: '90vh',
        overflow: 'auto',
    },
    headerStyle: {
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center',
        marginBottom: '24px',
    },
    titleContainerStyle: {
        display: 'flex',
        alignItems: 'center',
        gap: '12px',
    },
    iconStyle: {
        width: '30px',
        height: '30px',
        objectFit: 'cover',
        marginRight: '12px',
    },
    headingStyle: {
        fontSize: theme.fonts.size.medium,
        fontWeight: theme.fonts.weight.bold,
        margin: 0,
        color: theme.colors.darkGrey,
    },
    closeButtonStyle: {
        background: 'none',
        border: 'none',
        cursor: 'pointer',
        padding: '4px',
        color: theme.colors.darkGrey,
    },
    gridContainerStyle: {
        display: 'grid',
        gridTemplateColumns: 'repeat(auto-fill, minmax(120px, 1fr))',
        gap: '16px',
        marginBottom: '24px',
    },
    cardStyle: {
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'space-between',
        alignItems: 'center',
        padding: '12px',
        borderRadius: '12px',
        border: `2px solid ${theme.colors.lightGrey}`,
        cursor: 'pointer',
        transition: 'all 0.2s ease',
    },
    activeCardStyle: {
        borderColor: theme.colors.primary,
        backgroundColor: theme.colors.whiteElement,
    },
    imageContainerStyle: {
        width: '48px',
        height: '48px',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        marginBottom: '8px',
        color: theme.colors.darkGrey,
    },
    labelStyle: {
        margin: 0,
        fontSize: theme.fonts.size.small,
        fontWeight: theme.fonts.weight.medium,
        color: theme.colors.darkGrey,
        textAlign: 'center',
    },
    priceStyle: {
        fontSize: theme.fonts.size.small,
        fontWeight: theme.fonts.weight.medium,
        color: theme.colors.primary,
        textAlign: 'center',
    },
    quantityContainerStyle: {
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        gap: '12px',
        marginBottom: '24px',
    },
    minusButtonStyle: {
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
    },
    plusButtonStyle: {
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
    },
    quantityStyle: {
        fontSize: theme.fonts.size.medium,
        fontWeight: theme.fonts.weight.medium,
        color: theme.colors.darkGrey,
        minWidth: '60px',
        textAlign: 'center',
    },
    addButtonStyle: {
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
    },
}
</script>

<style scoped>
::-webkit-scrollbar {
    display: none;
}
</style>
