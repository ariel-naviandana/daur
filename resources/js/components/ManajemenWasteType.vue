<template>
    <div>
        <div :style="headerStyle">
            <button :style="addButtonStyle" @click="openCreatePopup">
                Tambah Jenis Sampah
            </button>
        </div>
        <div v-if="wasteTypes.length === 0" :style="emptyStateStyle">
            <p>Tidak ada jenis sampah tersedia</p>
        </div>
        <div v-else :style="listStyle">
            <WasteTypeCard
                v-for="wasteType in wasteTypes"
                :key="wasteType.id"
                :wasteType="wasteType"
                @edit="openEditPopup(wasteType)"
                @delete="openDeletePopup(wasteType)"
            />
        </div>

        <WasteTypeFormPopup
            v-if="showFormPopup"
            :wasteType="selectedWasteType"
            @close="closeFormPopup"
            @save="handleSave"
        />

        <PopupDelete
            v-if="showDeletePopup"
            :is-open="showDeletePopup"
            :item-name="selectedWasteType?.name || ''"
            @close="closeDeletePopup"
            @confirm="handleDelete"
        />
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import WasteTypeCard from './WasteTypeCard.vue'
import WasteTypeFormPopup from './PopupFormWasteType.vue'
import PopupDelete from './PopupDelete.vue'
import { WasteType } from '../interfaces/WasteType'
import { theme } from '../config/theme'

const selectedWasteType = ref<WasteType | null>(null)
const wasteTypes = ref<WasteType[]>([
    {
        id: 1,
        category_id: 1,
        name: 'Kertas',
        unit: 'kg',
        price_per_unit: 2000,
        image: '/images/ic_jenis_kertas.svg',
    },
    {
        id: 2,
        category_id: 1,
        name: 'Plastik',
        unit: 'kg',
        price_per_unit: 1500,
        image: '/images/ic_jenis_botol_plastik.svg',
    },
])

const showFormPopup = ref(false)
const showDeletePopup = ref(false)

const openCreatePopup = () => {
    selectedWasteType.value = null
    showFormPopup.value = true
}

const openEditPopup = (wasteType: WasteType) => {
    selectedWasteType.value = wasteType
    showFormPopup.value = true
}

const openDeletePopup = (wasteType: WasteType) => {
    selectedWasteType.value = wasteType
    showDeletePopup.value = true
}

const closeFormPopup = () => {
    showFormPopup.value = false
}

const closeDeletePopup = () => {
    showDeletePopup.value = false
}

const handleSave = (wasteType: WasteType) => {
    if (wasteType.id) {
        const index = wasteTypes.value.findIndex((w: WasteType) => w.id === wasteType.id)
        if (index !== -1) wasteTypes.value[index] = wasteType
    } else {
        wasteType.id = Date.now()
        wasteTypes.value.push(wasteType)
    }
    closeFormPopup()
}

const handleDelete = () => {
    wasteTypes.value = wasteTypes.value.filter((w: WasteType) => w.id !== selectedWasteType.value?.id)
    closeDeletePopup()
}

const headerStyle = {
    display: 'flex',
    justifyContent: 'flex-end',
    marginBottom: '16px',
}

const addButtonStyle = {
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    padding: '8px 16px',
    borderRadius: '6px',
    fontSize: theme.fonts.size.base,
    cursor: 'pointer',
    border: 'none',
}

const listStyle = {
    display: 'grid',
    gridTemplateColumns: 'repeat(auto-fill, minmax(200px, 1fr))',
    gap: '16px',
}

const emptyStateStyle = {
    textAlign: 'center',
    fontSize: theme.fonts.size.base,
    color: theme.colors.grey,
}
</script>
