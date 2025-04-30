<template>
    <div :style="containerStyle">
      <div :style="cardStyle">
        <img src="../../../public/images/logo-daur.png" alt="DAUR Logo" :style="logoStyle" />
        <h2 :style="titleStyle">Daftar</h2>
        <p :style="subtitleStyle">buat akun Anda</p>
  
        <!-- Form Register -->
        <form :style="formStyle">
          <label>Nama</label>
          <input type="text" placeholder="Masukkan nama" />
          <label>Email</label>
          <input type="email" placeholder="Masukkan email" />
          <label>Kata sandi</label>
          <input type="password" placeholder="Masukkan kata sandi" />
          <label>Konfirmasi kata sandi</label>
          <input type="password" placeholder="Masukkan ulang kata sandi" />
  
          <button type="submit">Daftar</button>
        </form>
  
        <p :style="footerStyle">Sudah memiliki akun? <a href="#" style="color: #4CAF50">Masuk</a></p>
      </div>
    </div>
  </template>
  
  <script lang="ts" setup>
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
    margin-top: 8px;
  }
  
  button:hover {
    background-color: #43a047;
  }
  </style>
  