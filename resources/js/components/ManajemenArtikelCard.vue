<template>
    <div :style="articleContainer">
        <div :style="articleIcon">
            <img
                v-if="article.image_url"
                :src="article.image_url"
                alt="cover"
                :style="imageStyle"
                @error="onImageError"
            />
            <img
                v-else
                src="/images/icon_article.svg"
                alt="Artikel"
                :style="imageStyle"
            />
        </div>
        <div :style="contentStyle">
            <h3 :style="articleTitle">{{ article.title }}</h3>
            <p :style="articleDate">{{ formatDate(article.created_at) }}</p>
        </div>
        <div :style="buttonGroupStyle">
            <button
                @click="$emit('edit', article)"
                :style="[editButtonStyle, isHoverEdit ? buttonHoverStyleEdit : {}]"
                @mouseover="isHoverEdit = true"
                @mouseleave="isHoverEdit = false"
            >
                Edit
            </button>
            <button
                @click="$emit('delete', article.id)"
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

const onImageError = (event: Event) => {
    const target = event.target as HTMLImageElement
    target.src = '/images/icon_article.svg'
}

// Style Definitions
const articleContainer = {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
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
    flexShrink: 0,
}

const imageStyle = {
    width: '100%',
    height: '100%',
    objectFit: 'cover',
    borderRadius: '12px',
}

const contentStyle = {
    flex: 1,
    marginRight: '1rem',
}

const articleTitle = {
    fontWeight: theme.fonts.weight.semibold,
    fontSize: theme.fonts.size.base,
    margin: 0,
}

const articleDate = {
    fontSize: theme.fonts.size.sm,
    color: theme.colors.primary,
    margin: 0,
}

const buttonGroupStyle = {
    display: 'flex',
    gap: '12px',
}

const editButtonStyle = {
    backgroundColor: theme.colors.yellow,
    color: theme.colors.whiteElement,
    fontSize: theme.fonts.size.base,
    borderRadius: '8px',
    padding: '8px',
    width: '90px',
    border: 'none',
    cursor: 'pointer',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
    transition: 'all 0.2s ease-in-out',
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
    padding: '8px',
    width: '90px',
    marginRight: '4px',
    border: 'none',
    cursor: 'pointer',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
    transition: 'all 0.2s ease-in-out',
}

const buttonHoverStyleDelete = {
    backgroundColor: '#B5271D',
    transform: 'scale(1.05)',
}
</script>
