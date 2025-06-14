<template>
    <div :style="overlayStyle">
        <div :style="popupStyle">
            <h2 :style="titleStyle">{{ form.id !== 0 ? 'Edit Kategori' : 'Tambah Kategori' }}</h2>
            <form @submit.prevent="save">
                <div :style="formGroupStyle">
                    <label :style="labelStyle">Nama Kategori</label>
                    <input v-model="form.name" type="text" :style="inputStyle" required />
                </div>

                <div :style="formGroupStyle">
                    <label :style="labelStyle">Gambar (Opsional)</label>
                    <input type="file" accept="image/*" @change="handleFileChange" :style="inputStyle" />
                    <div v-if="previewImage" :style="previewContainerStyle">
                        <img :src="previewImage" :style="previewImageStyle" />
                    </div>
                </div>

                <div :style="buttonGroupStyle">
                    <button
                        type="button"
                        :style="[cancelButtonStyle, isHoverCancel ? buttonHoverStyleCancel : {}]"
                        @mouseover="isHoverCancel = true"
                        @mouseleave="isHoverCancel = false"
                        @click="$emit('close')"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        :style="[saveButtonStyle, isHoverSave ? buttonHoverStyleSave : {}]"
                        @mouseover="isHoverSave = true"
                        @mouseleave="isHoverSave = false"
                        :disabled="isUploading"
                    >
                        {{ isUploading ? 'Mengunggah...' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>

        <PopupEdit
            v-if="showConfirmSavePopup"
            :is-open="showConfirmSavePopup"
            :item-name="form.name"
            @close="cancelConfirm"
            @confirm="confirmSave"
        />
    </div>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'
import { theme } from '@/helpers/theme'
import { useCategoryApi } from '@/composables/useCategoryApi'
import { useImageApi } from '@/composables/useImageApi'
import { Category } from '@/interfaces/Category'
import PopupEdit from './PopupEdit.vue'

const props = defineProps<{ category?: Category | null }>()
const emit = defineEmits(['close', 'saved'])

const isHoverCancel = ref(false)
const isHoverSave = ref(false)
const showConfirmSavePopup = ref(false)
const previewImage = ref<string | null>(null)
const form = ref<Category>({ id: 0, name: '', image: '' })

const { saveCategory } = useCategoryApi()
const { isUploading, uploadToCloudinary } = useImageApi()

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

    const imageUrl = await uploadToCloudinary(file)
    if (imageUrl) form.value.image = imageUrl
}

const save = () => {
    // Jika edit, tampilkan konfirmasi
    if (form.value.id !== 0) {
        showConfirmSavePopup.value = true
    } else {
        confirmSave()
    }
}

const confirmSave = async () => {
    showConfirmSavePopup.value = false

    try {
        await saveCategory(form.value)
        emit('saved')
        emit('close')
    } catch (error) {
        console.error('Gagal menyimpan kategori:', error)
    }
}

const cancelConfirm = () => {
    showConfirmSavePopup.value = false
}

// Styles
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
    zIndex: 1000
}

const popupStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '16px',
    padding: '24px',
    width: '90%',
    maxWidth: '400px',
    maxHeight: '90vh',
    overflowY: 'auto',
    boxShadow: '0 4px 6px rgba(0, 0, 0, 0.1)'
}

const titleStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    marginBottom: '16px',
    color: theme.colors.darkGrey
}

const formGroupStyle = {
    marginBottom: '16px',
    display: 'flex',
    flexDirection: 'column'
}

const labelStyle = {
    fontSize: theme.fonts.size.base,
    marginBottom: '8px',
    color: theme.colors.darkGrey,
    fontWeight: theme.fonts.weight.medium
}

const inputStyle = {
    padding: '10px 12px',
    fontSize: theme.fonts.size.base,
    borderRadius: '6px',
    border: `1px solid ${theme.colors.lightGrey}`,
    outline: 'none',
    fontFamily: theme.fonts.family
}

const buttonGroupStyle = {
    display: 'flex',
    justifyContent: 'flex-end',
    gap: '12px'
}

const cancelButtonStyle = {
    padding: '8px',
    width: '120px',
    borderRadius: '8px',
    backgroundColor: theme.colors.lightGrey,
    color: theme.colors.darkGrey,
    fontSize: theme.fonts.size.base,
    border: 'none',
    cursor: 'pointer',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
    transition: '0.2s ease-in-out',
}

const saveButtonStyle = {
    padding: '8px',
    width: '120px',
    borderRadius: '8px',
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    fontSize: theme.fonts.size.base,
    border: 'none',
    cursor: 'pointer',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
    transition: '0.2s ease-in-out',
}

const buttonHoverStyleCancel = {
    backgroundColor: theme.colors.grey,
    transform: 'scale(1.05)',
}

const buttonHoverStyleSave = {
    backgroundColor: '#2d862d',
    transform: 'scale(1.05)',
}

const previewContainerStyle = {
    marginTop: '12px',
    textAlign: 'center'
}

const previewImageStyle = {
    width: '100px',
    height: '100px',
    objectFit: 'cover',
    borderRadius: '6px'
}
</script>

<style scoped>
::-webkit-scrollbar {
    display: none;
}
</style>
