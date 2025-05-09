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

            <!-- Judul -->
            <div :style="inputContainer">
                <label :style="labelInput">Judul</label>
                <input v-model="form.title" type="text" placeholder="judul" :style="inputField"/>
            </div>

            <!-- Gambar -->
            <div :style="inputContainerCover">
                <div :style="inputFrameCover">
                    <label :style="labelInput">Cover Artikel</label>
                    <div :style="inputCover">
                        <img v-if="previewImage" :src="previewImage" alt="preview image" :style="cloudCover"/>
                        <img v-else src="/public/images/icon-upload.svg" alt="upload icon" :style="defaultCover"/>
                    </div>
                    <input
                        type="file"
                        accept="image/*"
                        @change="handleImageUpload"
                        :style="{ display: 'none' }"
                        ref="fileInput" />
                    <button @click="$refs.fileInput.click()" :style="btnTambahGambar">
                        {{ isEdit && previewImage ? 'Ubah Gambar' : 'Tambah Gambar' }}
                    </button>
                </div>
            </div>

            <!-- Isi Artikel -->
            <div :style="inputContainer">
                <label :style="labelInput">Isi Artikel</label>
                <textarea v-model="form.content" rows="6" placeholder="isi artikel" :style="inputField"></textarea>
            </div>

            <!-- Pewarta -->
            <div :style="inputContainer">
                <label :style="labelInput">Pewarta</label>
                <input v-model="form.reporter" type="text" placeholder="Nama Pewarta" :style="inputField"/>
            </div>

            <!-- Editor -->
            <div :style="inputContainer">
                <label :style="labelInput">Editor</label>
                <input v-model="form.editor" type="text" placeholder="Nama Editor" :style="inputField"/>
            </div>

            <!-- Copyright -->
            <div :style="inputContainer">
                <label :style="labelInput">Copyright</label>
                <input v-model="form.copyright" type="text" placeholder="Copyright" :style="inputField"/>
            </div>

            <!-- Sumber -->
            <div :style="inputContainer">
                <label :style="labelInput">Sumber</label>
                <input v-model="form.source" type="text" placeholder="Sumber artikel" :style="inputField"/>
            </div>
        </div>
    </div>
</template>

<script setup>
import { theme } from '@/helpers/theme'
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'

const isUploading = ref(false)

const props = defineProps({
    article: {
        type: Object,
        default: null
    }
})
const emit = defineEmits(['close', 'saved'])

const isEdit = ref(false)

const form = ref({
    title: '',
    content: '',
    image: '',
})
const previewImage = ref(null)

// Saat komponen dipasang atau article berubah
onMounted(() => {
    if (props.article) {
        isEdit.value = true
        form.value.title = props.article.title
        form.value.content = props.article.content
        previewImage.value = props.article.image || ''
        form.value.image = props.article.image || ''
        form.value.reporter = props.article.pewarta || ''
        form.value.editor = props.article.editor || ''
        form.value.copyright = props.article.copyright || ''
        form.value.source = props.article.sumber || ''
    }
})

watch(() => props.article, (newVal) => {
    if (newVal) {
        isEdit.value = true
        form.value.title = newVal.title
        form.value.content = newVal.content
        previewImage.value = newVal.image || ''
        form.value.image = newVal.image || ''
    } else {
        isEdit.value = false
        form.value = { title: '', content: '', image: null }
        previewImage.value = null
    }
})

const handleImageUpload = async (event) => {
    const file = event.target.files[0]
    if (!file) return

    previewImage.value = URL.createObjectURL(file)
    await uploadToCloudinary(file)
}

const uploadToCloudinary = async (file) => {
    isUploading.value = true
    const formData = new FormData()
    const cloudinaryPreset = 'webdaur'
    const cloudinaryURL = 'https://api.cloudinary.com/v1_1/dto6d9tbe/image/upload'

    formData.append('file', file)
    formData.append('upload_preset', cloudinaryPreset)

    try {
        const response = await axios.post(cloudinaryURL, formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        })
        form.value.image = response.data.secure_url
        console.log('Upload berhasil, URL:', form.value.image)
    } catch (error) {
        console.error('Error uploading to Cloudinary:', error.response?.data || error.message)
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
    console.log('Article ID:', props.article?.id)

    const payload = {
        title: form.value.title,
        content: form.value.content,
        image_url: form.value.image,
        pewarta: form.value.reporter,
        editor: form.value.editor,
        copyright: form.value.copyright,
        sumber: form.value.source
    }

    try {
        if (isEdit.value && props.article?.id) {
            console.log('Sending PUT request to update article with ID:', props.article.id)
            const response = await axios.put(`/articles/${props.article.id}`, payload)
            console.log('Response from backend:', response)
        } else {
            const response = await axios.post('/articles', payload)
            console.log('payload', payload)
            console.log('Article created:', response)
        }
        emit('saved')
    } catch (error) {
        console.error('Gagal menyimpan artikel:', error)
        alert('Gagal menyimpan artikel.')
    }
}

const backToList = () => {
    emit('close')  // Menutup form
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
</script>

<style scoped>
.spinner {
    width: 32px;
    height: 32px;
    border: 2px solid rgba(0, 0, 0, 0.2);
    border-top-color: #4caf50;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
    display: inline-block;
    margin-left: 6px;
    margin-top: 4px;
    vertical-align: center;
}

@keyframes spin {
    to {
        transform: rotate(360deg)
    }
}
</style>
