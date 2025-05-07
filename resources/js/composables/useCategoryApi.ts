import axios from 'axios'
import { Category } from '../interfaces/Category'

export function useCategoryApi() {
    const getCategories = async (): Promise<Category[]> => {
        try {
            const response = await axios.get('/categories')
            return response.data
        } catch (error) {
            console.error('Error fetching categories:', error)
            return []
        }
    }

    const getCategory = async (id: number): Promise<Category | null> => {
        try {
            const response = await axios.get(`/categories/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching category:', error)
            return null
        }
    }

    const saveCategory = async (form: Category): Promise<boolean> => {
        try {
            const config = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            }
            if (form.id) {
                await axios.put(`/categories/${form.id}`, form, config)
            } else {
                await axios.post('/categories', form, config)
            }
            return true
        } catch (error) {
            console.error('Error saving category:', error)
            return false
        }
    }

    const deleteCategory = async (id: number): Promise<boolean> => {
        try {
            await axios.delete(`/categories/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            })
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
