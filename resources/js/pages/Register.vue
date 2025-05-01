<template>
    <div :style="layoutStyle">
      <RegisterCard />
    </div>
  </template>
  
  <script setup>
  import RegisterCard from '@/components/RegisterCard.vue'
  const layoutStyle = {
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    minHeight: '100vh',
    backgroundColor: '#F9F9F9',
  }
  </script>
  