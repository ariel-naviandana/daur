<template>
    <div :style="categoryListContainerStyle">
        <button class="navigation-button" @click="scrollCategoryList('left')">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <div class="category-list" :style="categoryListStyle">
            <button
                class="category-card"
                v-for="category in categories"
                :key="category.id"
                :style="categoryItemStyle"
                @click="selectCategory(category)">
                <img :src="category.image" :alt="category.name" :style="categoryIconStyle" />
                <span :style="categoryLabelStyle">{{ category.name }}</span>
            </button>
        </div>

        <button class="navigation-button" @click="scrollCategoryList('right')">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useCategoryApi } from '@/composables/useCategoryApi'
import { theme } from '@/helpers/theme'
import { Category } from '@/interfaces/Category'

const categories = ref<Category[]>([])
const { getCategories } = useCategoryApi()

const fetchCategories = async () => {
    try {
        const data = await getCategories()
        categories.value = data.map((category: Category) => ({
            id: category.id,
            name: category.name,
            image: category.image || '/images/icon_article.svg'
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
</script>

<style scoped>
::-webkit-scrollbar {
    display: none
}

.category-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.category-card:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.9);
}

.navigation-button {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 50%;
    background-color: white;
    color: #333; /* atau pakai: theme.colors.darkGrey jika inline style dihilangkan */
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    flex-shrink: 0;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.navigation-button:hover {
    background-color: #f0f0f0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
</style>
