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
                    :style="headerIconStyle"
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
                    :style="cardList"
                >
                    <!-- Avatar -->
                    <div :style="avatar">
                        <!-- Cek apakah ada profileImage, jika ada tampilkan gambar profil -->
                        <img v-if="user.profileImage" :src="user.profileImage" alt="Profile" :style="userImage"/>
                        <!-- Jika tidak ada, tampilkan avatar default -->
                        <svg v-else xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" :style="iconStyle">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="10" r="3"></circle>
                            <path d="M7 18.5c.9-2.3 2.5-3.5 5-3.5s4.1 1.2 5 3.5"></path>
                        </svg>
                    </div>

                    <div :style="detailWrapper">
                        <p :style="nameStyle">{{ user.name }}</p>
                        <p :style="emailStyle">{{ user.email }}</p>
                    </div>

                    <!-- Dropdown Menu -->
                    <div class="relative" :ref="el => dropdownRefs[user.id] = el">
                        <button @click="toggleDropdown(user.id)" class="btn_menu" :style="btn_menu">
                            Menu
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div v-if="dropdownOpenId === user.id" :style="dropdownOpen">
                            <ul class="text-sm text-gray-700">
                                <li><button @click="handleAction('lihat', user)" :style="btn_dropdown" class="button_hover_grey">Lihat Detail</button></li>
                                <li><button @click="handleAction('edit', user)" :style="btn_dropdown" class="button_hover_grey">Edit</button></li>
                                <li><button @click="handleAction('hapus', user)" :style="btn_dropdown" class="button_hover_red">Hapus</button></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popup user detail -->
        <div v-if="selectedUser" :style="popupDetail">
            <div :style="popupOverlay" @click="closePopup"></div>
            <div :style="popupContainer">
                <button @click="closePopup" :style="btnClosePopup">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>

                <div :style="popupHeader">
                    <div>
                        <div :style="profileImageWrapper">
                            <img v-if="selectedUser.profileImage" :src="selectedUser.profileImage" alt="Profile" :style="profileImage" />
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" :style="iconStyle">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle cx="12" cy="10" r="3"></circle>
                                <path d="M7 18.5c.9-2.3 2.5-3.5 5-3.5s4.1 1.2 5 3.5"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 :style="nameUser">{{ selectedUser.name }}</h3>
                        <p :style="emailUser">{{ selectedUser.email }}</p>
                        <p :style="alamatUserHeading">Alamat</p>
                        <p :style="alamatUser">{{ selectedUser.address }}</p>
                    </div>
                </div>

                <div :style="containerAktivitas">
                    <div>
                        <p :style="labelAktivitas">Saldo DAUR</p>
                        <p :style="valueAktivitas">Rp. {{ selectedUser.saldo ? selectedUser.saldo.toLocaleString('id-ID') : '0' }}</p>
                    </div>
                    <div>
                        <p :style="labelAktivitas">Total Sampah</p>
                        <p :style="valueAktivitas">{{ selectedUser.totalSampah ?? 0 }} Kg</p>
                    </div>
                    <div>
                        <p :style="labelAktivitas">Recycle</p>
                        <div :style="iconAktivitas">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
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
import { theme } from '@/helpers/theme'
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

const headerIconStyle = {
    color: theme.colors.darkGrey,
}

const iconStyle = {
    color: theme.colors.darkGrey,
    width: '100%',
    height: 'auto',
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

const cardList = {
    backgroundColor: theme.colors.whiteElement,
    padding: '1rem',
    boxShadow: '0 1px 3px rgba(0, 0, 0, 0.1)',
    borderRadius: '16px',
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: '1rem',
}

const avatar = {
    width: '40px',
    height: '40px',
    borderRadius: '9999px',
    overflow: 'hidden',
    border: 'none',
}

const userImage = {
    width: '100%',
    height: '100%',
    objectFit: 'cover',
}

const detailWrapper = {
    flexGrow: 1,
    marginLeft: '1rem',
}

const nameStyle = {
    fontWeight: theme.fonts.weight.semibold,
    fontSize: theme.fonts.size.base,
    margin: 0,
}

const emailStyle = {
    fontSize: theme.fonts.size.small,
    color: theme.colors.greyText,
    margin: 0,
}

const dropdownOpen = {
    position: 'absolute',
    right: 0,
    marginTop: '0.5rem',
    width: '10rem',
    backgroundColor: '#fff',
    border: `1px solid ${theme.colors.lightGrey}`,
    borderRadius: '0.7rem',
    boxShadow: '0 2px 8px rgba(0, 0, 0, 0.1)',
    zIndex: 10,
}

const popupDetail = {
    position: 'fixed',
    inset: 0,
    zIndex: 50,
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
}

const popupContainer = {
    backgroundColor: theme.colors.whiteElement,
    padding: '32px',
    borderRadius: '30px',
    maxWidth: '700px',
    width: '90%',
    margin: 'auto',
    position: 'relative',
    boxShadow: '0 10px 40px rgba(0,0,0,0.2)',
}

const popupOverlay = {
    position: 'absolute',
    inset: 0,
    backgroundColor: 'rgba(17, 24, 39, 0.5)', // setara dengan bg-gray-900/50
    backdropFilter: 'blur(4px)',
};

const popupHeader = {
    display: 'flex',
    alignItems: 'flex-start',
    gap: '16px',
    padding: '20px',
}

const profileImageWrapper = {
    width: '64px',
    height: '64px',
    marginTop: '4px',
    borderRadius: '50%',
    overflow: 'hidden',
    flexShrink: 0,
}

const profileImage = {
    width: '100%',
    height: '100%',
    objectFit: 'cover',
};

const btnClosePopup = {
    position: 'absolute',
    top: '22px',
    right: '22px',
    color: '#000',
    background: 'none',
    border: 'none',
    cursor: 'pointer',
}

const nameUser = {
    fontSize: theme.fonts.size.heading,
    fontWeight: theme.fonts.weight.semibold,
    margin: 0,
}

const emailUser = {
    fontSize: theme.fonts.size.base,
    color: theme.colors.grey,
    margin: 0,
}

const alamatUserHeading = {
    fontWeight: theme.fonts.weight.semibold,
    marginTop: '10px',
}

const alamatUser = {
    fontWeight: theme.fonts.weight.regular,
}

const containerAktivitas = {
    marginTop: '10rem',
    width: '70%',
    backgroundColor: '#f9fafb',
    padding: '1rem',
    borderRadius: '12px',
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    textAlign: 'center',
    margin: 'auto',
    position: 'relative',
}

const noResultsTextStyle = {
    fontWeight: theme.fonts.weight.semibold,
    fontSize: theme.fonts.size.base,
    marginTop: '1rem',
}

const noResultsDescStyle = {
    fontSize: theme.fonts.size.small,
    color: theme.colors.greyText,
}

const labelAktivitas = {
    fontWeight: '600',
    marginBottom: '0.25rem',
};

const valueAktivitas = {
    color: theme.colors.primary,
    fontWeight: '700',
};

const iconAktivitas = {
    color: theme.colors.primary,
    display: 'flex',
    justifyContent: 'center',
};
</script>

<style scoped>
.filters {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.button_hover_grey:hover {
    border-radius: 0.7rem;
    background-color: #f3f4f6;
}

.button_hover_red:hover {
    border-radius: 0.7rem;
    background-color: #fee2e2;
}

.button_hover_red {
    color: #dc2626;
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
