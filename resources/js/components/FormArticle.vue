<template>
    <div class="min-h-screen py-10 flex justify-center">
        <div class="bg-white rounded-xl w-full max-w-[1200px] p-6 shadow">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">{{ isEdit ? 'Edit Artikel' : 'Tambah Artikel Baru' }}</h2>
                <div class="space-x-2">
                    <button @click="backToList" class="bg-red-500 text-white px-4 py-2 rounded">Batal</button>
                    <button @click="submitForm" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </div>

            <!-- Judul -->
            <div class="mb-4">
                <label class="block font-semibold mb-1">Judul</label>
                <input v-model="form.title" type="text" placeholder="judul"
                       class="w-full px-4 py-3 bg-gray-100 rounded-full outline-none" />
            </div>

            <!-- Gambar -->
            <div class="mb-6 flex justify-center">
                <div class="border-dashed border-2 border-gray-300 rounded-2xl p-6 text-center w-full">
                    <div class="flex justify-center mb-3">
                        <img v-if="previewImage" :src="previewImage" class="max-h-64 object-cover rounded" />
                        <img v-else src="/public/images/icon-upload.svg" class="w-16 h-16" alt="upload icon" />
                    </div>
                    <input type="file" @change="handleImageUpload" class="hidden" ref="fileInput" />
                    <button @click="$refs.fileInput.click()" class="bg-green-600 text-white px-4 py-2 rounded-full">Tambah Gambar</button>
                </div>
            </div>

            <!-- Isi Artikel -->
            <div class="mb-4">
                <label class="block font-semibold mb-1">Isi Artikel</label>
                <textarea v-model="form.content" rows="6" placeholder="isi artikel"
                          class="w-full px-4 py-3 bg-gray-100 rounded-2xl outline-none resize-none"></textarea>
            </div>

            <!-- Pewarta -->
            <div class="mb-4">
                <label class="block font-semibold mb-1">Pewarta</label>
                <input v-model="form.reporter" type="text" placeholder="Nama Pewarta"
                       class="w-full px-4 py-3 bg-gray-100 rounded-full outline-none" />
            </div>

            <!-- Editor -->
            <div class="mb-4">
                <label class="block font-semibold mb-1">Editor</label>
                <input v-model="form.editor" type="text" placeholder="Nama Editor"
                       class="w-full px-4 py-3 bg-gray-100 rounded-full outline-none" />
            </div>

            <!-- Copyright -->
            <div class="mb-4">
                <label class="block font-semibold mb-1">Copyright</label>
                <input v-model="form.copyright" type="text" placeholder="Copyright"
                       class="w-full px-4 py-3 bg-gray-100 rounded-full outline-none" />
            </div>

            <!-- Sumber -->
            <div class="mb-4">
                <label class="block font-semibold mb-1">Sumber</label>
                <input v-model="form.source" type="text" placeholder="Sumber artikel"
                       class="w-full px-4 py-3 bg-gray-100 rounded-full outline-none" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'

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
    image: null,
})
const previewImage = ref(null)

// Saat komponen dipasang atau article berubah
onMounted(() => {
    if (props.article) {
        isEdit.value = true
        form.value.title = props.article.title
        form.value.content = props.article.content
        previewImage.value = props.article.image_url || ''
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
        previewImage.value = newVal.image_url || ''
    } else {
        isEdit.value = false
        form.value = { title: '', content: '', image: null }
        previewImage.value = null
    }
})

const handleImageUpload = (event) => {
    const file = event.target.files[0]
    if (file) {
        form.value.image = file
        previewImage.value = URL.createObjectURL(file)
    }
}

const submitForm = async () => {
    const formData = new FormData()
    formData.append('title', form.value.title)
    formData.append('content', form.value.content)
    formData.append('pewarta', form.value.reporter || '')  // Tambahkan pewarta
    formData.append('editor', form.value.editor || '')    // Tambahkan editor
    formData.append('copyright', form.value.copyright || '') // Tambahkan copyright
    formData.append('sumber', form.value.source || '')    // Tambahkan sumber
    if (form.value.image) {
        formData.append('image', form.value.image)
    }

    try {
        if (isEdit.value && props.article?.id) {
            await axios.post(`/articles/${props.article.id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
        } else {
            await axios.post('/articles', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
        }

        emit('saved')
        emit('close')
    } catch (error) {
        console.error('Gagal simpan artikel:', error)
    }
}

const backToList = () => {
    emit('close')  // Emit 'close' untuk memberi tahu komponen induk menutup form
}
</script>

<style scoped>
</style>
