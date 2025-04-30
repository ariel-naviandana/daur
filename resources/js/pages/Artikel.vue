<template>
    <div :style="layoutStyle">
      <Navbar />
      <div :style="contentStyle" class="p-6">
        <template v-if="!selectedArtikel">
          <h1 :style="headingStyle">Artikel</h1>
          <hr :style="dividerStyle" />
          <h3 :style="sectionTitle">Artikel terbaru</h3>
  
          <div :style="cardWrapperStyle">
            <div
              v-for="(artikel, index) in artikelList"
              :key="index"
              :style="cardContainerStyle"
              @click="selectArtikel(artikel)"
              style="cursor: pointer"
            >
              <ArtikelCard
                :title="artikel.title"
                :description="artikel.content.slice(0, 2).join(' ')"
                :date="artikel.date"
                :image="artikel.image"
              />
            </div>
          </div>
        </template>
  
        <template v-else>
          <div style="max-width: 800px; margin: 0 auto">
            <button @click="selectedArtikel = null" style="margin-bottom: 20px">Kembali</button>
            <h1 :style="headingStyle">{{ selectedArtikel.title }}</h1>
            <p :style="dateStyle">{{ selectedArtikel.date }}</p>
            <img :src="selectedArtikel.image" :style="detailImageStyle" />
            <div v-for="(paragraph, index) in selectedArtikel.content" :key="index" :style="paragraphStyle">
              {{ paragraph }}
            </div>
            <div :style="infoStyle">
              <p>Pewarta: {{ selectedArtikel.pewarta }}</p>
              <p>Editor: {{ selectedArtikel.editor }}</p>
              <p>Copyright: {{ selectedArtikel.copyright }}</p>
              <p>Sumber: {{ selectedArtikel.sumber }}</p>
            </div>
          </div>
        </template>
      </div>
    </div>
  </template>
  
  <script lang="ts" setup>
  import Navbar from '../components/Navbar.vue'
  import ArtikelCard from '../components/ArtikelCard.vue'
  import { ref } from 'vue'
  import { theme } from '@/config/theme'
  
  const selectedArtikel = ref(null)
  
  function selectArtikel(artikel: any) {
    selectedArtikel.value = artikel
  }
  
  // Styles
  const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family,
  }
  const contentStyle = { color: theme.colors.darkGrey }
  const headingStyle = {
    fontSize: theme.fonts.size.large,
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
    width: '100%',
    maxWidth: '900px',
    margin: '0 auto',
    }

  const cardContainerStyle = {
    width:'100%',
    paddingLeft: '16px',
    paddingRight: '16px',
    boxSizing: 'border-box',
  }
  
  const detailImageStyle = {
    width: '100%',
    maxHeight: '400px',
    objectFit: 'cover',
    borderRadius: '12px',
    marginBottom: '16px',
  }
  
  const paragraphStyle = {
    marginBottom: '16px',
    lineHeight: '1.6',
  }
  
  const dateStyle = {
    color: theme.colors.green,
    fontSize: theme.fonts.size.small,
    marginBottom: '30px',
    marginTop: '30px', 
    textAlign: 'center',
  }
  
  const infoStyle = {
    marginTop: '32px',
    color: theme.colors.lightGrey,
    fontSize: theme.fonts.size.small,
    borderTop: '1px solid #ccc',
    paddingTop: '12px',
    lineHeight: '1.5',
  }
  
  // Dummy artikel list (dengan konten lengkap)
  const artikelList = [
    {
      title: 'DLH DKI siap bangun tujuh TPS 3R tahun ini',
      date: '21 Maret 2025',
      image: '/images/image.png',
      content: [
        'Jakarta (ANTARA) - Dinas Lingkungan Hidup DKI Jakarta siap membangun tujuh fasilitas pengolahan sampah berkonsep kurangi, pakai kembali, dan daur ulang atau "reduce", "reuse" dan "recycle" (TPS 3R) sepanjang tahun 2025 ini di dua wilayah di Jakarta.',
        'Kepala Dinas Lingkungan Hidup (DLH) DKI Jakarta Asep Kuswanto di Jakarta, Jumat, menyebutkan, dari tujuh TPS 3R ini, tiga di antaranya berada di Jakarta Barat yaitu di Tanah Sareal (Tambora), Kalideres dan Duri Kosambi RW 02 Cengkareng.',
        'Sementara empat lainnya berada di Jakarta Selatan, yakni satu di Menteng Atas (Setiabudi), Waduk Brigif (Jagakarsa), Kramat Pela, (Kebayoran Baru) dan Kemang Utara 9 (Mampang Prapatan).',
        '"Kami harapkan mulai proses lelangnya bisa di Maret dan kalau lancar, pembangunan enam bulan, mudah-mudahan di bulan ke-10 atau akhir tahun ini bisa terbangun semua," ujar Asep.',
        'Asep mengatakan, sejak tahun 2022 hingga saat ini, Pemerintah Provinsi (Pemprov) DKI Jakarta telah membangun 17 TPS 3R yang tersebar di wilayah administrasi Jakarta.',
        'Empat fasilitas terbaru yang diresmikan, yakni TPS 3R Sunter dan TPS 3R Semper di Jakarta Utara, lalu TPS 3R Bambu Larangan di Jakarta Barat dan TPS 3R Rawa Terate di Jakarta Timur.',
        'Pemprov DKI Jakarta menargetkan dapat membangun TPS 3R di 44 kecamatan untuk mengurangi volume sampah. Ini lantaran dengan semakin bertambahnya populasi penduduk, sampah yang dihasilkan juga terus bertambah.',
        'Karena itu, penerapan 3R di TPS (TPS 3R) dikatakan menjadi salah satu upaya mengelola sampah dari sumbernya. Upaya ini dikatakan akan mengurangi beban Tempat Pengolahan Sampah Terpadu (TPST) Bantar Gebang.',
        'TPS 3R merupakan fasilitas pengolahan sampah menjadi bahan bakar serupa Refuse Derived Fuel (RDF) Plant. Satu TPS 3R dapat mengolah sampah 25 hingga 50 ton per hari.',
      ],
      pewarta: 'Andi Wijaya',
      editor: 'Rina Putri',
      copyright: '© 2025 DLH DKI Jakarta',
      sumber: 'dlh.jakarta.go.id',
    },

    {
      title: 'DLH DKI siap bangun tujuh TPS 3R tahun ini',
      date: '21 Maret 2025',
      image: '/images/image.png',
      content: [
        'Jakarta (ANTARA) - Dinas Lingkungan Hidup DKI Jakarta siap membangun tujuh fasilitas pengolahan sampah berkonsep kurangi, pakai kembali, dan daur ulang atau "reduce", "reuse" dan "recycle" (TPS 3R) sepanjang tahun 2025 ini di dua wilayah di Jakarta.',
        'Kepala Dinas Lingkungan Hidup (DLH) DKI Jakarta Asep Kuswanto di Jakarta, Jumat, menyebutkan, dari tujuh TPS 3R ini, tiga di antaranya berada di Jakarta Barat yaitu di Tanah Sareal (Tambora), Kalideres dan Duri Kosambi RW 02 Cengkareng.',
        'Sementara empat lainnya berada di Jakarta Selatan, yakni satu di Menteng Atas (Setiabudi), Waduk Brigif (Jagakarsa), Kramat Pela, (Kebayoran Baru) dan Kemang Utara 9 (Mampang Prapatan).',
        '"Kami harapkan mulai proses lelangnya bisa di Maret dan kalau lancar, pembangunan enam bulan, mudah-mudahan di bulan ke-10 atau akhir tahun ini bisa terbangun semua," ujar Asep.',
        'Asep mengatakan, sejak tahun 2022 hingga saat ini, Pemerintah Provinsi (Pemprov) DKI Jakarta telah membangun 17 TPS 3R yang tersebar di wilayah administrasi Jakarta.',
        'Empat fasilitas terbaru yang diresmikan, yakni TPS 3R Sunter dan TPS 3R Semper di Jakarta Utara, lalu TPS 3R Bambu Larangan di Jakarta Barat dan TPS 3R Rawa Terate di Jakarta Timur.',
        'Pemprov DKI Jakarta menargetkan dapat membangun TPS 3R di 44 kecamatan untuk mengurangi volume sampah. Ini lantaran dengan semakin bertambahnya populasi penduduk, sampah yang dihasilkan juga terus bertambah.',
        'Karena itu, penerapan 3R di TPS (TPS 3R) dikatakan menjadi salah satu upaya mengelola sampah dari sumbernya. Upaya ini dikatakan akan mengurangi beban Tempat Pengolahan Sampah Terpadu (TPST) Bantar Gebang.',
        'TPS 3R merupakan fasilitas pengolahan sampah menjadi bahan bakar serupa Refuse Derived Fuel (RDF) Plant. Satu TPS 3R dapat mengolah sampah 25 hingga 50 ton per hari.',
      ],
      pewarta: 'Andi Wijaya',
      editor: 'Rina Putri',
      copyright: '© 2025 DLH DKI Jakarta',
      sumber: 'dlh.jakarta.go.id',
    },

    {
      title: 'DLH DKI siap bangun tujuh TPS 3R tahun ini',
      date: '21 Maret 2025',
      image: '/images/image.png',
      content: [
        'Jakarta (ANTARA) - Dinas Lingkungan Hidup DKI Jakarta siap membangun tujuh fasilitas pengolahan sampah berkonsep kurangi, pakai kembali, dan daur ulang atau "reduce", "reuse" dan "recycle" (TPS 3R) sepanjang tahun 2025 ini di dua wilayah di Jakarta.',
        'Kepala Dinas Lingkungan Hidup (DLH) DKI Jakarta Asep Kuswanto di Jakarta, Jumat, menyebutkan, dari tujuh TPS 3R ini, tiga di antaranya berada di Jakarta Barat yaitu di Tanah Sareal (Tambora), Kalideres dan Duri Kosambi RW 02 Cengkareng.',
        'Sementara empat lainnya berada di Jakarta Selatan, yakni satu di Menteng Atas (Setiabudi), Waduk Brigif (Jagakarsa), Kramat Pela, (Kebayoran Baru) dan Kemang Utara 9 (Mampang Prapatan).',
        '"Kami harapkan mulai proses lelangnya bisa di Maret dan kalau lancar, pembangunan enam bulan, mudah-mudahan di bulan ke-10 atau akhir tahun ini bisa terbangun semua," ujar Asep.',
        'Asep mengatakan, sejak tahun 2022 hingga saat ini, Pemerintah Provinsi (Pemprov) DKI Jakarta telah membangun 17 TPS 3R yang tersebar di wilayah administrasi Jakarta.',
        'Empat fasilitas terbaru yang diresmikan, yakni TPS 3R Sunter dan TPS 3R Semper di Jakarta Utara, lalu TPS 3R Bambu Larangan di Jakarta Barat dan TPS 3R Rawa Terate di Jakarta Timur.',
        'Pemprov DKI Jakarta menargetkan dapat membangun TPS 3R di 44 kecamatan untuk mengurangi volume sampah. Ini lantaran dengan semakin bertambahnya populasi penduduk, sampah yang dihasilkan juga terus bertambah.',
        'Karena itu, penerapan 3R di TPS (TPS 3R) dikatakan menjadi salah satu upaya mengelola sampah dari sumbernya. Upaya ini dikatakan akan mengurangi beban Tempat Pengolahan Sampah Terpadu (TPST) Bantar Gebang.',
        'TPS 3R merupakan fasilitas pengolahan sampah menjadi bahan bakar serupa Refuse Derived Fuel (RDF) Plant. Satu TPS 3R dapat mengolah sampah 25 hingga 50 ton per hari.',
      ],
      pewarta: 'Andi Wijaya',
      editor: 'Rina Putri',
      copyright: '© 2025 DLH DKI Jakarta',
      sumber: 'dlh.jakarta.go.id',
    },
    
  ]
  </script>
  