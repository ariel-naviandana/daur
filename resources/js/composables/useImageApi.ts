import {ref} from "vue"
import axios from "axios"

export function useImageApi() {
    const isUploading = ref(false)

    const uploadToCloudinary = async (file: File): Promise<string | null> => {
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
            return response.data.secure_url
        } catch (error) {
            console.error('Error uploading to Cloudinary:', error)
            alert('Gagal mengunggah gambar. Silakan coba lagi.')
            return null
        } finally {
            isUploading.value = false
        }
    }

    return {
        isUploading,
        uploadToCloudinary,
    }
}
