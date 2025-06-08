<template>
    <div v-if="isOpen" :style="overlayStyle">
        <div :style="modalStyle">
            <div :style="headerStyle">
                <div :style="titleContainerStyle">
                    <img :src="selectedCategory?.image" :alt="selectedCategory?.name || 'Icon Sampah'" :style="iconStyle" />
                    <h2 :style="headingStyle">{{ selectedCategory?.name || 'Detail Sampah' }}</h2>
                </div>
                <button
                    @click="closeModal"
                    :style="[closeButtonStyle, isHoverClose ? closeButtonHoverStyle : {}]"
                    @mouseover="isHoverClose = true"
                    @mouseleave="isHoverClose = false"
                    aria-label="Close modal"
                >
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <div :style="gridContainerStyle">
                <div v-for="(item, index) in wasteTypes" :key="item.id"
                     :style="[cardStyle, selectedItem?.id === item.id ? activeCardStyle : {}]"
                     @click="selectItem(item)">
                    <img v-if="item.image" :src="item.image" :alt="item.name" :style="iconStyle" />
                    <img v-else src="/images/icon_article.svg" :alt="item.name" :style="iconStyle" />
                    <p :style="labelStyle">{{ item.name }}</p>
                    <p :style="priceStyle">Rp{{ item.price_per_unit.toLocaleString('id-ID') }}</p>
                </div>
            </div>

            <div :style="quantityContainerStyle">
                <button
                    @click="decreaseQuantity"
                    :style="[minusButtonStyle, isHoverMinus ? buttonHoverStyleMinus : {}]"
                    @mouseover="isHoverMinus = true"
                    @mouseleave="isHoverMinus = false"
                >
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <span :style="quantityStyle">
                    {{ quantity }} {{ selectedItem?.unit || '' }}
                </span>
                <button
                    @click="increaseQuantity"
                    :style="[plusButtonStyle, isHoverPlus ? buttonHoverStylePlus : {}]"
                    @mouseover="isHoverPlus = true"
                    @mouseleave="isHoverPlus = false"
                >
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <button
                @click="addItems"
                :style="[addButtonStyle, isHoverAdd ? buttonHoverStyleAdd : {}]"
                @mouseover="isHoverAdd = true"
                @mouseleave="isHoverAdd = false"
            >
                Tambah
            </button>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'
import { theme } from '@/helpers/theme'
import { useWasteTypeApi } from '@/composables/useWasteTypeApi'
import { WasteType } from '@/interfaces/WasteType'
import { Category } from '@/interfaces/Category'
import { RecycleTransactionItem } from '@/interfaces/RecycleTransactionItem'
const isHoverClose = ref(false)

const props = defineProps<{
    isOpen: boolean
    selectedCategory: Category | null
}>()

const emit = defineEmits(['close', 'add'])

const quantity = ref(1)
const wasteTypes = ref<WasteType[]>([])
const selectedItem = ref<WasteType | null>(null)
const { getWasteTypesByCategory } = useWasteTypeApi()
const isHoverMinus = ref(false)
const isHoverPlus = ref(false)
const isHoverAdd = ref(false)

const fetchWasteTypes = async (categoryId: number) => {
    try {
        wasteTypes.value = await getWasteTypesByCategory(categoryId)
    } catch (error) {
        console.error('Error fetching waste types:', error)
    }
}

watch(
    () => props.selectedCategory,
    async (newCategory) => {
        if (newCategory) {
            await fetchWasteTypes(newCategory.id)
            selectedItem.value = null
        }
    },
    { immediate: true }
)

const selectItem = (item: WasteType) => {
    selectedItem.value = item
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
    selectedItem.value = null
}

const addItems = () => {
    if (selectedItem.value) {
        const transactionItem: RecycleTransactionItem = {
            id: 0,
            transaction_id: 0,
            waste_type_id: selectedItem.value.id,
            quantity: quantity.value,
            sub_total: quantity.value * selectedItem.value.price_per_unit,
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString()
        }
        emit('add', transactionItem)
        closeModal()
    }
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
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '16px',
    padding: '24px',
    width: '90%',
    maxWidth: '600px',
    maxHeight: '90vh',
    overflow: 'auto'
}

const headerStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: '24px'
}

const titleContainerStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '12px'
}

const iconStyle = {
    width: '50px',
    height: '50px',
    objectFit: 'cover',
    alignItems: 'center',
    justifyContent: 'center',
    display: 'flex',
    marginBottom: '8px'
}

const headingStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    margin: 0,
    color: theme.colors.darkGrey
}

const closeButtonStyle = {
    background: 'none',
    border: 'none',
    cursor: 'pointer',
    padding: '4px',
    color: theme.colors.darkGrey,
    transition: 'color 0.2s ease, transform 0.2s ease',
}

const gridContainerStyle = {
    display: 'grid',
    gridTemplateColumns: 'repeat(auto-fill, minmax(120px, 1fr))',
    gap: '16px',
    marginBottom: '24px'
}

const cardStyle = {
    display: 'flex',
    flexDirection: 'column',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: '12px',
    borderRadius: '12px',
    border: `2px solid ${theme.colors.lightGrey}`,
    cursor: 'pointer',
    transition: 'all 0.2s ease'
}

const activeCardStyle = {
    borderColor: theme.colors.primary,
    backgroundColor: theme.colors.whiteElement
}

const labelStyle = {
    margin: 0,
    fontSize: theme.fonts.size.small,
    fontWeight: theme.fonts.weight.medium,
    color: theme.colors.darkGrey,
    textAlign: 'center'
}

const priceStyle = {
    fontSize: theme.fonts.size.small,
    fontWeight: theme.fonts.weight.medium,
    color: theme.colors.primary,
    textAlign: 'center'
}

const quantityContainerStyle = {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    gap: '12px',
    marginBottom: '24px'
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
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
}

const buttonHoverStyleMinus = {
    backgroundColor: '#B5271D',
    transform: 'scale(1.05)',
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
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
}

const buttonHoverStylePlus = {
    backgroundColor: '#2d862d',
    transform: 'scale(1.05)',
}

const quantityStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.medium,
    color: theme.colors.darkGrey,
    minWidth: '60px',
    textAlign: 'center'
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
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
}

const buttonHoverStyleAdd = {
    backgroundColor: '#2d862d',
    transform: 'scale(1.05)',
}

const closeButtonHoverStyle = {
    color: theme.colors.black,   // Contoh: ganti warna saat hover
    transform: 'scale(1.1)',
}
</script>

<style scoped>
::-webkit-scrollbar {
    display: none
}
</style>
