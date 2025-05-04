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
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    <path d="M9 12h6"></path>
                    <path d="M9 16h6"></path>
                    <path d="M12 8L16 12"></path>
                </svg>
                <h1 :style="headingStyle">Manajemen User</h1>
            </div>

            <div class="filters">
                <div :style="leftFilterStyle">
                    <select v-model="sort" :style="selectStyle">
                        <option value="name_asc">Nama (A-Z)</option>
                        <option value="name_desc">Nama (Z-A)</option>
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
                        v-model="search"
                        placeholder="Cari nama pengguna"
                        :style="searchInputStyle"
                    />
                </div>
            </div>

            <div v-if="filteredUsers.length === 0" :style="noResultsStyle">
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

            <div v-else>
                <div
                    v-for="user in filteredUsers"
                    :key="user.id"
                    class="bg-white p-4 shadow rounded-2xl flex justify-between items-center mb-4"
                >
                    <!-- Avatar -->
                    <div class="w-10 h-10 rounded-full overflow-hidden border-none">
                        <!-- Cek apakah ada profileImage, jika ada tampilkan gambar profil -->
                        <img v-if="user.profileImage" :src="user.profileImage" alt="Profile" class="w-full h-full object-cover" />
                        <!-- Jika tidak ada, tampilkan avatar default -->
                        <svg v-else xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" :style="iconStyle">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="10" r="3"></circle>
                            <path d="M7 18.5c.9-2.3 2.5-3.5 5-3.5s4.1 1.2 5 3.5"></path>
                        </svg>
                    </div>

                    <div class="flex-grow ml-4">
                        <p class="font-semibold">{{ user.name }}</p>
                        <p class="text-sm text-gray-500">{{ user.email }}</p>
                    </div>

                    <!-- Dropdown Menu -->
                    <div class="relative" :ref="el => dropdownRefs[user.id] = el">
                        <button @click="toggleDropdown(user.id)" class="btn_menu" :style="btn_menu">
                            Menu
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div v-if="dropdownOpenId === user.id" class="absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg z-10">
                            <ul class="text-sm text-gray-700">
                                <li><button @click="handleAction('lihat', user)" :style="btn_dropdown" class="hover:bg-gray-100">Lihat Detail</button></li>
                                <li><button @click="handleAction('edit', user)" :style="btn_dropdown" class="hover:bg-gray-100">Edit</button></li>
                                <li><button @click="handleAction('hapus', user)" :style="btn_dropdown" class="hover:bg-red-100 text-red-600">Hapus</button></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popup user detail -->
        <div v-if="selectedUser" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="closePopup"></div>
            <div class="relative z-50 bg-white rounded-xl p-6 w-[90%] max-w-lg shadow-lg">
                <button @click="closePopup" class="absolute top-3 right-3 text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="flex items-start gap-4 mb-4">
                    <div>
                        <div class="w-10 h-10 rounded-full overflow-hidden border-none">
                            <img v-if="selectedUser.profileImage" :src="selectedUser.profileImage" alt="Profile" class="w-full h-full object-cover" />
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" :style="iconStyle">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle cx="12" cy="10" r="3"></circle>
                                <path d="M7 18.5c.9-2.3 2.5-3.5 5-3.5s4.1 1.2 5 3.5"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">{{ selectedUser.name }}</h3>
                        <p class="text-gray-500">{{ selectedUser.email }}</p>
                        <p class="mt-2 text-sm"><span class="font-semibold">Alamat</span><br>{{ selectedUser.address }}</p>
                    </div>
                </div>

                <div class="mt-4 bg-gray-50 p-4 rounded-xl flex justify-between items-center text-center">
                    <div>
                        <p class="font-semibold">Saldo DAUR</p>
                        <p class="text-green-600 font-bold">
                            Rp. {{ selectedUser.saldo ? selectedUser.saldo.toLocaleString('id-ID') : '0' }}
                        </p>
                    </div>
                    <div>
                        <p class="font-semibold">Total Sampah</p>
                        <p class="text-green-600 font-bold">
                            {{ selectedUser.totalSampah ? selectedUser.totalSampah : 0 }} Kg
                        </p>
                    </div>
                    <div>
                        <p class="font-semibold">Recycle</p>
                        <div class="text-green-600 flex justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="1 4 1 10 7 10" />
                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { theme } from '@/config/theme'
