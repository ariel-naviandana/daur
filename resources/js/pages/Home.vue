<template>
    <div :style="layoutStyle">
        <Navbar />
        <div class="home-page">
            <!-- Hero Section -->
            <section class="hero">
                <img src="/public/images/hero-image.png" alt="Hero Image" class="hero-image" />
            </section>

            <!-- Misi Section -->
            <section class="mission">
                <div class="mission-img">
                    <img src="/public/images/recycle-image.png" alt="Recycle" />
                </div>
                <div class="mission-text">
                    <h2>Meningkatkan Kesadaran, Mewujudkan Perubahan</h2>
                    <p>
                        Misi kami adalah meningkatkan kesadaran akan pentingnya daur ulang dan menyediakan solusi mudah bagi
                        masyarakat untuk mendaur ulang sampah dengan benar. Dengan teknologi dan inovasi, kami menghadirkan cara baru
                        dalam pengelolaan limbah.
                    </p>
                </div>
            </section>

            <!-- Cara Mengikuti Section -->
            <section class="title_center">
                <h2>Cara Mengikuti</h2>
                <div class="container">
                    <div class="card">
                        <div class="badge">1</div>
                        <div class="card-body">
                            <img src="/public/images/langkah1.svg" alt="Pilih dan Kemas" />
                            <div class="label">Pilah dan Kemas</div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="badge">2</div>
                        <div class="card-body">
                            <img src="/public/images/langkah2.svg" alt="Buat Transaksi" />
                            <div class="label">Buat Transaksi</div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="badge">3</div>
                        <div class="card-body">
                            <img src="/public/images/langkah3.svg" alt="Kirim Sampah" />
                            <div class="label">Kirim Sampah</div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="badge">4</div>
                        <div class="card-body">
                            <img src="/public/images/langkah4.svg" alt="Sampah Terkelola" />
                            <div class="label">Sampah Terkelola</div>
                        </div>
                    </div>
                </div>
                <a href="/recycle" class="send-button">Kirim Sampah</a>
            </section>

            <!-- Jenis Sampah Section -->
            <section class="title_center">
                <h2>Jenis Sampah</h2>
                <div class="trash-types-container">
                    <div class="small_card">
                        <img src="/public/images/ic_jenis_kertas.svg" alt="Kertas" />
                        <div class="label_jenis">Kertas</div>
                    </div>
                    <div class="small_card">
                        <img src="/public/images/ic_jenis_botol_plastik.svg" alt="Plastik" />
                        <div class="label_jenis">Plastik</div>
                    </div>
                    <div class="small_card">
                        <img src="/public/images/ic_jenis_botol_kaca.svg" alt="Kaca" />
                        <div class="label_jenis">Kaca</div>
                    </div>
                    <div class="small_card">
                        <img src="/public/images/ic_jenis_besi.svg" alt="Besi" />
                        <div class="label_jenis">Besi</div>
                    </div>
                    <div class="small_card">
                        <img src="/public/images/ic_jenis_aluminium.svg" alt="Aluminium" />
                        <div class="label_jenis">Aluminium</div>
                    </div>
                    <div class="small_card">
                        <img src="/public/images/ic_jenis_kardus.svg" alt="kardus" />
                        <div class="label_jenis">Kardus</div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script lang="ts" setup>
import Navbar from '../components/Navbar.vue'
import { theme } from '@/config/theme'

const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family,
}

const contentStyle = {
    color: theme.colors.darkGrey,
}

const headingStyle = {
    fontSize: theme.fonts.size.heading,
    fontWeight: theme.fonts.weight.bold,
}
</script>

<style scoped>
/* Div Utama */
.home-page {
    max-width: 1440px;
    margin: 0 auto;
}

/* Judul */
.title_center {
    text-align: center;
    padding: 40px;
}
.title_center h2 {
    margin-bottom: 40px;
    font-size: 40px;
    font-weight: bold;
    text-align: center;
}

/* Hero */
.hero img {
    width: 1440px;
    height: auto;
}

/* Mission */
.mission {
    display: flex;
    padding: 50px;
    align-items: center;
}
.mission-img img {
    width: 320vh;
    border-radius: 10px;
}
.mission-text {
    padding-left: 50px;
}
.mission-text h2 {
    margin-left: 40px;
    font-size: 48px;
    color: #222222;
    font-weight: bold;
}
.mission-text p {
    margin-top: 40px;
    margin-left: 40px;
    font-size: 32px;
    color: #222222;
}

/* Cara Mengikuti */
.card {
    position: relative;
    width: 261px;
    height: 261px;
    padding: 20px;
    border-radius: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    background: #fff;
    text-align: center;
    font-family: sans-serif;
    box-sizing: border-box;
}
.card-body {
    margin: 30px 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.badge {
    position: absolute;
    top: 0;
    left: 0;
    width: 60px;
    height: 60px;
    background: #4CAF50;
    color: white;
    border-top-left-radius: 30px;
    border-bottom-right-radius: 30px;
    font-size: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.label {
    font-size: 24px;
    font-weight: 500;
    color: #222222;
}
.send-button {
    margin-top: 40px;
    padding: 14px 80px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 30px;
    font-size: 24px;
    font-weight: bold;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}
.send-button:hover {
    background: #45a049;
    transition: background 0.3s ease;
}
.container {
    display: flex;
    justify-content: center;
    gap: 60px;
    flex-wrap: wrap;
}

/* Jenis Sampah */
.trash-types-container {
    display: flex;
    gap: 40px;
    justify-content: center;
    margin-bottom: 60px;
}
.trash-item {
    min-width: 100px;
    padding: 10px;
    border-radius: 10px;
    text-align: center;
}
.small_card {
    position: relative;
    width: 165px;
    height: 165px;
    padding: 20px;
    border-radius: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    background: #fff;
    text-align: center;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.small_card img {
    margin-top: 10px;
}
.label_jenis {
    font-size: 24px;
    font-weight: 500;
    color: #222222;
    margin-top: 16px;
}
</style>
