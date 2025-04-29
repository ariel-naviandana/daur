<template>
    <Navbar />
    <div class="chat-page-wrapper">
        <div class="chat-page">
            <ChatList :banks="banks" :selectedBank="selectedBank" @select-bank="selectedBank = $event" />
            <ChatWindow :selectedBank="selectedBank" />
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import ChatList from './ChatList.vue';
import ChatWindow from './ChatWindow.vue';
import Navbar from "@/components/Navbar.vue";

const banks = ref([
    { id: 1, name: 'Bank Sampah Lowokwaru', avatar: 'https://i.pravatar.cc/40?img=1' },
    { id: 2, name: 'Daur', avatar: 'https://i.pravatar.cc/40?img=2' },
    { id: 3, name: 'Bank Sampah Sawojajar', avatar: 'https://i.pravatar.cc/40?img=3' },
    { id: 4, name: 'Bank Sampah Singosari', avatar: 'https://i.pravatar.cc/40?img=4' },
    { id: 5, name: 'Bank Sampah Idjen', avatar: 'https://i.pravatar.cc/40?img=5' },
    { id: 6, name: 'Bank Sampah Sumbersari', avatar: 'https://i.pravatar.cc/40?img=6' },
    { id: 7, name: 'Bank Sampah Dieng', avatar: 'https://i.pravatar.cc/40?img=7' },
]);

const selectedBank = ref(null);
</script>

<style scoped>
/* Chat Wrapper */
.chat-page-wrapper {
    width: 100%;
    max-width: 1440px;
    margin: 0 auto;
    height: 90vh;
}

/* Halaman Chat */
.chat-page {
    display: flex;
    height: 896px;
}
</style>
