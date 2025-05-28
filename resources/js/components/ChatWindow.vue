<template>
    <div v-if="selectedContact" :style="chatWindow">
        <div :style="chatHeader">
            <img v-if="selectedContact.profile_picture" :src="selectedContact.profile_picture" alt="avatar" :style="avatar" />
            <img v-else src="/public/images/profile-icon.svg" alt="avatar" :style="avatar" />
            <h2>{{ selectedContact.name }}</h2>
        </div>
        <div :style="chatMessages">
            <div
                v-for="message in messages"
                :key="message.id"
                :style="[messageWrapper, message.sender_id === currentUser.id ? messageWrapperMe : {}]"
            >
                <div
                    :style="[
                        style.message,
                        message.sender_id === currentUser.id ? style.messageMe : style.messageThem
                    ]"
                >
                    <p v-if="message.message">{{ message.message }}</p>
                    <img v-if="message.image_url" :src="message.image_url" :style="messageImage" />
                </div>
                <small
                    :style="[
                        style.messageTime,
                        message.sender_id === currentUser.id ? { textAlign: 'right', alignSelf: 'flex-end', marginRight: '14px' } : { textAlign: 'left', alignSelf: 'flex-start', marginLeft: '14px' }
                    ]"
                >
                    {{ formatTime(message.sent_at) }}
                </small>
            </div>
        </div>
        <div :style="chatInput">
            <input
                type="file"
                accept="image/*"
                ref="fileInput"
                style="display: none"
                @change="uploadImage"
            />
            <button :style="iconButton" @click="$refs.fileInput.click()" :disabled="isUploading">
                <img src="/public/images/camera-icon.svg" alt="Camera" :style="iconImg" />
            </button>
            <input
                v-model="newMessage"
                placeholder="Tulis Pesan"
                @keyup.enter="sendMessage"
                :style="input"
                :disabled="isUploading"
            />
            <button :style="iconButton" @click="sendMessage" :disabled="isUploading">
                <img src="/public/images/send-icon.svg" alt="Send" :style="iconImg" />
            </button>
        </div>
    </div>
    <div v-else :style="emptyState">
        <p>Pilih kontak untuk mulai chatting</p>
    </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import { theme } from '@/helpers/theme'
import { useChatApi } from '@/composables/useChatApi'
import { useImageApi } from '@/composables/useImageApi'
import { User } from '@/interfaces/User'
import { Chat } from '@/interfaces/Chat'

const props = defineProps<{
    selectedContact: User | null
    currentUser: User | null
}>()

const { getChats, saveChat } = useChatApi()
const { uploadToCloudinary, isUploading } = useImageApi()

const messages = ref<Chat[]>([])
const newMessage = ref('')
const fileInput = ref<HTMLInputElement | null>(null)

async function fetchChats() {
    if (props.selectedContact && props.currentUser) {
        const chats = await getChats() as Chat[]
        messages.value = chats.filter(chat =>
            (chat.sender_id === props.currentUser?.id && chat.receiver_id === props.selectedContact.id) ||
            (chat.sender_id === props.selectedContact.id && chat.receiver_id === props.currentUser?.id)
        ).sort((a, b) => new Date(a.sent_at!).getTime() - new Date(b.sent_at!).getTime())
    } else {
        messages.value = []
    }
}

watch(() => props.selectedContact, fetchChats)

onMounted(() => {
    fetchChats()
    setInterval(fetchChats, 5000)
})

async function sendMessage() {
    if (newMessage.value.trim() === '' && !fileInput.value?.files?.length) return
    const chat: Chat = {
        sender_id: props.currentUser!.id,
        receiver_id: props.selectedContact!.id,
        message: newMessage.value || null,
        image_url: null,
    }
    const success = await saveChat(chat)
    if (success) {
        await fetchChats()
        newMessage.value = ''
    }
}

async function uploadImage(event: Event) {
    const file = (event.target as HTMLInputElement).files?.[0]
    if (!file) return
    const validTypes = ['image/jpeg', 'image/png', 'image/gif']
    const maxSize = 5 * 1024 * 1024
    if (!validTypes.includes(file.type)) {
        alert('Hanya file JPEG, PNG, atau GIF yang diperbolehkan.')
        return
    }
    if (file.size > maxSize) {
        alert('Ukuran file maksimum adalah 5MB.')
        return
    }
    const imageUrl = await uploadToCloudinary(file)
    if (imageUrl) {
        const chat: Chat = {
            sender_id: props.currentUser!.id,
            receiver_id: props.selectedContact!.id,
            message: null,
            image_url: imageUrl,
        }
        const success = await saveChat(chat)
        if (success) {
            await fetchChats()
            fileInput.value!.value = ''
        }
    }
}

function formatTime(sentAt?: string) {
    if (!sentAt) return ''
    const date = new Date(sentAt)
    return isNaN(date.getTime()) ? '' : date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
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
    },
}

const messageImage = {
    maxWidth: '100%',
    borderRadius: '8px',
    marginTop: '8px',
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
    paddingBottom: '4px',
    borderRadius: '30px',
    border: 'none',
    backgroundColor: '#fafafa',
    fontSize: theme.fonts.size.base,
    height: '40px',
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
