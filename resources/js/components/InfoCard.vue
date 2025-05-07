<template>
<div class="p-4 shadow rounded-lg flex items-center gap-4 w-full" :style="{ backgroundColor: theme.colors.whiteElement }">
    <div class="text-3xl">
      <slot name="icon" />
    </div>
    <div>
      <p :style="labelStyle">{{ title }}</p>
      <p :style="valueStyle">{{ value }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { theme } from '@/helpers/theme'

defineProps<{ title: string; value: string }>()

const labelStyle = {
  color: theme.colors.grey,
  fontSize: theme.fonts.size.medium,
  fontWeight: theme.fonts.weight.semibold,
}

const valueStyle = {
  color: theme.colors.primary,
  fontSize: theme.fonts.size.heading,
  fontWeight: theme.fonts.weight.bold,
}
</script>
