<template>
    <div>
        <div :style="headerStyle">
            <button :style="addButtonStyle" @click="openCreatePopup">
                Tambah Kategori
            </button>
        </div>
        <div v-if="categories.length === 0" :style="emptyStateStyle">
            <p>Tidak ada kategori tersedia</p>
        </div>
        <div v-else :style="listStyle">
            <CategoryCard
                v-for="category in categories"
                :key="category.id"
                :category="category"
                @edit="openEditPopup(category)"
                @delete="openDeletePopup(category)"
            />
        </div>

        <CategoryFormPopup
            v-if="showFormPopup"
            :category="selectedCategory"
            @close="closeFormPopup"
            @save="handleSave"
        />

        <PopupDelete
            v-if="showDeletePopup"
            :is-open="showDeletePopup"
            :item-name="selectedCategory?.name || ''"
            @close="closeDeletePopup"
            @confirm="handleDelete"
        />
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import CategoryCard from './CategoryCard.vue'
import CategoryFormPopup from './PopupFormCategory.vue'
import PopupDelete from './PopupDelete.vue'
import { Category } from '../interfaces/Category'
import { theme } from '../config/theme'

const selectedCategory = ref<Category | null>(null)
const categories = ref<Category[]>([
    { id: 1, name: 'Kertas', image: '/images/ic_jenis_kertas.svg' },
    { id: 2, name: 'Plastik', image: '/images/ic_jenis_botol_plastik.svg' },
    { id: 3, name: 'Kaca', image: '/images/ic_jenis_botol_kaca.svg' },
    { id: 4, name: 'Besi', image: '/images/ic_jenis_besi.svg' },
    { id: 5, name: 'Aluminium', image: '/images/ic_jenis_aluminium.svg' },
])

const showFormPopup = ref(false)
const showDeletePopup = ref(false)

const openCreatePopup = () => {
    selectedCategory.value = null
    showFormPopup.value = true
}

const openEditPopup = (category: Category) => {
    selectedCategory.value = category
    showFormPopup.value = true
}

const openDeletePopup = (category: Category) => {
    selectedCategory.value = category
    showDeletePopup.value = true
}

const closeFormPopup = () => {
    showFormPopup.value = false
}

const closeDeletePopup = () => {
    showDeletePopup.value = false
}

const handleSave = (category: Category) => {
    if (category.id) {
        const index = categories.value.findIndex((c: Category) => c.id === category.id)
        if (index !== -1) categories.value[index] = category
    } else {
        category.id = Date.now()
        categories.value.push(category)
    }
    closeFormPopup()
}

const handleDelete = () => {
    categories.value = categories.value.filter((c: Category) => c.id !== selectedCategory.value.id)
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
