<template>
  <div v-if="isOpen" :style="overlayStyle">
    <div :style="modalStyle">
      <h3 :style="titleStyle">Edit Role Pengguna</h3>
      <p :style="messageStyle">
        Ubah role untuk <strong>{{ userName }}</strong>
      </p>
      <select v-model="selectedRole" :style="selectStyle">
        <option value="user">User</option>
        <option value="bank_admin">Bank Admin</option>
        <option value="master_admin">Master Admin</option>
      </select>
      <div :style="buttonContainerStyle">
        <button @click="onClose" :style="cancelButtonStyle">Batal</button>
        <button @click="onSave" :style="confirmButtonStyle">Simpan</button>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'
import { useUserApi } from '@/composables/useUserApi'
import { theme } from '@/helpers/theme'

const props = defineProps<{
  isOpen: boolean
  userId: number
  userName: string
  userRole: 'user' | 'bank_admin' | 'master_admin'
}>()

const emit = defineEmits(['close', 'saved'])

const { saveUser } = useUserApi()

const selectedRole = ref(props.userRole)

watch(() => props.userRole, (val) => {
  selectedRole.value = val
})

const onClose = () => {
  emit('close')
}

const onSave = async () => {
  const success = await saveUser({ id: props.userId, role: selectedRole.value })
  if (success) {
    alert('Role berhasil diperbarui!')
    emit('saved',  { id: props.userId, role: selectedRole.value })
    emit('close')
  }
}

// Style seperti di PopupDelete.vue
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

const modalStyle = {
  backgroundColor: 'white',
  padding: '20px',
  borderRadius: '10px',
  width: '300px',
  boxShadow: '0 2px 10px rgba(0, 0, 0, 0.2)',
  textAlign: 'center',
}

const titleStyle = {
  fontSize: '18px',
  fontWeight: 'bold',
  marginBottom: '10px',
  color: theme.colors.darkGrey,
}

const messageStyle = {
  fontSize: '14px',
  color: theme.colors.darkGrey,
  marginBottom: '12px',
}

const selectStyle = {
  width: '100%',
  padding: '6px 10px',
  fontSize: theme.fonts.size.base,
  marginBottom: '20px',
  borderRadius: '6px',
  border: '1px solid #ccc',
}

const buttonContainerStyle = {
  display: 'flex',
  justifyContent: 'center',
  gap: '10px',
}

const confirmButtonStyle = {
  backgroundColor: theme.colors.primary,
  color: 'white',
  border: 'none',
  padding: '6px 28px',
  width: '120px',
  fontSize: theme.fonts.size.base,
  fontWeight: theme.fonts.weight.medium,
  borderRadius: '24px',
  cursor: 'pointer',
}

const cancelButtonStyle = {
  backgroundColor: theme.colors.lightGrey,
  color: 'black',
  border: 'none',
  padding: '6px 28px',
width: '120px',
  fontSize: theme.fonts.size.base,
  fontWeight: theme.fonts.weight.medium,
  borderRadius: '24px',
  cursor: 'pointer',
}
</script>
