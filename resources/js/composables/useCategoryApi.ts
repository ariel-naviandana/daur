import apiClient from '../helpers/axios'
import { useCsrf } from './useCsrf'
import { Category } from '../interfaces/Category'

export function useCategoryApi() {
    const { initCsrf } = useCsrf()

    const getCategories = async (): Promise<Category[]> => {
        try {
            const response = await apiClient.get('/categories')
            return response.data
        } catch (error) {
            console.error('Error fetching categories:', error)
            return []
        }
    }

    const getCategory = async (id: number): Promise<Category | null> => {
        try {
            const response = await apiClient.get(`/categories/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching category:', error)
            return null
        }
    }

    const saveCategory = async (form: Category): Promise<boolean> => {
        try {
            await initCsrf()
            if (form.id) {
                await apiClient.put(`/categories/${form.id}`, form)
            } else {
                await apiClient.post('/categories', form)
            }
            return true
        } catch (error) {
            console.error('Error saving category:', error)
            return false
        }
    }

    const deleteCategory = async (id: number): Promise<boolean> => {
        try {
            await initCsrf()
            await apiClient.delete(`/categories/${id}`)
            return true
        } catch (error) {
            console.error('Error deleting category:', error)
            return false
        }
    }

    return {
        getCategories,
        getCategory,
        saveCategory,
        deleteCategory,
    }
}
