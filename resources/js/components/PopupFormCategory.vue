<template>
    <div :style="overlayStyle">
        <div :style="popupStyle">
            <h2 :style="titleStyle">{{ category ? 'Edit Kategori' : 'Tambah Kategori' }}</h2>
            <form @submit.prevent="save">
                <div :style="formGroupStyle">
                    <label :style="labelStyle">Nama Kategori</label>
                    <input v-model="form.name" type="text" :style="inputStyle" required />
                </div>
                <div :style="formGroupStyle">
                    <label :style="labelStyle">Gambar (Opsional)</label>
                    <input
                        type="file"
                        accept="image/*"
                        @change="handleFileChange"
                        :style="inputStyle"
                    />
                    <div v-if="previewImage" :style="previewContainerStyle">
                        <img :src="previewImage" :style="previewImageStyle" />
                    </div>
                </div>
                <div :style="buttonGroupStyle">
                    <button type="button" :style="cancelButtonStyle" @click="$emit('close')">Batal</button>
                    <button type="submit" :style="saveButtonStyle" :disabled="isUploading">
                        {{ isUploading ? 'Mengunggah...' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import { theme } from '../helpers/theme'
import { Category } from '../interfaces/Category'

const props = defineProps<{ category?: Category | null }>()
const emit = defineEmits(['close', 'saved'])

const form = ref<Category>({ id: 0, name: '', image: '' })
const previewImage = ref<string | null>(null)
const isUploading = ref(false)

watch(
    () => props.category,
    (newVal) => {
        form.value = newVal ? { ...newVal } : { id: 0, name: '', image: '' }
        previewImage.value = newVal?.image || null
    },
    { immediate: true }
)

const handleFileChange = async (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0]
    if (!file) return

    const reader = new FileReader()
    reader.onload = () => {
        previewImage.value = reader.result as string
    }
    reader.readAsDataURL(file)

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
        const response = await axios.post(cloudinaryURL, formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        })
        form.value.image = response.data.secure_url
    } catch (error) {
        console.error('Error uploading to Cloudinary:', error)
        alert('Gagal mengunggah gambar. Silakan coba lagi.')
    } finally {
        isUploading.value = false
    }
}

const save = async () => {
    try {
        if (form.value.id) {
            await axios.put(`/categories/${form.value.id}`, form.value)
        } else {
            await axios.post('/categories', form.value)
        }
        emit('saved')
        emit('close')
    } catch (error) {
        console.error('Error saving category:', error)
    }
}

const overlayStyle = {
    position: 'fixed',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    zIndex: 1000,
}

const popupStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '16px',
    padding: '24px',
    width: '90%',
    maxWidth: '400px',
    maxHeight: '90vh',
    overflowY: 'auto',
    boxShadow: '0 4px 6px rgba(0, 0, 0, 0.1)',
}

const titleStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    marginBottom: '16px',
    color: theme.colors.darkGrey,
}

const formGroupStyle = {
    marginBottom: '16px',
    display: 'flex',
    flexDirection: 'column',
}

const labelStyle = {
    fontSize: theme.fonts.size.base,
    marginBottom: '8px',
    color: theme.colors.darkGrey,
    fontWeight: theme.fonts.weight.medium,
}

const inputStyle = {
    padding: '10px 12px',
    fontSize: theme.fonts.size.base,
    borderRadius: '6px',
    border: `1px solid ${theme.colors.lightGrey}`,
    outline: 'none',
    fontFamily: theme.fonts.family,
}

const buttonGroupStyle = {
    display: 'flex',
    justifyContent: 'flex-end',
    gap: '12px',
}

const cancelButtonStyle = {
    padding: '8px 16px',
    borderRadius: '6px',
    backgroundColor: theme.colors.lightGrey,
    color: theme.colors.darkGrey,
    fontSize: theme.fonts.size.base,
    border: 'none',
    cursor: 'pointer',
}

const saveButtonStyle = {
    padding: '8px 16px',
    borderRadius: '6px',
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    fontSize: theme.fonts.size.base,
    border: 'none',
    cursor: 'pointer',
}

const previewContainerStyle = {
    marginTop: '12px',
    textAlign: 'center',
}

const previewImageStyle = {
    width: '100px',
    height: '100px',
    objectFit: 'cover',
    borderRadius: '6px',
}
</script>

<style scoped>
::-webkit-scrollbar {
    display: none;
}
</style>
