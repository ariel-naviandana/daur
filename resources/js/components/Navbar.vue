<template>
    <nav :style="navStyle">
        <div class="flex justify-between items-center">
            <div class="logo">
                <a href="/">
                    <img src="../../../public/images/logo-daur.png" alt="DAUR Logo" :style="logoStyle" />
                </a>
            </div>

            <button class="block md:hidden" @click="toggleSidebar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <ul class="hidden md:flex gap-8">
                <li>
                    <a href="/" :style="linkStyle('/')">Home</a>
                </li>
                <li>
                    <a href="/recycle" :style="linkStyle('/recycle')">Recycle</a>
                </li>
                <li>
                    <a href="/artikel" :style="linkStyle('/artikel')">Artikel</a>
                </li>
                <li>
                    <a href="/chat" :style="linkStyle('/chat')">Chat</a>
                </li>
            </ul>

            <div class="hidden md:block">
                <a href="/profile" :style="profileLinkStyle">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="35"
                        height="35"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        :style="iconStyle"
                    >
                        <circle cx="12" cy="12" r="10"></circle>
                        <circle cx="12" cy="10" r="3"></circle>
                        <path d="M7 18.5c.9-2.3 2.5-3.5 5-3.5s4.1 1.2 5 3.5"></path>
                    </svg>
                </a>
            </div>
        </div>

        <div
            class="fixed top-0 right-0 w-2/3 h-full bg-white shadow-lg z-50 p-6 transition-transform transform md:hidden"
            :class="{ 'translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            <button class="mb-6" @click="toggleSidebar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <ul class="flex flex-col gap-6">
                <li><a href="/" @click="closeSidebar" :style="linkStyle('/')">Home</a></li>
                <li><a href="/recycle" @click="closeSidebar" :style="linkStyle('/recycle')">Recycle</a></li>
                <li><a href="/artikel" @click="closeSidebar" :style="linkStyle('/artikel')">Artikel</a></li>
                <li><a href="/chat" @click="closeSidebar" :style="linkStyle('/chat')">Chat</a></li>
                <li><a href="/profile" @click="closeSidebar" :style="linkStyle('/profile')">Profil</a></li>
            </ul>
        </div>
    </nav>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { theme } from '@/config/theme'

const sidebarOpen = ref(false)
const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value
}
const closeSidebar = () => {
    sidebarOpen.value = false
}

function linkStyle(path: string) {
    const isCurrent = window.location.pathname === path
    return {
        color: isCurrent ? theme.colors.primary : theme.colors.darkGrey,
        transition: 'all 0.3s ease',
        fontWeight: theme.fonts.weight.medium,
    }
}

const logoStyle = {
    width: '80px',
    height: 'auto',
}

const navStyle = {
    backgroundColor: theme.colors.whiteElement,
    color: theme.colors.darkGrey,
    padding: '1rem 2rem',
    fontFamily: theme.fonts.family,
}

const iconStyle = {
    color: theme.colors.darkGrey,
}

const profileLinkStyle = {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
}
</script>

<style scoped>
a:hover {
    text-decoration: underline;
}
</style>
