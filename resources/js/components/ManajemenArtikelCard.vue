<template>
    <div :style="articleContainer">
        <div :style="articleIcon">
            <img
                v-if="article.image_url"
                :src="article.image_url"
                alt="cover"
                class="rounded-lg"
            />
            <img v-else src="/public/images/icon_article.svg" alt="Artikel" />
        </div>
        <div class="flex-grow">
            <h3 class="font-semibold" :style="articleTitle">{{ article.title }}</h3>
            <p class="text-sm text-green-600" :style="articleDate">
                {{ formatDate(article.created_at) }}
            </p>
        </div>
        <div class="flex space-x-2">
            <button
                @click="$emit('edit', article)"
                class="btn_edit"
                :style="[editButtonStyle, isHoverEdit ? buttonHoverStyleEdit : {}]"
                @mouseover="isHoverEdit = true"
                @mouseleave="isHoverEdit = false"
            >
                Edit
            </button>
            <button
                @click="$emit('delete', article.id)"
                class="btn_hapus"
                :style="[deleteButtonStyle, isHoverDelete ? buttonHoverStyleDelete : {}]"
                @mouseover="isHoverDelete = true"
                @mouseleave="isHoverDelete = false"
            >
                Hapus
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { theme } from '@/helpers/theme'
import type { Article } from '@/interfaces/Article'

const props = defineProps<{
    article: Article
}>()

const emit = defineEmits<{
    (e: 'edit', article: Article): void
    (e: 'delete', id: number): void
}>()

const isHoverEdit = ref(false)
const isHoverDelete = ref(false)

const formatDate = (dateStr?: string) => {
    if (!dateStr) return ''
    const date = new Date(dateStr)
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    })
}

const articleContainer = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: '1rem',
    marginBottom: '1rem',
    borderRadius: '24px',
    boxShadow: '0 4px 6px rgba(0,0,0,0.1)',
    backgroundColor: theme.colors.whiteElement,
}

const articleIcon = {
    width: '40px',
    height: '40px',
    overflow: 'hidden',
    border: 'none',
    marginRight: '1rem',
}

const articleTitle = {
    fontWeight: theme.fonts.weight.semibold,
    fontSize: theme.fonts.size.base,
    margin: 0,
}

const articleDate = {
    fontSize: theme.fonts.size.sm,
    color: theme.colors.green,
    margin: 0,
}

const editButtonStyle = {
    backgroundColor: theme.colors.yellow,
    color: theme.colors.whiteElement,
    fontSize: theme.fonts.size.base,
    borderRadius: '8px',
    height: '30px',
    width: '70px',
}

const buttonHoverStyleEdit = {
    backgroundColor: '#AC6600',
    transform: 'scale(1.05)',
}

const deleteButtonStyle = {
    backgroundColor: theme.colors.red,
    color: theme.colors.whiteElement,
    fontSize: theme.fonts.size.base,
    borderRadius: '8px',
    height: '30px',
    width: '70px',
    marginRight: '6px',
}

const buttonHoverStyleDelete = {
    backgroundColor: '#B5271D',
    transform: 'scale(1.05)',
}
</script>
