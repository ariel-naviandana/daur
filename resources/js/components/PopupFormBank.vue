<template>
    <div :style="overlayStyle">
        <div :style="popupStyle">
            <h2 :style="titleStyle">{{ bank ? 'Edit Bank Sampah' : 'Tambah Bank Sampah' }}</h2>
            <form @submit.prevent="save">
                <div :style="formGroupStyle">
                    <label :style="labelStyle">Nama Bank Sampah</label>
                    <input v-model="form.name" type="text" :style="inputStyle" required />
                </div>
                <div :style="formGroupStyle">
                    <label :style="labelStyle">Alamat (Opsional)</label>
                    <input v-model="form.address" type="text" :style="inputStyle" />
                </div>
                <div :style="formGroupStyle">
                    <label :style="labelStyle">Telepon (Opsional)</label>
                    <input v-model="form.phone" type="text" :style="inputStyle" />
                </div>
                <div :style="buttonGroupStyle">
                    <button type="button" :style="cancelButtonStyle" @click="$emit('close')">Batal</button>
                    <button type="submit" :style="saveButtonStyle">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'
import { theme } from '@/helpers/theme'
import { useBankApi } from '@/composables/useBankApi'
import { Bank } from '@/interfaces/Bank'

const props = defineProps<{ bank?: Bank | null }>()
const emit = defineEmits(['close', 'saved'])

const form = ref<Bank>({ id: 0, name: '', address: '', phone: '' })
const { saveBank } = useBankApi()

watch(
    () => props.bank,
    (newVal) => {
        form.value = newVal ? { ...newVal } : { id: 0, name: '', address: '', phone: '' }
    },
    { immediate: true }
)

const save = async () => {
    try {
        await saveBank(form.value)
        emit('saved')
        emit('close')
    } catch (error) {
        console.error('Error saving bank:', error)
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
</script>

<style scoped>
::-webkit-scrollbar {
    display: none;
}
</style>
