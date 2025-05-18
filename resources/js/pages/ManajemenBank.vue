<template>
    <div :style="layoutStyle">
        <Navbar />
        <div :style="contentStyle" class="p-6">
            <div :style="headingContainerStyle">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="28"
                    height="28"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    :style="iconStyle"
                >
                    <path d="M12 2L2 7h20z"></path>
                    <rect x="4" y="7" width="16" height="13"></rect>
                    <path d="M12 20v-9"></path>
                    <path d="M12 11L8 9"></path>
                    <path d="M12 11l4-2"></path>
                </svg>
                <h1 :style="headingStyle">Manajemen Bank Sampah</h1>
            </div>
            <div :style="headerWithFiltersStyle">
                <div class="filters" :style="filtersWrapperStyle">
                    <div :style="leftFilterStyle">
                        <select v-model="selectedSort" :style="selectStyle">
                            <option value="alphabet-asc">Nama (A-Z)</option>
                            <option value="alphabet-desc">Nama (Z-A)</option>
                        </select>
                    </div>
                    <div :style="searchWrapperStyle">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            :style="searchIconStyle"
                        >
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Cari bank sampah"
                            :style="searchInputStyle"
                        />
                    </div>
                </div>
                <button :style="addButtonStyle" @click="openCreatePopup">
                    Tambah
                </button>
            </div>

            <div v-if="filteredAndSortedBanks.length === 0" :style="noResultsStyle">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="48"
                    height="48"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <p :style="noResultsTextStyle">Tidak ada hasil yang ditemukan</p>
                <p :style="noResultsDescStyle">
                    Coba sesuaikan filter atau kata kunci pencarian Anda
                </p>
            </div>
            <div v-else :style="listStyle">
                <BankCard
                    v-for="bank in filteredAndSortedBanks"
                    :key="bank.id"
                    :bank="bank"
                    @edit="openEditPopup(bank)"
                    @delete="openDeletePopup(bank)"
                />
            </div>

            <PopupFormBank
                v-if="showFormPopup"
                :bank="selectedBank"
                @close="closeFormPopup"
                @saved="fetchBanks"
            />

            <PopupDelete
                v-if="showDeletePopup"
                :is-open="showDeletePopup"
                :item-name="selectedBank?.name || ''"
                @close="closeDeletePopup"
                @confirm="handleDelete"
            />
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted } from 'vue'
import Navbar from '@/components/Navbar.vue'
import BankCard from '@/components/BankCard.vue'
import PopupFormBank from '@/components/PopupFormBank.vue'
import PopupDelete from '@/components/PopupDelete.vue'
import { useBankApi } from '@/composables/useBankApi'
import { Bank } from '@/interfaces/Bank'
import { theme } from '@/helpers/theme'

const selectedBank = ref<Bank | null>(null)
const banks = ref<Bank[]>([])
const selectedSort = ref<string>('alphabet-asc')
const searchQuery = ref<string>('')
const showFormPopup = ref(false)
const showDeletePopup = ref(false)
const { getBanks, deleteBank } = useBankApi()

const fetchBanks = async () => {
    try {
        banks.value = await getBanks()
    } catch (error) {
        console.error('Error fetching banks:', error)
    }
}

onMounted(fetchBanks)

const filteredAndSortedBanks = computed(() => {
    let filtered = banks.value
    if (searchQuery.value) {
        filtered = filtered.filter(bank =>
            bank.name.toLowerCase().includes(searchQuery.value.toLowerCase())
        )
    }
    if (selectedSort.value === 'alphabet-asc') {
        filtered = filtered.sort((a, b) => a.name.localeCompare(b.name))
    } else if (selectedSort.value === 'alphabet-desc') {
        filtered = filtered.sort((a, b) => b.name.localeCompare(a.name))
    }
    return filtered
})

const openCreatePopup = () => {
    selectedBank.value = null
    showFormPopup.value = true
}

const openEditPopup = (bank: Bank) => {
    selectedBank.value = bank
    showFormPopup.value = true
}

const openDeletePopup = (bank: Bank) => {
    selectedBank.value = bank
    showDeletePopup.value = true
}

const closeFormPopup = () => {
    showFormPopup.value = false
}

const closeDeletePopup = () => {
    showDeletePopup.value = false
}

const handleDelete = async () => {
    try {
        if (selectedBank.value) {
            await deleteBank(selectedBank.value.id)
            banks.value = banks.value.filter(
                (b: Bank) => b.id !== selectedBank.value?.id
            )
        }
        closeDeletePopup()
    } catch (error) {
        console.error('Error deleting bank:', error)
    }
}

const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family,
}

const contentStyle = {
    maxWidth: '1200px',
    margin: '0 auto',
    color: theme.colors.darkGrey,
}

const headingContainerStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '12px',
    marginBottom: '20px',
}

const iconStyle = {
    color: theme.colors.darkGrey,
}

const headingStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    margin: 0,
}

const headerWithFiltersStyle = {
    display: 'flex',
    alignItems: 'center',
    marginBottom: '16px',
}

const addButtonStyle = {
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    padding: '8px 16px',
    borderRadius: '6px',
    fontSize: theme.fonts.size.base,
    cursor: 'pointer',
    border: 'none',
    marginLeft: 'auto',
}

const filtersWrapperStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '12px',
}

const leftFilterStyle = {
    display: 'flex',
    gap: '12px',
}

const searchWrapperStyle = {
    display: 'flex',
    alignItems: 'center',
    backgroundColor: theme.colors.whiteElement,
    border: `1px solid ${theme.colors.lightGrey}`,
    borderRadius: '100px',
    padding: '8px 16px',
    marginRight: '5px',
}

const searchIconStyle = {
    color: theme.colors.lightGrey,
    marginRight: '12px',
}

const searchInputStyle = {
    border: 'none',
    outline: 'none',
    width: '100%',
    fontSize: theme.fonts.size.base,
    backgroundColor: 'transparent',
    color: theme.colors.darkGrey,
    fontFamily: theme.fonts.family,
}

const selectStyle = {
    padding: '8px 12px',
    paddingRight: '36px',
    fontSize: theme.fonts.size.base,
    borderRadius: '6px',
    border: `1px solid ${theme.colors.lightGrey}`,
    fontFamily: theme.fonts.family,
    appearance: 'none',
    backgroundImage: `url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e")`,
    backgroundRepeat: 'no-repeat',
    backgroundPosition: 'right 8px center',
    backgroundSize: '16px',
}

const listStyle = {
    display: 'grid',
    gridTemplateColumns: 'repeat(auto-fill, minmax(200px, 1fr))',
    gap: '16px',
}

const noResultsStyle = {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    padding: '48px 0',
    textAlign: 'center',
}

const noResultsTextStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.darkGrey,
    margin: '0 0 8px 0',
}

const noResultsDescStyle = {
    fontSize: theme.fonts.size.base,
    color: theme.colors.grey,
    margin: 0,
}
</script>
