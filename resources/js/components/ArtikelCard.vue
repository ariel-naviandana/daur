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
    width: '100%',
    maxWidth: '800px', 
    height: '300px',
    backgroundColor: '#fff',
    borderRadius: '16px',
    boxShadow: '0 2px 6px rgba(0, 0, 0, 0.1)',
    padding: '12px',
    margin: '0 auto 24px auto', 
    alignItems: 'center',
    gap: '16px'
  }
  
  const imageStyle = {
    width: '320px',
    height: '220px',
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
    marginBottom: '6px'
  }
  
  const dateStyle = {
    color: theme.colors.green,
    fontSize: theme.fonts.size.small,
    fontWeight: theme.fonts.weight.medium
  }
  </script>
  
  <style scoped>
  </style>