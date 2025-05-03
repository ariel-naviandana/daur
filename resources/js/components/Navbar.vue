<template>
    <nav :style="navStyle">
        <div class="flex justify-between items-center">
            <div class="logo">
                <a href="/">
                    <img src="/public/images/logo-daur.png" alt="DAUR Logo" :style="logoStyle" />
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
                    <a :href="isAdmin ? '/admin' : '/'" @click="closeSidebar" :style="linkStyle(isAdmin ? '/admin' : '/')">Home</a>
                </li>
                <li v-if="isAdmin">
                    <a href="/admin/sampah" :style="linkStyle('/admin/sampah')">Sampah</a>
                </li>
                <li>
                    <a @click="redirectIfNotLoggedIn(isAdmin ? '/admin/recycle' : '/recycle')" :style="linkStyle(isAdmin ? '/admin/recycle' : '/recycle')" role="button">Recycle</a>
                </li>
                <li>
                    <a :href="isAdmin ? '/admin/artikel' : '/artikel'" @click="closeSidebar" :style="linkStyle(isAdmin ? '/admin/artikel' : '/artikel')">Artikel</a>
                </li>
                <li>
                    <a @click="redirectIfNotLoggedIn('/chat')" :style="linkStyle('/chat')" role="button">Chat</a>
                </li>
                <li v-if="isAdmin">
                    <a href="/admin/users" :style="linkStyle('/admin/users')">User</a>
                </li>
                <li v-if="isAdmin">
                    <a href="/admin/saldo" :style="linkStyle('/admin/saldo')">Saldo</a>
                </li>
            </ul>

            <div class="hidden md:block">
                <template v-if="!loggedInUser">
                    <a href="/login" style="color: white; font-weight: bold; background-color: #4CAF50; padding: 6px 28px; border-radius: 24px">Login</a>
                </template>
                <template v-else>
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
                </template>
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
                <li>
                    <a :href="isAdmin ? '/admin' : '/'" @click="closeSidebar" :style="linkStyle(isAdmin ? '/admin' : '/')">Home</a>
                </li>
                <li v-if="isAdmin">
                    <a href="/admin/sampah" :style="linkStyle('/admin/sampah')">Sampah</a>
                </li>
                <li>
                    <a @click="redirectIfNotLoggedIn(isAdmin ? '/admin/recycle' : '/recycle')" :style="linkStyle(isAdmin ? '/admin/recycle' : '/recycle')" role="button">Recycle</a>
                </li>
                <li><a :href="isAdmin ? '/admin/artikel' : '/artikel'" @click="closeSidebar" :style="linkStyle(isAdmin ? '/admin/artikel' : '/artikel')">Artikel</a></li>
                <li>
                    <a @click="redirectIfNotLoggedIn('/chat')" :style="linkStyle('/chat')" role="button">Chat</a>
                </li>
                <li v-if="isAdmin">
                    <a href="/admin/users" :style="linkStyle('/admin/users')">User</a>
                </li>
                <li v-if="isAdmin">
                    <a href="/admin/saldo" :style="linkStyle('/admin/saldo')">Saldo</a>
                </li>
                <li>
                    <a href="/profile" @click="closeSidebar" :style="linkStyle('/profile')">Profil</a>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue'
import { theme } from '@/config/theme'

const sidebarOpen = ref(false)
const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value
}
const closeSidebar = () => {
    sidebarOpen.value = false
}

const loggedInUser = JSON.parse(localStorage.getItem('user') || 'null')

const isAdmin = computed(() => loggedInUser?.email === 'admin@gmail.com')

function redirectIfNotLoggedIn(path: string) {
    if (!loggedInUser) {
        alert('Anda harus login untuk mengakses fitur ini!')
        window.location.href = '/login'
    } else {
        window.location.href = path
    }
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

a[role="button"] {
    cursor: pointer;
}
</style>
