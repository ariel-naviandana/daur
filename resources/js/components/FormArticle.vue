<template>
    <div :style="layoutStyle">
        <div :style="contentStyle">
            <div :style="header">
                <h2 :style="headerText">{{ isEdit ? 'Edit Artikel' : 'Tambah Artikel Baru' }}</h2>
                <div :style="buttonContainer">
                    <button @click="backToList" :style="headerCancel">Batal</button>
                    <span v-if="isUploading" :style="spinnerStyle" class="spinner"></span>
                    <button
                        v-if="!isUploading"
                        type="button"
                        @click="submitForm"
                        :style="headerSave"
                    >
                        Simpan
                    </button>
                </div>
            </div>

            <div :style="inputContainer">
                <label :style="labelInput">Judul</label>
                <input v-model="form.title" type="text" placeholder="judul" :style="inputField" />
            </div>

            <div :style="inputContainerCover">
                <div :style="inputFrameCover">
                    <label :style="labelInput">Cover Artikel</label>
                    <div :style="inputCover">
                        <img
                            v-if="previewImage"
                            :src="previewImage"
                            alt="preview image"
                            :style="cloudCover"
                        />
                        <img
                            v-else
                            src="/public/images/icon-upload.svg"
                            alt="upload icon"
                            :style="defaultCover"
                        />
                    </div>
                    <input
                        type="file"
                        accept="image/*"
                        @change="handleImageUpload"
                        :style="{ display: 'none' }"
                        ref="fileInput"
                    />
                    <button @click="$refs.fileInput.click()" :style="btnTambahGambar">
                        {{ isEdit && previewImage ? 'Ubah Gambar' : 'Tambah Gambar' }}
                    </button>
                </div>
            </div>

            <div :style="inputContainer">
                <label :style="labelInput">Isi Artikel</label>
                <textarea
                    v-model="form.content"
                    rows="6"
                    placeholder="isi artikel"
                    :style="inputField"
                ></textarea>
            </div>

            <div :style="inputContainer">
                <label :style="labelInput">Pewarta</label>
                <input
                    v-model="form.pewarta"
                    type="text"
                    placeholder="Nama Pewarta"
                    :style="inputField"
                />
            </div>

            <div :style="inputContainer">
                <label :style="labelInput">Editor</label>
                <input
                    v-model="form.editor"
                    type="text"
                    placeholder="Nama Editor"
                    :style="inputField"
                />
            </div>

            <div :style="inputContainer">
                <label :style="labelInput">Copyright</label>
                <input
                    v-model="form.copyright"
                    type="text"
                    placeholder="Copyright"
                    :style="inputField"
                />
            </div>

            <div :style="inputContainer">
                <label :style="labelInput">Sumber</label>
                <input
                    v-model="form.sumber"
                    type="text"
                    placeholder="Sumber artikel"
                    :style="inputField"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { theme } from '@/helpers/theme'
import { ref } from 'vue'
import { useArticleApi } from '@/composables/useArticleApi'
import type { Article } from '@/interfaces/Article'

const { saveArticle } = useArticleApi()
const isUploading = ref(false)

const props = defineProps<{
    article: Article | null
    isEdit: boolean
}>()

const emit = defineEmits<{
    (e: 'close'): void
    (e: 'saved'): void
}>()

const form = ref<Partial<Article>>({
    id: undefined,
    title: '',
    content: '',
    image_url: null,
    pewarta: null,
    editor: null,
    copyright: null,
    sumber: null,
})
const previewImage = ref<string | null>(null)

if (props.article) {
    form.value = { ...props.article }
    previewImage.value = props.article.image_url || null
}

const handleImageUpload = async (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0]
    if (!file) return

    previewImage.value = URL.createObjectURL(file)
    await uploadToCloudinary(file)
}

