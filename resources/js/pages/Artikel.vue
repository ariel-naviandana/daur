<template>
    <div :style="layoutStyle">
        <Navbar />
        <div :style="contentStyle" class="p-6">
            <h1 :style="headingStyle">Artikel</h1>

            <hr :style="dividerStyle" />
            <h3 :style="sectionTitle">Artikel terbaru</h3>

            <div :style="cardWrapperStyle">
            <div
                v-for="(artikel, index) in artikelList"
                :key="index"
                :style="cardContainerStyle"
            >
                <ArtikelCard
                    :title="artikel.title"
                    :description="artikel.description"
                    :date="artikel.date"
                    :image="artikel.image"
                />
            </div>
        </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import Navbar from '../components/Navbar.vue'
import ArtikelCard from '../components/ArtikelCard.vue'
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
    textAlign: 'center',
}

const sectionTitle = { fontSize: '20px', marginBottom: '15px', textAlign: 'center' }
const dividerStyle = { margin: '20px 0', borderTop: '1px solid #ddd' }

const cardWrapperStyle = {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    gap: '24px',
}

const cardContainerStyle = {
    width: '100%',
    display: 'flex',
    justifyContent: 'center',
    paddingLeft: '60px',
    paddingRight: '60px',
    boxSizing: 'border-box',
}

const artikelList = [
    {
        title: 'DLH DKI siap bangun tujuh TPS 3R tahun ini',
        description: 'Dinas Lingkungan Hidup DKI Jakarta siap membangun tujuh fasilitas pengolahan sampah berkonsep kurangi, pakai kembali, dan daur ulang atau "reduce", ...',
        date: '21 Maret 2025',
        image: '/images/image.png',
    },
    {
        title: 'DLH DKI siap bangun tujuh TPS 3R tahun ini',
        description: 'Dinas Lingkungan Hidup DKI Jakarta siap membangun tujuh fasilitas pengolahan sampah berkonsep kurangi, pakai kembali, dan daur ulang atau "reduce", ...',
        date: '21 Maret 2025',
        image: '/images/image.png',
    },
    {
        title: 'DLH DKI siap bangun tujuh TPS 3R tahun ini',
        description: 'Dinas Lingkungan Hidup DKI Jakarta siap membangun tujuh fasilitas pengolahan sampah berkonsep kurangi, pakai kembali, dan daur ulang atau "reduce", ...',
        date: '21 Maret 2025',
        image: '/images/image.png',
    },
]
</script>

<style scoped>
</style>
