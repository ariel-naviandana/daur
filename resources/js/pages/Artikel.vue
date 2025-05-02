<template>
    <div :style="layoutStyle">
      <Navbar />
      <div :style="contentStyle" class="p-6">
        <template v-if="!selectedArtikel">
          <h1 :style="headingStyle">Artikel</h1>
          <input
            v-model="searchQuery"
            :style="searchBarStyle"
            placeholder="Cari judul artikel..."
          />
          <hr :style="dividerStyle" />  
          <div :style="cardWrapperStyle">
          <template v-if="filteredArtikelList.length > 0">
            <h3 :style="sectionTitle">Artikel terbaru</h3>
            <div
              v-for="(artikel, index) in filteredArtikelList"
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
          </template>
          <template v-else>
            <div :style="noResultsStyle">
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
                :style="noResultsIconStyle"
              >
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              <p :style="noResultsTextStyle">Tidak ada hasil yang ditemukan</p>
              <p :style="noResultsDescStyle">Coba sesuaikan kata kunci pencarian Anda</p>
            </div>
          </template>
        </div>    
      </template>
          <template v-else>
            <div style="max-width: 800px; margin: 0 auto">
              <button 
                @click="selectedArtikel = null" 
                :style="backButtonStyle"
                aria-label="Kembali"
              >
                <svg 
                  xmlns="http://www.w3.org/2000/svg" 
                  width="24" 
                  height="24" 
                  viewBox="0 0 24 24" 
                  fill="none" 
                  stroke="currentColor" 
                  stroke-width="2" 
                  stroke-linecap="round" 
                  stroke-linejoin="round"
                  style="margin-right: 8px"
                >
                  <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                <span>Kembali</span>
              </button>

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
    import { computed } from 'vue'
    
    const selectedArtikel = ref(null)
    const searchQuery = ref('')

    
    function selectArtikel(artikel: any) {
        selectedArtikel.value = artikel
    }
    
    const filteredArtikelList = computed(() =>
        artikelList.filter(artikel =>
            artikel.title.toLowerCase().includes(searchQuery.value.toLowerCase())
        )
    )

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
    const sectionTitle = { fontSize: '20px', marginBottom: '15px', textAlign: 'center', fontWeight: theme.fonts.weight.bold }
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

    const backButtonStyle = {
      display: 'flex',
      alignItems: 'center',
      gap: '8px',
      background: 'none',
      border: 'none',
      cursor: 'pointer',
      color: theme.colors.green,
      fontWeight: theme.fonts.weight.medium,
      fontSize: theme.fonts.size.small,
      marginBottom: '20px',
    }

    const noResultsStyle = {
      display: 'flex',
      flexDirection: 'column',
      alignItems: 'center',
      justifyContent: 'center',
      textAlign: 'center',
      color: theme.colors.lightGrey,
      padding: '40px 20px',
      maxWidth: '600px',
      margin: '0 auto',
    }
    const noResultsIconStyle = {
      marginBottom: '16px',
      color: '#ccc',
    }
    const noResultsTextStyle = {
      fontWeight: theme.fonts.weight.bold,
      fontSize: '18px',
      marginBottom: '8px',
    }
    const noResultsDescStyle = {
      fontSize: '14px',
    }

    const searchBarStyle = {
        display: 'block',
        width: '100%',
        maxWidth: '900px',
        margin: '24px auto 30px auto',
        padding: '12px 20px',
        borderRadius: '9999px', 
        border: '1px solid #ccc',
        outline: 'none',
        fontSize: theme.fonts.size.small,
        boxShadow: '0 1px 3px rgba(0,0,0,0.05)',
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
        marginTop: '15px', 
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
        title: 'Mengapa Daur Ulang Itu Penting? Peran Setiap Individu dalam Menjaga Bumi',
        date: '12 April 2025',
        image: '/images/artikel2.jpg',
        content: [
            'Malang - Daur ulang bukan hanya tentang mengelola sampah, tetapi juga tentang menjaga bumi untuk generasi mendatang. Dalam beberapa tahun terakhir, kesadaran masyarakat tentang pentingnya mendaur ulang sampah semakin meningkat. Namun, masih banyak yang belum sepenuhnya memahami manfaat dan urgensinya.',
            'Daur ulang dapat mengurangi beban Tempat Pembuangan Akhir (TPA), menghemat sumber daya alam, dan mengurangi emisi gas rumah kaca. "Sampah yang terbuang begitu saja dapat menjadi masalah besar bagi lingkungan kita. Dengan mendaur ulang, kita memberi sampah kesempatan kedua untuk digunakan kembali," kata Siti Fatimah, seorang aktivis lingkungan.',
            'Selain itu, mendaur ulang juga dapat mengurangi kebutuhan akan bahan baku baru, yang pada gilirannya mengurangi kerusakan alam akibat penambangan dan pembalakan.',
            'Aplikasi daur ulang yang semakin banyak bermunculan juga mempermudah masyarakat untuk terlibat dalam proses ini, dengan hanya memindai sampah yang ingin dibuang dan mengetahui di mana harus mengirimkannya untuk didaur ulang.',
        ],
        pewarta: 'Fahri Rizky',
        editor: 'Ariel',
        copyright: '© 2025 Yayasan Hijau Indonesia',
        sumber: 'Instagram.com',
        },

        {
        title: 'Aplikasi Daur Ulang Digital: Solusi Mengurangi Sampah Plastik di Perkotaan',
        date: '15 Januari 2025',
        image: '/images/Artikel3.png',
        content: [
            'Jakarta (ANTARA) - Aplikasi daur ulang digital kini menjadi solusi modern untuk mengatasi sampah plastik yang terus menumpuk di perkotaan. Melalui aplikasi ini, masyarakat dapat dengan mudah melaporkan dan menjadwalkan pengambilan sampah plastik untuk didaur ulang.',
            'Menurut riset dari Lembaga Pengelolaan Sampah Jakarta, sekitar 60% sampah plastik yang dihasilkan di ibu kota tidak terkelola dengan baik. Aplikasi ini bertujuan untuk meningkatkan kesadaran dan partisipasi masyarakat dalam mendaur ulang sampah.',
            '"Dengan menggunakan aplikasi, masyarakat dapat dengan mudah mengetahui titik-titik pengumpulan sampah daur ulang terdekat dan bahkan mendapatkan insentif berupa poin yang bisa ditukarkan dengan berbagai hadiah," ujar Andi Wijaya, CEO dari perusahaan pengembang aplikasi daur ulang ini.',
            'Aplikasi ini juga dilengkapi dengan fitur edukasi mengenai pentingnya daur ulang dan bagaimana cara memilah sampah dengan benar.',
        ],
        pewarta: 'Tari Hapsari',
        editor: 'Dewi Nurul',
        copyright: '© 2025 PT Daur Ulang Sejahtera',
        sumber: 'kompas.com',
        },

        {
        title: 'Daur Ulang untuk Masa Depan: Bagaimana Proses Ini Menyelamatkan Sumber Daya Alam',
        date: '22 April 2025',
        image: '/images/artikel4.jpg',
        content: [
            'Bandung (ANTARA) - Sumber daya alam yang terbatas dan terus menipis menjadi alasan utama mengapa daur ulang harus diprioritaskan. Setiap hari, manusia menghasilkan jutaan ton sampah yang bisa didaur ulang, namun hanya sebagian kecil dari sampah tersebut yang terkelola dengan baik.',
            '"Sumber daya alam seperti logam, plastik, dan kertas yang kita gunakan setiap hari sebenarnya dapat diolah kembali menjadi produk baru, yang berarti kita tidak perlu lagi menambang atau memproduksi bahan baru dari alam," ujar Dr. Andrianto, pakar pengelolaan sampah.',
            'Daur ulang membantu menghemat energi dan air, yang dibutuhkan dalam proses produksi bahan baru. Misalnya, mendaur ulang satu ton kertas dapat menghemat hingga 17 pohon dan mengurangi emisi CO2 sebesar 2,5 ton.',
            'Di sisi lain, semakin banyak perusahaan yang mengembangkan teknologi untuk meningkatkan efisiensi proses daur ulang, sehingga memungkinkan lebih banyak jenis sampah untuk didaur ulang dengan biaya yang lebih rendah.',
        ],
        pewarta: 'Siti Aminah',
        editor: 'Dewi Kartika',
        copyright: '© 2025 GreenTech Bandung',
        sumber: 'kompas.com',
        },

        {
        title: 'Mendaur Ulang untuk Mengurangi Sampah Plastik: Sebuah Langkah Kecil untuk Dampak Besar',
        date: '20 Desember 2024',
        image: '/images/artikel5.jpg',
        content: [
            'Jakarta (ANTARA) - Sampah plastik adalah salah satu masalah lingkungan terbesar yang dihadapi dunia saat ini. Setiap tahun, milyaran ton plastik berakhir di lautan dan TPA, yang membutuhkan waktu ratusan tahun untuk terurai secara alami.',
            'Namun, ada cara untuk mengurangi dampak plastik terhadap lingkungan, yaitu dengan mendaur ulang. "Mendaur ulang plastik membantu mengurangi jumlah sampah plastik yang mencemari lingkungan, sekaligus mengurangi ketergantungan kita pada produksi plastik baru," jelas Rina Putri, pengelola bank sampah di Jakarta.',
            'Sampah plastik yang didaur ulang dapat diubah menjadi produk baru seperti tas, baju, bahkan perabot rumah tangga. Setiap ton plastik yang didaur ulang dapat mengurangi emisi gas rumah kaca dan menghemat energi.',
            'Untuk itu, masyarakat diminta untuk lebih peduli dalam memilah sampah plastik dan mengirimkannya ke pusat daur ulang yang telah disediakan di banyak kota besar.',
        ],
        pewarta: 'Ivan',
        editor: 'Pandu Satria',
        copyright: '© 2025 GreenTech Bandung',
        sumber: 'kompas.com',
        },
        
    ]
    </script>
    