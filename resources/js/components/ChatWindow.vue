<template>
    <div v-if="selectedBank" :style="chatWindow">
        <div :style="chatHeader">
            <img :src="selectedBank.avatar" alt="avatar" :style="avatar" />
            <h2>{{ selectedBank.name }}</h2>
        </div>
        <div :style="chatMessages">
            <div
                v-for="(message, index) in messages"
                :key="index"
                :style="[messageWrapper, style.message.from === 'me' ? messageWrapperMe : {}]"
            >
                <div
                    :style="[
                        style.message,
                        message.from === 'me' ? style.messageMe : style.messageThem
                    ]"
                >
                    <p>{{ message.text }}</p>
                </div>
                <small
                    :style="[
                        style.messageTime,
                        message.from === 'me' ? { textAlign: 'right', alignSelf: 'flex-end', marginRight: '14px' } : { textAlign: 'left', alignSelf: 'flex-start', marginLeft: '14px' }
                    ]"
                >
                    {{ message.time }}
                </small>
            </div>
        </div>
        <div :style="chatInput">
            <button :style="iconButton">
                <img src="/public/images/camera-icon.svg" alt="Camera" :style="iconImg" />
            </button>
            <input
                v-model="newMessage"
                placeholder="Tulis Pesan"
                @keyup.enter="sendMessage"
                :style="input"
            />
            <button :style="iconButton" @click="sendMessage">
                <img src="/public/images/send-icon.svg" alt="Send" :style="iconImg" />
            </button>
        </div>
    </div>
    <div v-else :style="emptyState">
        <p>Pilih bank sampah untuk mulai chatting</p>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import {theme} from "@/helpers/theme"

const props = defineProps({
    selectedBank: Object,
})

const messages = ref([
    { from: 'me', text: 'Halo Kak, saya mau bertanya mengenai panduan penggunaan aplikasi DAUR', time: '12.28 PM' },
    { from: 'them', text: 'Halo, Kak Radit. Terima kasih sudah menghubungi kami...', time: '12.32 PM' },
    { from: 'me', text: 'Baik, Terima kasih!', time: '12.36 PM' },
])

const newMessage = ref('')

function sendMessage() {
    if (newMessage.value.trim() !== '') {
        messages.value.push({
            from: 'me',
            text: newMessage.value,
            time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
        })
        newMessage.value = ''
    }
}


const chatWindow = {
    flex: 1,
    display: 'flex',
    flexDirection: 'column',
    borderTop: '1px solid #ccc',
    borderBottom: '1px solid #ccc',
    borderRight: '1px solid #ccc',
}
const chatHeader = {
    display: 'flex',
    height: '60px',
    alignItems: 'center',
    padding: '20px',
    borderBottom: '1px solid #ccc',
}
const avatar = {
    width: '36px',
    height: '36px',
    borderRadius: '50%',
    marginRight: '1rem',
}
const chatMessages = {
    flex: 1,
    display: 'flex',
    flexDirection: 'column',
    padding: '1rem',
    overflowY: 'auto',
    backgroundColor: '#fafafa',
}
const messageWrapper = {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'flex-start',
    marginBottom: '1rem',
}
const messageWrapperMe = {
    alignItems: 'flex-end',
}
const style = {
    message: {
        display: 'inline-block',
        maxWidth: '60%',
        paddingLeft: '14px',
        paddingRight: '14px',
        paddingTop: '10px',
        paddingBottom: '10px',
        borderRadius: '14px',
        wordWrap: 'break-word',
        fontSize: theme.fonts.size.base,
    },
    messageTime: {
        marginTop: '4px',
        fontSize: '12px',
        marginRight: '20px',
        marginLeft: '20px',
        color: '#888888',
    },
    messageMe: {
        alignSelf: 'flex-end',
        backgroundColor: '#a3d4a5',
    },
    messageThem: {
        alignSelf: 'flex-start',
        backgroundColor: '#eeeeee',
    }
}

const chatInput = {
    display: 'flex',
    padding: '1rem',
}
const input = {
    flex: 1,
    marginLeft: '10px',
    marginRight: '10px',
    paddingLeft: '20px',
    borderRadius: '30px',
    border: 'none',
    backgroundColor: '#fafafa',
    fontSize: '20px',
    height: '50px',
}
const iconButton = {
    background: 'none',
    border: 'none',
    cursor: 'pointer',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
}
const iconImg = {
    width: '40px',
    height: '40px',
}

const emptyState = {
    flex: 1,
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
}

</script>

<style scoped>

</style>
