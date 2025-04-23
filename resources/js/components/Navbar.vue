<template>
    <nav :style="navStyle">
        <ul class="flex gap-4">
            <li>
                <a href="/" :style="linkStyle('/')">Home</a>
            </li>
            <li>
                <a href="/profile" :style="linkStyle('/profile')">Profile</a>
            </li>
        </ul>
    </nav>
</template>

<script lang="ts" setup>
import { theme } from '../config/theme'

function linkStyle(path: string) {
    const isCurrent = window.location.pathname === path
    return {
        color: isCurrent ? theme.colors.primary : theme.colors.darkGrey,
        transition: 'all 0.3s ease',
    }
}

const navStyle = {
    backgroundColor: theme.colors.whiteElement,
    color: theme.colors.darkGrey,
    padding: '1rem',
    fontFamily: theme.fonts.family,
}
</script>

<style scoped>
a:hover {
    text-decoration: underline;
}
</style>
