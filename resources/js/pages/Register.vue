<template>
    <div :style="layoutStyle">
        <div :style="containerStyle">
            <div :style="cardStyle">
                <img src="/images/logo-daur.png" alt="DAUR Logo" :style="logoStyle" />
                <h2 :style="titleStyle">Daftar</h2>
                <p :style="subtitleStyle">buat akun Anda</p>

                <form :style="formStyle" @submit.prevent="handleRegister">
                    <label>Nama</label>
                    <input type="text" placeholder="Masukkan nama" v-model="name" required />
                    <label>Email</label>
                    <input type="email" placeholder="Masukkan email" v-model="email" required />
                    <label>Kata sandi</label>
                    <input type="password" placeholder="Masukkan kata sandi" v-model="password" required />
                    <div v-if="password" :style="passwordCriteriaStyle">
                        <p :style="getCriteriaStyle(hasMinLength)">
                            <span :style="checklistIconStyle(hasMinLength)"> {{ hasMinLength ? '✓' : '✗' }} </span>
                            Minimal 8 karakter
                        </p>
                        <p :style="getCriteriaStyle(hasLowercase)">
                            <span :style="checklistIconStyle(hasLowercase)"> {{ hasLowercase ? '✓' : '✗' }} </span>
                            Huruf kecil (a-z)
                        </p>
                        <p :style="getCriteriaStyle(hasUppercase)">
                            <span :style="checklistIconStyle(hasUppercase)"> {{ hasUppercase ? '✓' : '✗' }} </span>
                            Huruf besar (A-Z)
                        </p>
                        <p :style="getCriteriaStyle(hasNumber)">
                            <span :style="checklistIconStyle(hasNumber)"> {{ hasNumber ? '✓' : '✗' }} </span>
                            Angka (0-9)
                        </p>
                        <p :style="getCriteriaStyle(hasSpecialChar)">
                            <span :style="checklistIconStyle(hasSpecialChar)"> {{ hasSpecialChar ? '✓' : '✗' }} </span>
                            Karakter khusus (!@#$%^&*)
                        </p>
                    </div>
                    <label>Konfirmasi kata sandi</label>
                    <input type="password" placeholder="Masukkan ulang kata sandi" v-model="confirmPassword" required />

                    <button type="submit">Daftar</button>
                </form>

                <p :style="footerStyle">
                    Sudah memiliki akun?
                    <a href="/login" style="color: #4CAF50">Masuk</a>
                </p>
            </div>
        </div>

        <PopupNotifikasi
            :isOpen="popup.isOpen"
            :title="popup.title"
            :message="popup.message"
            @close="popup.isOpen = false"
        />
    </div>
</template>

<script lang="ts" setup>
import { ref, reactive, computed } from 'vue'
import { useAuthApi } from '@/composables/useAuthApi'
import { User } from '@/interfaces/User'
import PopupNotifikasi from '@/components/PopupNotifikasi.vue'

const { register } = useAuthApi()

const name = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')

const popup = reactive({
    isOpen: false,
    title: '',
    message: ''
})

const hasMinLength = computed(() => password.value.length >= 8)
const hasLowercase = computed(() => /[a-z]/.test(password.value))
const hasUppercase = computed(() => /[A-Z]/.test(password.value))
const hasNumber = computed(() => /\d/.test(password.value))
const hasSpecialChar = computed(() => /[!@#$%^&*]/.test(password.value))

const isPasswordValid = computed(() =>
    hasMinLength.value &&
    hasLowercase.value &&
    hasUppercase.value &&
    hasNumber.value &&
    hasSpecialChar.value
)

const showPopup = (title: string, message: string) => {
    popup.title = title
    popup.message = message
    popup.isOpen = true
}

const getCriteriaStyle = (isValid: boolean) => ({
    color: isValid ? '#4CAF50' : '#FF0000',
    fontSize: '12px',
    margin: '4px 0',
    display: 'flex',
    alignItems: 'center'
})

const checklistIconStyle = (isValid: boolean) => ({
    display: 'inline-block',
    width: '16px',
    textAlign: 'center',
    marginRight: '8px',
    fontWeight: 'bold'
})

const passwordCriteriaStyle = {
    marginTop: '8px',
    textAlign: 'left'
}

const handleRegister = async () => {
    if (!name.value || !email.value || !password.value || !confirmPassword.value) {
        showPopup('Peringatan', 'Mohon lengkapi semua data.')
        return
    }

    if (!isPasswordValid.value) {
        showPopup('Peringatan', 'Kata sandi tidak memenuhi kriteria.')
        return
    }

    if (password.value !== confirmPassword.value) {
        showPopup('Peringatan', 'Kata sandi tidak cocok.')
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
        showPopup('Sukses', 'Pendaftaran berhasil!')
        const closeWatcher = watch(() => popup.isOpen, (val) => {
            if (!val) {
                closeWatcher()
                window.location.href = '/login'
            }
        })
    } else {
        showPopup('Gagal', 'Pendaftaran gagal. Silakan coba lagi.')
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

const layoutStyle = {
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    minHeight: '100vh',
    backgroundColor: '#F9F9F9',
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
