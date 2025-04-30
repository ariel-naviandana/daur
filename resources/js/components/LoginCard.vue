<template>
    <div :style="containerStyle">
      <div :style="cardStyle">
        <img src="../../../public/images/logo-daur.png" alt="DAUR Logo" :style="logoStyle" />
        <h2 :style="titleStyle">Selamat Datang</h2>
        <p :style="subtitleStyle">masuk akun Anda</p>
  
        <!-- Form Login -->
        <form :style="formStyle" @submit.prevent="handleLogin">
            <label>Email</label>
            <input type="email" v-model="email" placeholder="Masukkan email" />
            <label>Kata sandi</label>
            <input type="password" v-model="password" placeholder="Masukkan kata sandi" />
            
            <button type="submit">Masuk</button>
        </form>
  
        <p :style="footerStyle">Belum memiliki akun? <a href="/register" style="color: #4CAF50">Daftar</a></p>
      </div>
    </div>
  </template>
  
  <script lang="ts" setup>
  
  import { ref } from 'vue'

    const email = ref('')
    const password = ref('')

    function handleLogin() {
    const storedUser = localStorage.getItem('user')
    if (!storedUser) {
        alert('Pengguna belum terdaftar!')
        return
    }

    const user = JSON.parse(storedUser)

    if (user.email === email.value && user.password === password.value) {
        alert('Login berhasil!')
        window.location.href = '/' 
    } else {
        alert('Email atau kata sandi salah!')
    }
}

  const containerStyle = {
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    height: '100vh',
    backgroundColor: '#f9f9f9',
    fontFamily: 'sans-serif',
  }
  
  const cardStyle = {
    width: '680px',
    backgroundColor: '#fff',
    borderRadius: '24px',
    boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)',
    padding: '48px 40px',
    boxSizing: 'border-box',
    textAlign: 'center',
    maxWidth: '90%',
    margin: '0 auto',
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
  