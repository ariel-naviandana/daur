<template>
    <div>
      <RecycleItem
        v-for="(item, index) in items"
        :key="index"
        v-bind="item"
      />
    </div>
  </template>
  
  <script setup lang="ts">
  import RecycleItem from './RecycleItem.vue'
  
  defineProps<{
    items: {
      name: string
      date: string
      material: string
      weight: string
      status: string
    }[]
  }>()
  </script>
  