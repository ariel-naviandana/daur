<template>
    <div :style="overlayStyle">
        <div :style="popupStyle">
            <h2 :style="titleStyle">{{ wasteType?.id ? 'Edit Jenis Sampah' : 'Tambah Jenis Sampah' }}</h2>
            <form @submit.prevent="save">
                <div :style="formGroupStyle">
                    <label :style="labelStyle">Nama Jenis Sampah</label>
                    <input v-model="form.name" type="text" :style="inputStyle" required />
                </div>
                <div :style="formGroupStyle">
                    <label :style="labelStyle">Kategori</label>
                    <select v-model="form.category_id" :style="selectStyle" required>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                </div>
                <div :style="formGroupStyle">
                    <label :style="labelStyle">Satuan</label>
                    <input v-model="form.unit" type="text" :style="inputStyle" placeholder="Contoh: kg, liter, dll." required />
                </div>
                <div :style="formGroupStyle">
                    <label :style="labelStyle">Harga per Satuan</label>
                    <input
                        v-model.number="form.price_per_unit"
                        type="number"
                        step="0.01"
                        :style="inputStyle"
                        placeholder="Contoh: 2000"
                        required
                    />
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
    </div>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'
import { theme } from '@/helpers/theme'
import { useWasteTypeApi } from '@/composables/useWasteTypeApi'
import { useImageApi } from '@/composables/useImageApi'
import { WasteType } from '@/interfaces/WasteType'
import { Category } from '@/interfaces/Category'

const isHoverCancel = ref(false)
const isHoverSave = ref(false)

const props = defineProps<{ wasteType?: WasteType | null, categories: Category[] }>()
const emit = defineEmits(['close', 'saved'])

const form = ref<WasteType>({
    id: 0,
    category_id: props.categories[0]?.id || 0,
    name: '',
    unit: '',
    price_per_unit: 0,
    image: ''
})
const previewImage = ref<string | null>(null)
const { saveWasteType } = useWasteTypeApi()
const { isUploading, uploadToCloudinary } = useImageApi()

watch(
    () => props.wasteType,
    (newVal) => {
        form.value = newVal
            ? { ...newVal }
            : { id: 0, category_id: props.categories[0]?.id || 0, name: '', unit: '', price_per_unit: 0, image: '' }
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

const save = async () => {
    try {
        const success = await saveWasteType(form.value)
        if (success) {
            emit('saved')
            emit('close')
        } else {
            alert('Gagal menyimpan jenis sampah')
        }
    } catch (error) {
        console.error('Error saving waste type:', error)
        alert('Terjadi kesalahan saat menyimpan')
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

const selectStyle = {
    ...inputStyle,
    appearance: 'none',
    backgroundColor: theme.colors.whiteElement
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

const buttonGroupStyle = {
    display: 'flex',
    justifyContent: 'flex-end',
    gap: '12px'
}

const cancelButtonStyle = {
    padding: '8px 16px',
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
    padding: '8px 16px',
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
</script>

<style scoped>
::-webkit-scrollbar {
    display: none
}
</style>
