<template>
    <div :style="cardStyle">
        <img v-if="category.image" :src="category.image" :alt="category.name" :style="imageStyle" />
        <img v-else src="/public/images/icon_article.svg" :alt="category.name" :style="imageStyle" />
        <div :style="contentStyle">
            <p :style="nameStyle">{{ category.name }}</p>
            <div :style="actionsStyle">
                <button
                    :style="[editButtonStyle, isHoverEdit ? buttonHoverStyleEdit : {}]"
                    @mouseover="isHoverEdit = true"
                    @mouseleave="isHoverEdit = false"
                    @click="$emit('edit', category)"
                >
                    Edit
                </button>
                <button
                    :style="[deleteButtonStyle, isHoverDelete ? buttonHoverStyleDelete : {}]"
                    @mouseover="isHoverDelete = true"
                    @mouseleave="isHoverDelete = false"
                    @click="$emit('delete', category)"
                >
                    Hapus
                </button>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { Category } from '@/interfaces/Category'
import { theme } from '@/helpers/theme'
import {ref} from "vue"

const isHoverEdit = ref(false)
const isHoverDelete = ref(false)

const props = defineProps<{ category: Category }>()

const cardStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '16px',
    boxShadow: '0 1px 2px rgba(0, 0, 0, 0.1)',
    overflow: 'hidden',
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    padding: '16px',
    textAlign: 'center',
}

const imageStyle = {
    objectFit: 'cover',
    width: '80px',
    height: '80px',
    marginBottom: '12px',
}

const contentStyle = {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    gap: '8px',
}

const nameStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: 'bold',
    margin: '0',
}

const actionsStyle = {
    display: 'flex',
    gap: '8px',
}

const editButtonStyle = {
    backgroundColor: theme.colors.blue,
    color: theme.colors.whiteElement,
    padding: '6px',
    width: '88px',
    borderRadius: '6px',
    fontSize: theme.fonts.size.small,
    cursor: 'pointer',
    border: 'none',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
    transition: '0.2s ease-in-out',
}

const deleteButtonStyle = {
    backgroundColor: theme.colors.red,
    color: theme.colors.whiteElement,
    padding: '6px',
    width: '88px',
    borderRadius: '6px',
    fontSize: theme.fonts.size.small,
    cursor: 'pointer',
    border: 'none',
    boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
    transition: '0.2s ease-in-out',
}

const buttonHoverStyleEdit = {
    backgroundColor: '#226EAA',
    transform: 'scale(1.05)',
}

const buttonHoverStyleDelete = {
    backgroundColor: '#B5271D',
    transform: 'scale(1.05)',
}
</script>
