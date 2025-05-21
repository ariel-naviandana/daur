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
                    @input="fetchArticles"
                />
                <hr :style="dividerStyle" />
                <div :style="cardWrapperStyle">
                    <template v-if="articles.length > 0">
                        <h3 :style="sectionTitle">Artikel terbaru</h3>
                        <div
                            v-for="artikel in articles"
                            :key="artikel.id"
                            :style="cardContainerStyle"
                            @click="selectArtikel(artikel)"
                            style="cursor: pointer"
                        >
                            <ArtikelCard
                                :article="artikel"
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
                        @mouseover="isHover = true"
                        @mouseleave="isHover = false"
                        :style="[backButtonStyle, isHover ? buttonHoverStyle : {}]"
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
                    <p :style="dateStyle">{{ formatDate(selectedArtikel.created_at) }}</p>
                    <img
                        :src="selectedArtikel.image_url || '/images/artikel2.jpg'"
                        :style="detailImageStyle"
                    />
                    <div :style="paragraphStyle">{{ selectedArtikel.content }}</div>
                    <div :style="infoStyle">
                        <p>Pewarta: {{ selectedArtikel.pewarta || 'Tidak diketahui' }}</p>
                        <p>Editor: {{ selectedArtikel.editor || 'Tidak diketahui' }}</p>
                        <p>Copyright: {{ selectedArtikel.copyright || 'Tidak diketahui' }}</p>
                        <p>Sumber: {{ selectedArtikel.sumber || 'Tidak diketahui' }}</p>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script lang="ts" setup>
import Navbar from '@/components/Navbar.vue'
import ArtikelCard from '@/components/ArtikelCard.vue'
import { ref, onMounted } from 'vue'
import { theme } from '@/helpers/theme'
import { useArticleApi } from '@/composables/useArticleApi'
import type { Article } from '@/interfaces/Article'

const { getArticles } = useArticleApi()
const articles = ref<Article[]>([])
const selectedArtikel = ref<Article | null>(null)
const searchQuery = ref('')
const isHover = ref(false)

const fetchArticles = async () => {
    articles.value = await getArticles(searchQuery.value, 'newest')
}

const selectArtikel = (artikel: Article) => {
    selectedArtikel.value = artikel
}

const formatDate = (dateStr?: string) => {
    if (!dateStr) return 'Tanggal tidak tersedia'
    const date = new Date(dateStr)
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    })
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
const sectionTitle = {
    fontSize: '20px',
    marginBottom: '15px',
    textAlign: 'center',
    fontWeight: theme.fonts.weight.bold,
}
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
    width: '100%',
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
    color: theme.colors.primary,
    fontWeight: theme.fonts.weight.medium,
    fontSize: theme.fonts.size.small,
    marginBottom: '20px',
    transition: '0.2s ease-in-out',
}
const noResultsStyle = {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    textAlign: 'center',
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
    color: theme.colors.primary,
    fontSize: theme.fonts.size.small,
    marginBottom: '30px',
    marginTop: '15px',
    textAlign: 'center',
}
const infoStyle = {
    marginTop: '32px',
    color: theme.colors.grey,
    fontSize: theme.fonts.size.small,
    borderTop: '1px solid #ccc',
    paddingTop: '12px',
    lineHeight: '1.5',
}

const buttonHoverStyle = {
    transform: 'scale(1.05)',
}
onMounted(fetchArticles)
</script>
