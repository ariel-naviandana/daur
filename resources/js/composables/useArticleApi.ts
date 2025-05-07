import axios from 'axios'
import { Article } from '../interfaces/Article'

export function useArticle() {
    const getArticles = async (search?: string, sort: 'newest' | 'oldest' = 'newest'): Promise<Article[]> => {
        try {
            const params = { search, sort }
            const response = await axios.get('/articles', { params })
            return response.data
        } catch (error) {
            console.error('Error fetching articles:', error)
            return []
        }
    }

    const getArticle = async (id: number): Promise<Article | null> => {
        try {
            const response = await axios.get(`/articles/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching article:', error)
            return null
        }
    }

    const saveArticle = async (form: Article): Promise<boolean> => {
        try {
            const config = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            }
            if (form.id) {
                await axios.put(`/articles/${form.id}`, form, config)
            } else {
                await axios.post('/articles', form, config)
            }
            return true
        } catch (error) {
            console.error('Error saving article:', error)
            return false
        }
    }

    const deleteArticle = async (id: number): Promise<boolean> => {
        try {
            await axios.delete(`/articles/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            })
            return true
        } catch (error) {
            console.error('Error deleting article:', error)
            return false
        }
    }

    return {
        getArticles,
        getArticle,
        saveArticle,
        deleteArticle,
    }
}
