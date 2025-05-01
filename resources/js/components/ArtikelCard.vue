<template>
    <div :style="cardStyle">
      <img :src="image" alt="artikel" :style="imageStyle" />
      <div :style="contentStyle">
        <h2 :style="titleStyle">{{ title }}</h2>
        <p :style="descStyle">{{ description }}</p>
        <p :style="dateStyle">{{ date }}</p>
      </div>
    </div>
  </template>

  <script lang="ts" setup>
  defineProps({
    title: String,
    description: String,
    date: String,
    image: String
  })

  import { theme } from '@/config/theme'

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
    borderRadius: '8px'
    }

  const contentStyle = {
    flex: 1
  }

  const titleStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    marginBottom: '6px'
  }

  const descStyle = {
    fontSize: theme.fonts.size.small,
    color: theme.colors.grey,
    marginBottom: '6px',
    overflow: 'hidden',
    textOverflow: 'ellipsis',
    display: '-webkit-box',
    WebkitLineClamp: 3,
    WebkitBoxOrient: 'vertical'
    }

  const dateStyle = {
    color: theme.colors.primary,
    fontSize: theme.fonts.size.small,
    fontWeight: theme.fonts.weight.medium,
    marginTop: '22px'
  }
  </script>

  <style scoped>
  </style>