import Navbar from "@/components/Navbar.vue"
import { onMounted, onUnmounted } from 'vue'

const dropdownRefs = {}

const handleClickOutside = (event) => {
    const openDropdown = dropdownOpenId.value
    const dropdownEl = dropdownRefs[openDropdown]

    if (dropdownEl && !dropdownEl.contains(event.target)) {
        dropdownOpenId.value = null
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
})

// State untuk filter & pencarian
const search = ref('')
const sort = ref('name_asc')

// Data dummy user
const users = ref([
    {
        id: 1,
        name: 'Rudy Tabootie',
        email: 'rudytabootie@gmail.com',
        address: 'Jl. Mawar No. 10, Malang',
        saldo: 25000,
        totalSampah: 15,
        profileImage: 'https://i.pravatar.cc/100?img=3' // contoh avatar URL
    },
    {
        id: 2,
        name: 'Anna Zakaria',
        email: 'anna.zakaria@example.com',
        address: 'Jl. Soekarno-Hatta No. 21, Malang',
    },
    {
        id: 3,
        name: 'Budi Santoso',
        email: 'budi.santoso@example.com',
        address: 'Jl. Veteran No. 3, Malang',
    },
    {
        id: 4,
        name: 'Citra Lestari',
        email: 'citra.lestari@example.com',
        address: 'Jl. Gajayana No. 12, Malang',
    },
])

// Filter dan sortir
const filteredUsers = computed(() => {
    let result = users.value.filter(user =>
        user.name.toLowerCase().includes(search.value.toLowerCase())
    )

    if (sort.value === 'name_asc') {
        result.sort((a, b) => a.name.localeCompare(b.name))
    } else if (sort.value === 'name_desc') {
        result.sort((a, b) => b.name.localeCompare(a.name))
    }

    return result
})

const selectedUser = ref(null)

const openUserPopup = (user) => {
    selectedUser.value = user
}

const closePopup = () => {
    selectedUser.value = null
}

const dropdownOpenId = ref(null)

const toggleDropdown = (userId) => {
    dropdownOpenId.value = dropdownOpenId.value === userId ? null : userId
}

const handleAction = (action, user) => {
    if (action === 'lihat') {
        openUserPopup(user)
    } else if (action === 'edit') {
        alert(`Edit user: ${user.name}`)
    } else if (action === 'hapus') {
        const confirmed = confirm(`Hapus user ${user.name}?`)
        if (confirmed) {
            users.value = users.value.filter(u => u.id !== user.id)
        }
    }
    dropdownOpenId.value = null
}

const btn_menu = {
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    borderRadius: '8px',
    display: 'flex',
    alignItems: 'center',
    height: '32px',
    width: '120px',
    justifyContent: 'end',
    gap: '18px',
    paddingRight: '8px',
}

const btn_dropdown = {
    fontFamily: theme.fonts.family,
    fontWeight: theme.fonts.weight.regular,
    width: '100%',
    textAlign: 'left',
    padding: '0.5rem 1rem', // py-2 px-4
    transition: 'background-color 0.2s',
}

const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family,
    overflowX: 'hidden',
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
    size: theme.fonts.size.subheading,
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

const leftFilterStyle = {
    display: 'flex',
    gap: '12px',
}

const selectStyle = {
    padding: '8px 16px',
    paddingRight: '36px',
    fontSize: theme.fonts.size.base,
    borderRadius: '16px',
    border: `1px solid ${theme.colors.lightGrey}`,
    fontFamily: theme.fonts.family,
    appearance: 'none',
    backgroundImage: `url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e")`,
    backgroundRepeat: 'no-repeat',
    backgroundPosition: 'right 12px center',
    backgroundSize: '16px',
}

const searchWrapperStyle = {
    marginLeft: '5px',
    display: 'flex',
    alignItems: 'center',
    backgroundColor: theme.colors.whiteElement,
    border: `1px solid ${theme.colors.lightGrey}`,
    borderRadius: '100px',
    padding: '8px 16px',
    width: '300px',
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
    fontSize: theme.fonts.size.sm,
    color: theme.colors.grey,
    margin: 0,
}
</script>

<style scoped>
.filters {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

input:focus {
    outline: none;
}

[style*="flex-direction: column"] {
    display: flex;
}

::-webkit-scrollbar {
    display: none;
}
</style>
