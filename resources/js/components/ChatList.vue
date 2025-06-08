<template>
    <div :style="chatListStyle">
        <div :style="searchInputWrapperStyle">
            <img src="/images/ic_search.svg" alt="Search" :style="searchIconStyle" />
            <input
                v-model="searchQuery"
                placeholder="Cari nama kontak"
                :style="searchInputStyle"
            />
        </div>
        <div :style="bankListStyle">
            <div
                v-for="contact in filteredContacts"
                :key="contact.id"
                @click="selectContact(contact)"
                :style="[bankItemStyle, selectedContact?.id === contact.id ? activeBankStyle : {}]"
            >
                <img v-if="contact.profile_picture" :src="contact.profile_picture" alt="avatar" :style="avatarStyle" />
                <img v-else src="/images/profile-icon.svg" alt="avatar" :style="avatarStyle" />
                <span>{{ contact.name }}</span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { theme } from '@/helpers/theme'
import { User } from '@/interfaces/User'

const props = defineProps<{
    contacts: User[]
    selectedContact: User | null
}>()

const emits = defineEmits<{
    (e: 'select-contact', contact: User): void
}>()

const searchQuery = ref('')

const filteredContacts = computed(() => {
    return props.contacts.filter(contact =>
        contact.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
})

function selectContact(contact: User) {
    emits('select-contact', contact)
}

const chatListStyle = {
    width: '350px',
    border: '1px solid #ccc',
    padding: '1rem',
}

const searchInputWrapperStyle = {
    position: 'relative',
    width: '380px',
    height: '40px',
    marginBottom: '1rem',
}

const searchIconStyle = {
    position: 'absolute',
    top: '50%',
    left: '16px',
    transform: 'translateY(-50%)',
    width: '20px',
    height: '20px',
    pointerEvents: 'none',
}

const searchInputStyle = {
    width: '300px',
    height: '40px',
    paddingLeft: '40px',
    paddingBottom: '4px',
    border: '1px solid #888888',
    borderRadius: '30px',
    fontSize: '16px',
    outline: 'none',
}

const bankListStyle = {
    marginTop: '1rem',
}

const bankItemStyle = {
    display: 'flex',
    alignItems: 'center',
    padding: '0.5rem',
    cursor: 'pointer',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
}

const activeBankStyle = {
    backgroundColor: '#e0e0e0',
    borderRadius: '10px',
}

const avatarStyle = {
    width: '32px',
    height: '32px',
    borderRadius: '50%',
    marginRight: '1rem',
}
</script>
