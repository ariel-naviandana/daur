<template>
    <div v-if="isOpen" :style="overlayStyle">
        <div :style="modalStyle">
            <h3 :style="titleStyle">Edit Role Pengguna</h3>
            <p :style="messageStyle">
                Ubah role untuk <strong>{{ userName }}</strong>
            </p>
            <select v-model="selectedRole" :style="selectStyle" @change="onRoleChange">
                <option value="user">User</option>
                <option value="bank_admin">Bank Admin</option>
                <option value="master_admin">Master Admin</option>
            </select>
            <select v-if="selectedRole === 'bank_admin'" v-model="selectedBankId" :style="selectStyle">
                <option value="" disabled>Pilih Bank Sampah</option>
                <option v-for="bank in banks" :key="bank.id" :value="bank.id">{{ bank.name }}</option>
            </select>
            <div :style="buttonContainerStyle">
                <button
                    @click="onClose"
                    :style="[cancelButtonStyle, isHoverCancel ? buttonHoverStyleCancel : {}]"
                    @mouseover="isHoverCancel = true"
                    @mouseleave="isHoverCancel = false"
                >
                    Batal
                </button>
                <button
                    @click="confirmAction"
                    :style="[confirmButtonStyle, isHoverConfirm ? buttonHoverStyleConfirm : {}]"
                    @mouseover="isHoverConfirm = true"
                    @mouseleave="isHoverConfirm = false"
                >
                    Simpan
                </button>
            </div>
        </div>
    </div>

    <PopupConfirm
        v-if="confirmation"
        message="Apakah Anda yakin ingin mengubah role pengguna ini?"
        @confirm="handleConfirm"
        @cancel="cancelConfirm"
    />
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'
import { useUserApi } from '@/composables/useUserApi'
import { useBankApi } from '@/composables/useBankApi'
import { theme } from '@/helpers/theme'
import { Bank } from '@/interfaces/Bank'
import PopupConfirm from './PopupConfirm.vue'

const props = defineProps<{
    isOpen: boolean
    userId: number
    userName: string
    userRole: 'user' | 'bank_admin' | 'master_admin'
    userBankId?: number | null
}>()

const emit = defineEmits(['close', 'saved'])

const { saveUser } = useUserApi()
const { getBanks } = useBankApi()

const selectedRole = ref(props.userRole)
const selectedBankId = ref(props.userBankId ?? '')
const banks = ref<Bank[]>([])
const confirmation = ref(false)
const isHoverCancel = ref(false)
const isHoverConfirm = ref(false)

const fetchBanks = async () => {
    banks.value = await getBanks()
}

watch(() => props.userRole, (val) => {
    selectedRole.value = val
})

watch(() => props.userBankId, (val) => {
    selectedBankId.value = val ?? ''
})

watch(() => selectedRole.value, (val) => {
    if (val !== 'bank_admin') {
        selectedBankId.value = ''
    }
})

const onRoleChange = () => {
    if (selectedRole.value === 'bank_admin' && banks.value.length === 0) {
        fetchBanks()
    }
}

const onClose = () => {
    emit('close')
}

const confirmAction = () => {
    if (selectedRole.value === 'bank_admin' && !selectedBankId.value) {
        alert('Harap pilih Bank Sampah untuk role Bank Admin.')
        return
    }
    confirmation.value = true
}

const handleConfirm = async () => {
    const userData = {
        id: props.userId,
        role: selectedRole.value,
        ...(selectedRole.value === 'bank_admin' && selectedBankId.value ? { bank_id: selectedBankId.value } : {})
    }
    const success = await saveUser(userData)
    if (success) {
        alert('Role berhasil diperbarui!')
        emit('saved', { id: props.userId, role: selectedRole.value, bank_id: selectedBankId.value })
        emit('close')
    }
    confirmation.value = false
}

const cancelConfirm = () => {
    confirmation.value = false
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

const modalStyle = {
    backgroundColor: theme.colors.whiteElement,
    padding: '20px',
    borderRadius: '10px',
    width: '300px',
    boxShadow: '0 2px 10px rgba(0, 0, 0, 0.2)',
    textAlign: 'center'
}

const titleStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    marginBottom: '10px',
    color: theme.colors.darkGrey
}

const messageStyle = {
    fontSize: theme.fonts.size.base,
    color: theme.colors.darkGrey,
    marginBottom: '12px'
}

const selectStyle = {
    width: '100%',
    padding: '6px 10px',
    fontSize: theme.fonts.size.base,
    marginBottom: '20px',
    borderRadius: '6px',
    border: `1px solid ${theme.colors.lightGrey}`
}

const buttonContainerStyle = {
    display: 'flex',
    justifyContent: 'center',
    gap: '10px'
}

const confirmButtonStyle = {
    backgroundColor: theme.colors.primary,
    color: theme.colors.whiteElement,
    border: 'none',
    padding: '6px 28px',
    width: '120px',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    borderRadius: '24px',
    cursor: 'pointer',
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)'
}

const cancelButtonStyle = {
    backgroundColor: theme.colors.lightGrey,
    color: theme.colors.darkGrey,
    border: 'none',
    padding: '6px 28px',
    width: '120px',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    borderRadius: '24px',
    cursor: 'pointer',
    transition: '0.2s ease-in-out',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)'
}

const buttonHoverStyleConfirm = {
    transform: 'scale(1.05)',
    backgroundColor: '#2d862d'
}

const buttonHoverStyleCancel = {
    transform: 'scale(1.05)',
    backgroundColor: theme.colors.grey
}
</script>
