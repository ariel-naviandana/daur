<template>
    <div :style="layoutStyle">
        <Navbar />
        <div :style="contentStyle" class="p-6">
            <FormArticle
                v-if="showForm"
                :article="selectedArticle"
                :isEdit="!!selectedArticle"
                @close="closeForm"
                @saved="onArticleSaved"
            />
            <div v-else>
                <div :style="headingContainerStyle">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="28"
                        height="28"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        :style="iconStyle"
                    >
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        <path d="M9 12h6"></path>
                        <path d="M9 16h6"></path>
                        <path d="M12 8L16 12"></path>
                    </svg>
                    <h1 :style="headingStyle">Daftar Artikel</h1>
                </div>

                <div :style="sorting">
                    <select v-model="sortOrder" @change="fetchArticles" :style="selectStyle">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                    </select>
                    <div :style="rightControl">
                        <button
                            :style="[addButtonStyle, isHoverAdd ? buttonHoverStyleAdd : {}]"
                            @mouseover="isHoverAdd = true"
                            @mouseleave="isHoverAdd = false"
                            class="btn_tambah"
                            @click="openCreateForm"
                        >
                            <img src="/images/icon_plus.svg" alt="Tambah Artikel" />
                            Tambah
                        </button>
                        <div :style="searchWrapperStyle">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                :style="searchIconStyle"
                            >
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input
                                v-model="searchQuery"
                                placeholder="Cari judul artikel"
                                :style="searchInputStyle"
                                @input="fetchArticles"
                            />
                        </div>
                    </div>
                </div>

                <div v-if="articles.length === 0" :style="noResultsStyle">
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
                    <p :style="noResultsDescStyle">Coba sesuaikan filter atau kata kunci pencarian Anda</p>
                </div>

                <ManajemenArtikelCard
                    v-for="article in articles"
                    :key="article.id"
                    :article="article"
                    @edit="openEditForm"
                    @delete="() => requestDeleteArticle(article)"
                />
                <PopupDeleteArticle
                    :isOpen="showDeletePopup"
                    :itemName="articleToDelete?.title || ''"
                    @close="cancelDelete"
                    @confirm="confirmDeleteArticle"
                />
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import Navbar from '@/components/Navbar.vue'
import FormArticle from '@/components/FormArticle.vue'
import { theme } from '@/helpers/theme'
import { ref, onMounted } from 'vue'
import { useArticleApi } from '@/composables/useArticleApi'
import ManajemenArtikelCard from '@/components/ManajemenArtikelCard.vue'
import PopupDeleteArticle from "@/components/PopupDeleteArticle.vue"
import type { Article } from '@/interfaces/Article'

const { getArticles } = useArticleApi()
const isHoverAdd = ref(false)
const showDeletePopup = ref(false)
const articleToDelete = ref<Article | null>(null)

const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family,
    overflowX: 'hidden',
}

const contentStyle = {
    maxWidth: '1200px',
    margin: '0 auto',
    color: theme.colors.darkGrey,
}

const headingContainerStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '12px',
    marginBottom: '20px',
}

const iconStyle = { color: theme.colors.darkGrey }

const headingStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    margin: 0,
}

const searchWrapperStyle = {
    marginLeft: '5px',
    display: 'flex',
    alignItems: 'center',
    backgroundColor: theme.colors.whiteElement,
    border: `1px solid ${theme.colors.lightGrey}`,
    borderRadius: '100px',
    padding: '8px 16px',
    width: '300px',
}

const searchIconStyle = {
    color: theme.colors.lightGrey,
    marginRight: '12px',
}

const searchInputStyle = {
    border: 'none',
    outline: 'none',
    width: '100%',
    fontSize: theme.fonts.size.base,
    backgroundColor: 'transparent',
    color: theme.colors.darkGrey,
    fontFamily: theme.fonts.family,
}

const noResultsStyle = {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    padding: '48px 0',
    textAlign: 'center',
}

const noResultsTextStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.darkGrey,
    margin: '0 0 8px 0',
}

const noResultsDescStyle = {
    fontSize: theme.fonts.size.sm,
    color: theme.colors.grey,
    margin: 0,
}

const selectStyle = {
    padding: '8px 16px',
    paddingRight: '36px',
    fontSize: theme.fonts.size.base,
    borderRadius: '8px',
    border: `1px solid ${theme.colors.lightGrey}`,
    fontFamily: theme.fonts.family,
    appearance: 'none',
    backgroundImage: `url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e")`,
    backgroundRepeat: 'no-repeat',
    backgroundPosition: 'right 14px center',
    backgroundSize: '16px',
}

const addButtonStyle = {
    height: '40px',
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    borderRadius: '30px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    gap: '6px',
    paddingLeft: '10px',
    paddingRight: '14px',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
    transition: '0.2s ease-in-out',
}

const buttonHoverStyleAdd = {
    backgroundColor: '#2d862d',
    transform: 'scale(1.05)',
}

const articles = ref<Article[]>([])
const searchQuery = ref('')
const sortOrder = ref<'newest' | 'oldest'>('newest')
const showForm = ref(false)
const selectedArticle = ref<Article | null>(null)

const fetchArticles = async () => {
    articles.value = await getArticles(searchQuery.value, sortOrder.value)
}

onMounted(fetchArticles)

const openCreateForm = () => {
    selectedArticle.value = null
    showForm.value = true
}

const openEditForm = (article: Article) => {
    selectedArticle.value = { ...article }
    showForm.value = true
}

const closeForm = () => {
    showForm.value = false
}

const onArticleSaved = () => {
    showForm.value = false
    fetchArticles()
}

const deleteArticle = async (id: number) => {
    await useArticleApi().deleteArticle(id)
    await fetchArticles()
}

const requestDeleteArticle = (article: Article) => {
    articleToDelete.value = article
    showDeletePopup.value = true
}

const confirmDeleteArticle = async () => {
    if (articleToDelete.value) {
        await deleteArticle(articleToDelete.value.id)
    }
    showDeletePopup.value = false
    articleToDelete.value = null
}

const cancelDelete = () => {
    showDeletePopup.value = false
    articleToDelete.value = null
}

const sorting = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: '1rem',
}

const rightControl = {
    display: 'flex',
    gap: '1rem',
    width: '100%',
    justifyContent: 'flex-end',
    alignItems: 'center',
}
</script>

<style scoped>
::-webkit-scrollbar {
    display: none;
}
</style>
