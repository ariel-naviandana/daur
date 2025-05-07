<template>
    <div :style="containerStyle">
        <div :style="cardStyle">
            <img src="/public/images/logo-daur.png" alt="DAUR Logo" :style="logoStyle" />
            <h2 :style="titleStyle">Daftar</h2>
            <p :style="subtitleStyle">buat akun Anda</p>

            <!-- Form Register -->
            <form :style="formStyle" @submit.prevent="handleRegister">
                <label>Nama</label>
                <input type="text" placeholder="Masukkan nama" v-model="name" required />
                <label>Email</label>
                <input type="email" placeholder="Masukkan email" v-model="email" required />
                <label>Kata sandi</label>
                <input type="password" placeholder="Masukkan kata sandi" v-model="password" required />
                <label>Konfirmasi kata sandi</label>
                <input type="password" placeholder="Masukkan ulang kata sandi" v-model="confirmPassword" required />

                <button type="submit">Daftar</button>
            </form>

            <p :style="footerStyle">Sudah memiliki akun? <a href="/login" style="color: #4CAF50">Masuk</a></p>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { useAuthApi } from '@/composables/useAuthApi'
import { User } from '@/interfaces/User'

const { register } = useAuthApi()

const name = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')

const handleRegister = async () => {
    if (!name.value || !email.value || !password.value || !confirmPassword.value) {
        alert('Mohon lengkapi semua data.')
        return
    }

    if (password.value !== confirmPassword.value) {
        alert('Kata sandi tidak cocok.')
        return
    }

    const userData: Partial<User> = {
        name: name.value,
        email: email.value,
        password: password.value,
        role: 'user',
    }

    const user = await register(userData as User)
    if (user) {
        localStorage.setItem('user', JSON.stringify(user))
        alert('Pendaftaran berhasil!')
        window.location.href = '/login'
    } else {
        alert('Pendaftaran gagal. Silakan coba lagi.')
    }
}

const containerStyle = {
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    minHeight: '100vh',
    backgroundColor: '#f9f9f9',
    fontFamily: 'sans-serif',
    padding: '24px',
    overflowY: 'auto',
}

const cardStyle = {
    width: '620px',
    backgroundColor: '#fff',
    borderRadius: '16px',
    boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)',
    padding: '32px 24px',
    boxSizing: 'border-box',
    textAlign: 'center',
    maxWidth: '90%',
    marginTop: '40px',
    marginBottom: '40px',
}

const logoStyle = {
    height: '40px',
    marginBottom: '20px',
    display: 'block',
    marginLeft: 'auto',
    marginRight: 'auto',
}

const titleStyle = {
    fontSize: '28px',
    fontWeight: 'bold',
    marginBottom: '4px',
    color: '#222',
}

const subtitleStyle = {
    color: '#888',
    fontSize: '14px',
    marginBottom: '32px',
}

const formStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '16px',
    textAlign: 'left',
    fontWeight: 'bold',
}

const footerStyle = {
    marginTop: '32px',
    fontSize: '14px',
    color: '#999',
}
</script>

<style scoped>
input {
    padding: 12px 16px;
    border: none;
    border-radius: 24px;
    background-color: #f0f0f0;
    font-size: 14px;
}

button {
    padding: 14px;
    border: none;
    border-radius: 24px;
    background-color: #4CAF50;
    color: white;
    font-size: 16px;
    cursor: pointer;
    margin-top: 20px;
}

button:hover {
    background-color: #43a047;
}
</style>
