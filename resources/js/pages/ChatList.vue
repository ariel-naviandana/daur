<template>
    <div class="chat-list">
        <div class="search-input-wrapper">
            <img src="/public/images/ic_search.svg" alt="Search" class="search-icon" />
            <input
                v-model="searchQuery"
                placeholder="Cari nama bank sampah"
                class="search-input"
            />
        </div>

        <div class="bank-list">
            <div
                v-for="bank in filteredBanks"
                :key="bank.id"
                @click="selectBank(bank)"
                :class="['bank-item', { active: selectedBank?.id === bank.id }]"
            >
                <img :src="bank.avatar" alt="avatar" class="avatar" />
                <span>{{ bank.name }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

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
</script>

<style scoped>
/* List Chat */
.chat-list {
    width: 350px;
    border: 1px solid #ccc;
    padding: 1rem;
}

/* Search Bar */
.search-input-wrapper {
    position: relative;
    width: 380px;
    height: 40px;
    margin-bottom: 1rem;
}
.search-icon {
    position: absolute;
    top: 50%;
    left: 20px;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    pointer-events: none;
}
.search-input {
    width: 300px;
    height: 40px;
    padding-left: 40px;
    border: 1px solid #888888;
    border-radius: 30px;
    font-size: 16px;
    outline: none;
}

/* List Bank */
.bank-list {
    margin-top: 1rem;
}
.bank-item {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
}
.bank-item.active {
    background-color: #e0e0e0;
    border-radius: 10px;
}
.avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 1rem;
}
</style>
