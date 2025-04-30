<template>
    <div :style="layoutStyle">
        <Navbar />
        <div :style="contentStyle" class="p-6">
            <h1 :style="headingStyle">This is your Artikel</h1>
        </div>
    </div>
</template>

<script lang="ts" setup>
import Navbar from '../components/Navbar.vue'
import { theme } from '@/config/theme'

const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family,
}

const contentStyle = {
    color: theme.colors.darkGrey,
}

const headingStyle = {
    fontSize: theme.fonts.size.heading,
    fontWeight: theme.fonts.weight.bold,
}
</script>

<style scoped>
</style>
