<template>
    <div :style="chatListStyle">
        <div :style="searchInputWrapperStyle">
            <img src="/public/images/ic_search.svg" alt="Search" :style="searchIconStyle" />
            <input
                v-model="searchQuery"
                placeholder="Cari nama bank sampah"
                :style="searchInputStyle"
            />
        </div>

        <div :style="bankListStyle">
            <div
                v-for="bank in filteredBanks"
                :key="bank.id"
                @click="selectBank(bank)"
                :style="[bankItemStyle, selectedBank?.id === bank.id ? activeBankStyle : {}]"
            >
                <img :src="bank.avatar" alt="avatar" :style="avatarStyle" />
                <span>{{ bank.name }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import {theme} from "@/config/theme.js";

const props = defineProps({
    banks: Array,
    selectedBank: Object,
})
const emits = defineEmits(['select-bank'])

const searchQuery = ref('')

const filteredBanks = computed(() => {
    return props.banks.filter(bank =>
        bank.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
})

function selectBank(bank) {
    emits('select-bank', bank)
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
    left: '20px',
    transform: 'translateY(-50%)',
    width: '20px',
    height: '20px',
    pointerEvents: 'none',
}

const searchInputStyle = {
    width: '300px',
    height: '40px',
    paddingLeft: '40px',
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

<style scoped>

</style>
