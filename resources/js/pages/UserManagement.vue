<!--<template>-->
<!--    <div class="p-6">-->
<!--        <h2 class="text-xl font-semibold mb-4">Manajemen User</h2>-->

<!--        <div class="mb-4 flex justify-between items-center">-->
<!--            <select v-model="sort" class="border rounded px-4 py-2">-->
<!--                <option value="name_asc">Nama (A-Z)</option>-->
<!--                <option value="name_desc">Nama (Z-A)</option>-->
<!--            </select>-->
<!--            <input v-model="search" placeholder="Cari nama pengguna" class="border rounded px-4 py-2 w-64" />-->
<!--        </div>-->

<!--        <div v-for="user in filteredUsers" :key="user.id" class="flex items-center justify-between border rounded px-4 py-3 mb-2 bg-white">-->
<!--            <div>-->
<!--                <p class="font-medium">{{ user.name }} – {{ user.email }}</p>-->
<!--            </div>-->
<!--            <div class="relative">-->
<!--                <button @click="toggleMenu(user)" class="bg-green-500 text-white px-4 py-1 rounded">-->
<!--                    Menu-->
<!--                </button>-->
<!--                <div v-if="selectedUser && selectedUser.id === user.id" class="absolute right-0 mt-2 w-64 bg-white shadow-lg border rounded z-10 p-4">-->
<!--                    <p><strong>Nama:</strong> {{ selectedUser.name }}</p>-->
<!--                    <p><strong>Email:</strong> {{ selectedUser.email }}</p>-->
<!--                    <p><strong>Alamat:</strong> {{ selectedUser.address || 'Belum tersedia' }}</p>-->
<!--                    <button @click="selectedUser = null" class="mt-2 text-red-500">Tutup</button>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</template>-->

<!--<script setup lang="ts">-->
<!--// import { ref, computed, onMounted } from 'vue'-->
<!--//-->
<!--// const users = ref([])-->
<!--// const selectedUser = ref(null)-->
<!--// const sort = ref('name_asc')-->
<!--// const search = ref('')-->
<!--//-->
<!--// const fetchUsers = async () => {-->
<!--//     const res = await fetch('/api/users')-->
<!--//     users.value = await res.json()-->
<!--// }-->
<!--//-->
<!--// const toggleMenu = (user: any) => {-->
<!--//     selectedUser.value = selectedUser.value?.id === user.id ? null : user-->
<!--// }-->
<!--//-->
<!--// const filteredUsers = computed(() => {-->
<!--//     let result = users.value.filter(u => u.name.toLowerCase().includes(search.value.toLowerCase()))-->
<!--//     if (sort.value === 'name_desc') {-->
<!--//         return result.sort((a, b) => b.name.localeCompare(a.name))-->
<!--//     }-->
<!--//     return result.sort((a, b) => a.name.localeCompare(b.name))-->
<!--// })-->
<!--//-->
<!--// onMounted(fetchUsers)-->
<!--</script>-->

<script setup>
import { ref, computed } from 'vue';
import { theme } from '@/config/theme';
import Navbar from "@/components/Navbar.vue";

const primaryColor = theme.colors.primary;

// State untuk filter & pencarian
const search = ref('');
const sort = ref('name_asc');

// Data dummy user
const users = ref([
    {
        id: 1,
        name: 'Rudy Tabootie',
        email: 'rudytabootie@gmail.com',
        address: 'Jl. Mawar No. 10, Malang',
        saldo: 25000,
        totalSampah: 15,
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
]);

// Filter dan sortir
const filteredUsers = computed(() => {
    let result = users.value.filter(user =>
        user.name.toLowerCase().includes(search.value.toLowerCase())
    );

    if (sort.value === 'name_asc') {
        result.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sort.value === 'name_desc') {
        result.sort((a, b) => b.name.localeCompare(a.name));
    }

    return result;
});

const selectedUser = ref(null);

const openUserPopup = (user) => {
    selectedUser.value = user
}

const closePopup = () => {
    selectedUser.value = null
}
</script>

<template>
    <Navbar />
    <!-- User list -->
    <div class="min-h-screen bg-gray-100 flex items-center justify-center px-4">
        <div class="w-[1440px] h-[1024px] bg-white rounded-lg shadow p-6 overflow-y-auto">
            <h2 class="text-2xl font-bold mb-4">Manajemen User</h2>

            <!-- Filter & search -->
            <div class="mb-4 flex justify-between items-center">
                <select v-model="sort" class="border rounded px-4 py-2">
                    <option value="name_asc">Nama (A-Z)</option>
                    <option value="name_desc">Nama (Z-A)</option>
                </select>
                <input
                    v-model="search"
                    placeholder="Cari nama pengguna"
                    class="border rounded px-4 py-2 w-64"
                />
            </div>

            <div v-for="user in filteredUsers" :key="user.id" class="bg-white p-4 shadow rounded-lg flex justify-between items-center mb-4">
                <div>
                    <p class="font-semibold">{{ user.name }}</p>
                    <p class="text-sm text-gray-500">{{ user.email }}</p>
                </div>
                <button @click="openUserPopup(user)" class="btn_menu text-white px-4 py-1 rounded">Menu</button>
            </div>
        </div>
    </div>

    <!-- Popup -->
    <div v-if="selectedUser" class="fixed inset-0 z-50 flex items-center justify-center">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="closePopup"></div>

        <!-- Popup card -->
        <div class="relative z-50 bg-white rounded-xl p-6 w-[90%] max-w-lg shadow-lg">
            <button @click="closePopup" class="absolute top-3 right-3 text-black">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="flex items-start gap-4 mb-4">
                <div>
                    <div class="w-10 h-10 rounded-full border-2 border-gray-400 flex items-center justify-center text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <circle cx="12" cy="10" r="3" />
                            <path d="M7 18.5c.9-2.3 2.5-3.5 5-3.5s4.1 1.2 5 3.5" />
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
</template>

<style scoped>
.btn_menu {
    background-color: v-bind(primaryColor);
}
</style>/