const uploadToCloudinary = async (file: File) => {
    isUploading.value = true
    const formData = new FormData()
    const cloudinaryPreset = 'webdaur'
    const cloudinaryURL = 'https://api.cloudinary.com/v1_1/dto6d9tbe/image/upload'

    formData.append('file', file)
    formData.append('upload_preset', cloudinaryPreset)

    try {
        const response = await fetch(cloudinaryURL, {
            method: 'POST',
            body: formData,
        })
        const data = await response.json()
        form.value.image_url = data.secure_url
    } catch (error) {
        console.error('Error uploading to Cloudinary:', error)
        alert('Gagal mengunggah gambar. Silakan coba lagi.')
    } finally {
        isUploading.value = false
    }
}

const submitForm = async () => {
    if (isUploading.value) {
        alert('Mohon tunggu hingga gambar selesai diunggah.')
        return
    }

    const article: Article = {
        id: form.value.id || 0,
        title: form.value.title || '',
        content: form.value.content || '',
        image_url: form.value.image_url,
        pewarta: form.value.pewarta,
        editor: form.value.editor,
        copyright: form.value.copyright,
        sumber: form.value.sumber,
        created_at: form.value.created_at,
        updated_at: form.value.updated_at,
    }

    const success = await saveArticle(article)
    if (success) {
        emit('saved')
    } else {
        alert('Gagal menyimpan artikel.')
    }
}

const backToList = () => {
    emit('close')
}

const layoutStyle = {
    minHeight: '100vh',
    padding: '40px 0',
    display: 'flex',
    justifyContent: 'center',
    backgroundColor: theme.colors.whiteBg,
    fontFamily: theme.fonts.family,
}

const contentStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '16px',
    width: '100%',
    maxWidth: '1200px',
    padding: '24px',
    boxShadow: '0 4px 12px rgba(0, 0, 0, 0.05)',
}

const header = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: '24px',
}

const headerText = {
    fontSize: theme.fonts.size.large,
    fontWeight: theme.fonts.weight.bold,
    margin: 0,
}

const buttonContainer = {
    display: 'flex',
    gap: '8px',
}

const headerCancel = {
    backgroundColor: theme.colors.red,
    color: theme.colors.whiteElement,
    padding: '8px 28px',
    borderRadius: '30px',
    fontSize: theme.fonts.size.base,
    marginRight: '8px',
}

const headerSave = {
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    padding: '8px 30px',
    borderRadius: '30px',
    fontSize: theme.fonts.size.base,
    cursor: 'pointer',
    opacity: isUploading.value ? 0.5 : 1,
}

const inputContainer = { marginBottom: '16px' }

const inputContainerCover = {
    marginBottom: '24px',
    display: 'flex',
    justifyContent: 'center',
}

const labelInput = {
    fontWeight: theme.fonts.weight.medium,
    marginBottom: '6px',
    display: 'block',
}

const inputField = {
    width: '100%',
    padding: '12px 16px',
    backgroundColor: theme.colors.lightGrey,
    borderRadius: '30px',
    border: 'none',
    outline: 'none',
    fontFamily: theme.fonts.family,
    fontSize: theme.fonts.size.base,
}

const inputFrameCover = {
    border: `2px dashed ${theme.colors.lightGrey}`,
    borderRadius: '16px',
    padding: '24px',
    width: '100%',
    textAlign: 'center',
}

const inputCover = {
    display: 'flex',
    justifyContent: 'center',
    marginBottom: '12px',
}

const cloudCover = {
    maxHeight: '256px',
    objectFit: 'cover',
    borderRadius: '8px',
}

const defaultCover = {
    width: '64px',
    height: '64px',
}

const btnTambahGambar = {
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    padding: '8px 16px',
    borderRadius: '9999px',
    fontSize: theme.fonts.size.base,
    cursor: 'pointer',
}

const spinnerStyle = {
    width: '32px',
    height: '32px',
    border: '2px solid rgba(0, 0, 0, 0.2)',
    borderTopColor: '#4caf50',
    borderRadius: '50%',
    animation: 'spin 0.6s linear infinite',
    display: 'inline-block',
    marginLeft: '6px',
    marginTop: '4px',
    verticalAlign: 'center',
}
</script>

<style scoped>
@keyframes spin {
    to {
        transform: rotate(360deg)
    }
}
</style>
