<template>
    <div class="flex justify-between items-center rounded-lg p-4 shadow-sm mb-2"
         :style="{ backgroundColor: theme.colors.whiteElement }">
      <div>
        <p :style="nameStyle">{{ name }}</p>
        <p :style="dateStyle">{{ date }}</p>
      </div>
      <div class="flex items-center gap-4">
        <p :style="materialStyle">{{ material }}</p>
        <p :style="weightStyle">{{ weight }}</p>
        <span :style="[statusBadgeStyle, statusBackgroundStyle]">
          {{ status }}
        </span>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { computed } from 'vue'
  import { theme } from '@/config/theme'
  
  const props = defineProps<{
    name: string
    date: string
    material: string
    weight: string
    status: string
  }>()
  
  const nameStyle = {
    fontWeight: theme.fonts.weight.semibold,
    fontSize: theme.fonts.size.medium,
    color: theme.colors.darkGrey,
  }
  
  const dateStyle = {
    fontSize: theme.fonts.size.base,
    color: theme.colors.lightGrey,
  }
  
  const materialStyle = {
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey,
  }
  
  const weightStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.green,
  }
  
  const statusBadgeStyle = {
    display: 'inline-flex',
    alignItems: 'center',
    justifyContent: 'center',
    padding: '4px 12px',
    borderRadius: '10px',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    color: theme.colors.whiteElement,
    textTransform: 'capitalize',
    width: '100px',
    height: '40px',
    textAlign: 'center',
    lineHeight: '16px',
  }
  
  const statusBackgroundStyle = computed(() => {
    const statusLower = props.status.toLowerCase()
    if (statusLower === 'sukses') return { backgroundColor: theme.colors.primary }
    if (statusLower === 'diproses') return { backgroundColor: theme.colors.yellow }
    if (statusLower === 'dibatalkan') return { backgroundColor: theme.colors.red }
    return { backgroundColor: theme.colors.lightGrey }
  })
  </script>
  