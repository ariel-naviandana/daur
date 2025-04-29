<template>
    <div class="message-input-wrapper">
        <button class="icon-button">
            <img src="/public/images/camera-icon.svg" alt="Camera" />
        </button>
        <input
            type="text"
            v-model="message"
            placeholder="Tulis Pesan"
            class="message-input"
            @keyup.enter="emitMessage"
        />
        <button class="icon-button" @click="emitMessage">
            <img src="/public/images/send-icon.svg" alt="Send" />
        </button>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const emit = defineEmits(['send']);
const message = ref('');

function emitMessage() {
    if (message.value.trim() !== '') {
        emit('send', message.value);
        message.value = ''; // Kosongkan input setelah kirim
    }
}
</script>

<style scoped>
/* Bar Input */
.message-input-wrapper {
    display: flex;
    align-items: center;
    padding: 10px;
    border: none;
    background-color: #fff;
}

/* Bar Input Pesan */
.message-input {
    flex-grow: 1;
    border: none;
    outline: none;
    padding: 10px;
    font-size: 16px;
}

/* Icon Tombol Kamera dan Kirim */
.icon-button {
    background: none;
    border: none;
    margin: 0 5px;
    cursor: pointer;
}
.icon-button img {
    width: 24px;
    height: 24px;
}
</style>
