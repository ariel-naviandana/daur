<template>
    <div class="chat-window" v-if="selectedBank">
        <div class="chat-header">
            <img :src="selectedBank.avatar" alt="avatar" class="avatar" />
            <h2>{{ selectedBank.name }}</h2>
        </div>
        <div class="chat-messages">
            <div v-for="(message, index) in messages" :key="index" class="message-wrapper" :class="message.from">
                <div :class="['message', message.from]">
                    <p>{{ message.text }}</p>
                </div>
                <small class="message-time">{{ message.time }}</small>
            </div>
        </div>
        <div class="chat-input">
            <button class="icon-button">
                <img src="/public/images/camera-icon.svg" alt="Camera" />
            </button>
            <input v-model="newMessage" placeholder="Tulis Pesan" @keyup.enter="sendMessage" />
            <button class="icon-button" @click="sendMessage">
                <img src="/public/images/send-icon.svg" alt="Send" />
            </button>
        </div>
    </div>
    <div v-else class="empty-state">
        <p>Pilih bank sampah untuk mulai chatting</p>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    selectedBank: Object,
});

const messages = ref([
    { from: 'me', text: 'Halo Kak, saya mau bertanya mengenai panduan penggunaan aplikasi DAUR', time: '12.28 pm' },
    { from: 'them', text: 'Halo, Kak Radit. Terima kasih sudah menghubungi kami...', time: '12.32 pm' },
    { from: 'me', text: 'Baik, Terima kasih!', time: '12.36 pm' },
]);

const newMessage = ref('');

function sendMessage() {
    if (newMessage.value.trim() !== '') {
        messages.value.push({
            from: 'me',
            text: newMessage.value,
            time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
        });
        newMessage.value = '';
    }
}
</script>

<style scoped>
/* Chat Window */
.chat-window {
    flex: 1;
    display: flex;
    flex-direction: column;
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
    border-right: 1px solid #ccc;
}

/* Chat Header */
.chat-header {
    display: flex;
    height: 60px;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #ccc;
}

/* Avatar Header */
.avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 1rem;
}

/* Chat Messages */
.chat-messages {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 1rem;
    overflow-y: auto;
    background-color: #fafafa;
}
.message-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin-bottom: 1rem;
}
.message-wrapper.me {
    align-items: flex-end;
}
.message {
    display: inline-block;
    max-width: 70%;
    padding-left: 24px;
    padding-right: 24px;
    border-radius: 30px;
    word-wrap: break-word;
    font-size: 20px;
}
.message.me {
    align-self: flex-end;
    align-items: flex-end;
    background-color: #a3d4a5;
}
.message.them {
    align-self: flex-start;
    background-color: #eeeeee;
}
.message-time {
    margin-top: 4px;
    font-size: 12px;
    margin-right: 20px;
    margin-left: 20px;
    color: #888888;
}

/* Chat Input */
.chat-input {
    display: flex;
    padding: 1rem;
}
.chat-input input {
    flex: 1;
    margin-left: 10px;
    margin-right: 10px;
    padding-left: 20px;
    border-radius: 30px;
    border: none;
    background-color: #fafafa;
    font-size: 20px;
    height: 50px;
}

/* Input Button */
.icon-button {
    background: none;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}
.icon-button img {
    width: 40px;
    height: 40px;
}

/* Belum memilih chat */
.empty-state {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
