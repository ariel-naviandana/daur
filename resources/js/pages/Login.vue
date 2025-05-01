<template>
    <div :style="layoutStyle">
      <LoginCard />
    </div>
  </template>
  
  <script setup>
  import LoginCard from '@/components/LoginCard.vue'
  const layoutStyle = {
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    minHeight: '100vh',
    backgroundColor: '#F9F9F9',
  }
  </script>
  