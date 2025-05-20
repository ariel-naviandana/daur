<template>
    <Navbar />
    <div :style="chatPageWrapper">
        <div :style="chatHeader">
            <img :style="chatHeaderImg" src="/public/images/ic_chat.svg" alt="Chat">
            <h2>Chat</h2>
        </div>
        <div :style="chatPage">
            <ChatList :contacts="contacts" :selectedContact="selectedContact" @select-contact="selectedContact = $event" />
            <ChatWindow :selectedContact="selectedContact" :currentUser="currentUser" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import ChatList from '@/components/ChatList.vue'
import ChatWindow from '@/components/ChatWindow.vue'
import Navbar from '@/components/Navbar.vue'
import { theme } from '@/helpers/theme'
import { useAuthApi } from '@/composables/useAuthApi'
import { useUserApi } from '@/composables/useUserApi'
import { useChatApi } from '@/composables/useChatApi'
import { User } from '@/interfaces/User'
import { Chat } from '@/interfaces/Chat'

const { getCurrentUser } = useAuthApi()
const { getAdmins } = useUserApi()
const { getChats } = useChatApi()

const currentUser = ref<User | null>(null)
const contacts = ref<User[]>([])
const selectedContact = ref<User | null>(null)

onMounted(async () => {
    currentUser.value = await getCurrentUser()
    if (currentUser.value?.role === 'user') {
        contacts.value = (await getAdmins()).map(user => ({
            ...user,
            name: user.role === 'admin' && user.bank ? user.bank.name : user.name,
            profile_picture: user.profile_picture
        }))
    } else if (currentUser.value?.role === 'master_admin' || currentUser.value?.role === 'bank_admin') {
        const chats = await getChats() as Chat[]
        const uniqueUsers = new Map<number, User>()
        chats.forEach(chat => {
            if (chat.sender_id !== currentUser.value?.id) {
                uniqueUsers.set(chat.sender_id, {
                    ...chat.sender,
                    profile_picture: chat.sender.profile_picture
                })
            }
            if (chat.receiver_id !== currentUser.value?.id) {
                uniqueUsers.set(chat.receiver_id, {
                    ...chat.receiver,
                    profile_picture: chat.receiver.profile_picture
                })
            }
        })
        contacts.value = Array.from(uniqueUsers.values())
    }
})

const chatHeader = {
    fontSize: theme.fonts.size.subheading,
    fontWeight: theme.fonts.weight.bold,
    fontFamily: theme.fonts.family,
    paddingLeft: '20px',
    paddingBottom: '10px',
    display: 'flex',
    alignItems: 'center',
    gap: '10px',
}

const chatHeaderImg = {
    height: '32px',
}

const chatPageWrapper = {
    width: '100%',
    maxWidth: '1200px',
    margin: '0 auto',
    height: '80vh',
}

const chatPage = {
    display: 'flex',
    minHeight: '80vh',
}
</script>
