<template>
    <div :style="cardStyle">
        <div :style="contentStyle">
            <h3 :style="titleStyle">{{ bank.name }}</h3>
            <p v-if="bank.address" :style="infoStyle">Alamat: {{ bank.address }}</p>
            <p v-if="bank.phone" :style="infoStyle">Telepon: {{ bank.phone }}</p>
        </div>
        <div :style="buttonGroupStyle">
            <button :style="editButtonStyle" @click="$emit('edit', bank)">Edit</button>
            <button :style="deleteButtonStyle" @click="$emit('delete', bank)">Hapus</button>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { defineProps } from 'vue'
import { Bank } from '@/interfaces/Bank'
import { theme } from '@/helpers/theme'

const props = defineProps<{ bank: Bank }>()

const formatDate = (dateString: string) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    })
}

const cardStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '8px',
    padding: '16px',
    boxShadow: '0 2px 4px rgba(0, 0, 0, 0.1)',
    display: 'flex',
    flexDirection: 'column',
    height: '100%',
}

const contentStyle = {
    flexGrow: 1,
    display: 'flex',
    flexDirection: 'column',
    gap: '8px',
}

const titleStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.darkGrey,
    margin: 0,
}

const infoStyle = {
    fontSize: theme.fonts.size.small,
    color: theme.colors.grey,
    margin: 0,
}

const buttonGroupStyle = {
    display: 'flex',
    gap: '8px',
    marginTop: 'auto',
    paddingTop: '12px',
}

const editButtonStyle = {
    backgroundColor: theme.colors.blue,
    color: theme.colors.whiteElement,
    padding: '6px 12px',
    borderRadius: '4px',
    border: 'none',
    cursor: 'pointer',
    fontSize: theme.fonts.size.small,
}

const deleteButtonStyle = {
    backgroundColor: theme.colors.red,
    color: theme.colors.whiteElement,
    padding: '6px 12px',
    borderRadius: '4px',
    border: 'none',
    cursor: 'pointer',
    fontSize: theme.fonts.size.small,
}
</script>
