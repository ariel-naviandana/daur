<template>
    <div :style="cardStyle" class="article-card">
        <img :src="article.image_url ? article.image_url : '/images/artikel2.jpg'" alt="artikel" :style="imageStyle" />
        <div :style="contentStyle">
            <h2 :style="titleStyle">{{ article.title }}</h2>
            <p :style="descStyle">{{ article.content }}</p>
            <p :style="dateStyle">{{ formatDate(article.created_at) }}</p>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { theme } from '@/helpers/theme'
import { Article } from '@/interfaces/Article'

defineProps<{
    article: Article
}>()

const formatDate = (dateStr?: string) => {
    if (!dateStr) return 'Tanggal tidak tersedia'
    const date = new Date(dateStr)
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    })
}

const cardStyle = {
    display: 'flex',
    flexDirection: 'row',
    width: '100%',
    backgroundColor: '#fff',
    borderRadius: '12px',
    boxShadow: '0 2px 8px rgba(0, 0, 0, 0.05)',
    padding: '12px',
    gap: '16px',
    height: 'auto',
    alignItems: 'flex-start',
}

const imageStyle = {
    width: '300px',
    height: '180px',
    objectFit: 'cover',
    borderRadius: '8px',
}

const contentStyle = {
    flex: 1,
}

const titleStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    marginBottom: '6px',
}

const descStyle = {
    fontSize: theme.fonts.size.small,
    color: theme.colors.grey,
    marginBottom: '6px',
    overflow: 'hidden',
    textOverflow: 'ellipsis',
    display: '-webkit-box',
    WebkitLineClamp: 3,
    WebkitBoxOrient: 'vertical',
}

const dateStyle = {
    color: theme.colors.primary,
    fontSize: theme.fonts.size.small,
    fontWeight: theme.fonts.weight.medium,
    marginTop: '22px',
}
</script>

<style scoped>
.article-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.article-card:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
}
</style>
