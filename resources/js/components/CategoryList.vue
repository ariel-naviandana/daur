<template>
    <div :style="categoryListContainerStyle">
        <button :style="navigationButtonStyle" @click="scrollCategoryList('left')">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <div class="category-list" :style="categoryListStyle">
            <button
                v-for="category in categories"
                :key="category.id"
                :style="categoryItemStyle"
                @click="selectCategory(category)">
                <img :src="category.image" :alt="category.name" :style="categoryIconStyle" />
                <span :style="categoryLabelStyle">{{ category.name }}</span>
            </button>
        </div>

        <button :style="navigationButtonStyle" @click="scrollCategoryList('right')">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { theme } from '@/helpers/theme'
import { Category } from '../interfaces/Category'

const categories = ref<Category[]>([])

const fetchCategories = async () => {
    try {
        const { data } = await axios.get('/categories')
        categories.value = data.map((category: any) => ({
            id: category.id,
            name: category.name,
            image: category.image || '/images/ic_jenis_kertas.svg'
        }))
    } catch (error) {
        console.error('Error fetching categories:', error)
    }
}

onMounted(fetchCategories)

const emit = defineEmits(['category-clicked'])

const selectCategory = (category: Category) => {
    emit('category-clicked', category)
}

const scrollCategoryList = (direction: 'left' | 'right') => {
    const categoryListRef = document.querySelector('.category-list') as HTMLElement
    if (categoryListRef) {
        const scrollAmount = categoryListRef.clientWidth
        categoryListRef.scrollBy({
            left: direction === 'left' ? -scrollAmount : scrollAmount,
            behavior: 'smooth'
        })
    }
}

const categoryListContainerStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    gap: '16px',
    padding: '16px 0'
}

const navigationButtonStyle = {
    width: '40px',
    height: '40px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    border: 'none',
    borderRadius: '50%',
    backgroundColor: 'white',
    color: theme.colors.darkGrey,
    cursor: 'pointer',
    boxShadow: '0 1px 3px rgba(0, 0, 0, 0.1)',
    flexShrink: 0
}

const categoryListStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    gap: '16px',
    overflow: 'hidden',
    overflowX: 'auto',
    padding: '8px 4px',
    margin: '0 -4px'
}

const categoryItemStyle = {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    margin: '0 10px',
    gap: '8px',
    padding: '16px',
    border: 'none',
    background: 'white',
    cursor: 'pointer',
    borderRadius: '16px',
    boxShadow: '0 2px 4px rgba(0, 0, 0, 0.05)',
    minWidth: '100px'
}

const categoryIconStyle = {
    width: '40px',
    height: '40px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center'
}

const categoryLabelStyle = {
    fontSize: '0.875rem',
    color: theme.colors.darkGrey,
    fontWeight: 500
}
</script>

<style scoped>
::-webkit-scrollbar {
    display: none;
}
</style>
