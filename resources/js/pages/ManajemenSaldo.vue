<template>
    <div :style="layoutStyle">
        <Navbar />
    </div>
</template>

<script lang="ts" setup>
import Navbar from '../components/Navbar.vue'
import { theme } from '../helpers/theme'

const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family,
}
</script>

<style scoped>
</style>
