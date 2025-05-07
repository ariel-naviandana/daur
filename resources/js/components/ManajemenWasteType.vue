<template>
    <div>
        <div :style="headerWithFiltersStyle">
            <div class="filters" :style="filtersWrapperStyle">
                <div :style="leftFilterStyle">
                    <select v-model="selectedCategoryFilter" :style="selectStyle">
                        <option value="">Semua Kategori</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                    <select v-model="selectedSort" :style="selectStyle">
                        <option value="alphabet-asc">Nama (A-Z)</option>
                        <option value="alphabet-desc">Nama (Z-A)</option>
                        <option value="price-asc">Harga (Termurah)</option>
                        <option value="price-desc">Harga (Termahal)</option>
                    </select>
                </div>
                <div :style="searchWrapperStyle">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        :style="searchIconStyle"
                    >
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input
                        type="text"
                        v-model="searchQuery"
                        placeholder="Cari jenis sampah"
                        :style="searchInputStyle"
                    />
                </div>
            </div>
            <button :style="addButtonStyle" @click="openCreatePopup">
                Tambah
            </button>
        </div>

        <div v-if="filteredAndSortedWasteTypes.length === 0" :style="noResultsStyle">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="48"
                height="48"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <p :style="noResultsTextStyle">Tidak ada hasil yang ditemukan</p>
            <p :style="noResultsDescStyle">
                Coba sesuaikan filter atau kata kunci pencarian Anda
            </p>
        </div>
        <div v-else :style="listStyle">
            <WasteTypeCard
                v-for="wasteType in filteredAndSortedWasteTypes"
                :key="wasteType.id"
                :wasteType="wasteType"
                @edit="openEditPopup(wasteType)"
                @delete="openDeletePopup(wasteType)"
            />
        </div>

        <WasteTypeFormPopup
            v-if="showFormPopup"
            :wasteType="selectedWasteType"
            :categories="categories"
            @close="closeFormPopup"
            @saved="fetchWasteTypes"
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
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import WasteTypeCard from './WasteTypeCard.vue'
import WasteTypeFormPopup from './PopupFormWasteType.vue'
import PopupDelete from './PopupDelete.vue'
import { WasteType } from '../interfaces/WasteType'
import { Category } from '../interfaces/Category'
import { theme } from '../helpers/theme'

const selectedWasteType = ref<WasteType | null>(null)
const wasteTypes = ref<WasteType[]>([])
const categories = ref<Category[]>([])
const selectedSort = ref<string>('alphabet-asc')
const selectedCategoryFilter = ref<number | ''>('')
const searchQuery = ref<string>('')
const showFormPopup = ref(false)
const showDeletePopup = ref(false)

const fetchWasteTypes = async () => {
    try {
        const { data } = await axios.get('/waste-types')
        wasteTypes.value = data
    } catch (error) {
        console.error('Error fetching waste types:', error)
    }
}

const fetchCategories = async () => {
    try {
        const { data } = await axios.get('/categories')
        categories.value = data
    } catch (error) {
        console.error('Error fetching categories:', error)
    }
}

onMounted(async () => {
    await fetchWasteTypes()
    await fetchCategories()
})

const filteredAndSortedWasteTypes = computed(() => {
    let filtered = wasteTypes.value
    if (selectedCategoryFilter.value) {
        filtered = filtered.filter(wasteType => wasteType.category_id === selectedCategoryFilter.value)
    }
    if (searchQuery.value) {
        filtered = filtered.filter(wasteType =>
            wasteType.name.toLowerCase().includes(searchQuery.value.toLowerCase())
        )
    }
    if (selectedSort.value === 'alphabet-asc') {
        filtered = filtered.sort((a, b) => a.name.localeCompare(b.name))
    } else if (selectedSort.value === 'alphabet-desc') {
        filtered = filtered.sort((a, b) => b.name.localeCompare(a.name))
    } else if (selectedSort.value === 'price-asc') {
        filtered = filtered.sort((a, b) => a.price_per_unit - b.price_per_unit)
    } else if (selectedSort.value === 'price-desc') {
        filtered = filtered.sort((a, b) => b.price_per_unit - a.price_per_unit)
    }
    return filtered
})

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

const handleDelete = async () => {
    try {
        if (selectedWasteType.value) {
            await axios.delete(`/waste-types/${selectedWasteType.value.id}`)
            wasteTypes.value = wasteTypes.value.filter(
                (w: WasteType) => w.id !== selectedWasteType.value?.id
            )
        }
        closeDeletePopup()
    } catch (error) {
        console.error('Error deleting waste type:', error)
    }
}

const headerWithFiltersStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: '16px',
    flexWrap: 'wrap',
    gap: '16px',
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

const filtersWrapperStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '12px',
    flexWrap: 'wrap',
}

const leftFilterStyle = {
    display: 'flex',
    gap: '12px',
}

const searchWrapperStyle = {
    display: 'flex',
    alignItems: 'center',
    backgroundColor: theme.colors.whiteElement,
    border: `1px solid ${theme.colors.lightGrey}`,
    borderRadius: '100px',
    padding: '8px 16px',
    width: '300px',
    flexGrow: 1,
}

const searchIconStyle = {
    color: theme.colors.lightGrey,
    marginRight: '12px',
}

const searchInputStyle = {
    border: 'none',
    outline: 'none',
    width: '100%',
    fontSize: theme.fonts.size.base,
    backgroundColor: 'transparent',
    color: theme.colors.darkGrey,
    fontFamily: theme.fonts.family,
}

const selectStyle = {
    padding: '8px 12px',
    paddingRight: '36px',
    fontSize: theme.fonts.size.base,
    borderRadius: '6px',
    border: `1px solid ${theme.colors.lightGrey}`,
    fontFamily: theme.fonts.family,
    appearance: 'none',
    backgroundImage: `url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e")`,
    backgroundRepeat: 'no-repeat',
    backgroundPosition: 'right 8px center',
    backgroundSize: '16px',
}

const listStyle = {
    display: 'grid',
    gridTemplateColumns: 'repeat(auto-fill, minmax(200px, 1fr))',
    gap: '16px',
}

const noResultsStyle = {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    padding: '48px 0',
    textAlign: 'center',
}

const noResultsTextStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.darkGrey,
    margin: '0 0 8px 0',
}

const noResultsDescStyle = {
    fontSize: theme.fonts.size.base,
    color: theme.colors.grey,
    margin: 0,
}
</script>
