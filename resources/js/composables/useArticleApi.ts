import apiClient from '../helpers/axios'
import { useCsrf } from './useCsrf'
import { Article } from '../interfaces/Article'

export function useArticleApi() {
    const { initCsrf } = useCsrf()

    const getArticles = async (search?: string, sort: 'newest' | 'oldest' = 'newest'): Promise<Article[]> => {
        try {
            const params = { search, sort }
            const response = await apiClient.get('/articles', { params })
            return response.data
        } catch (error) {
            console.error('Error fetching articles:', error)
            return []
        }
    }

    const getArticle = async (id: number): Promise<Article | null> => {
        try {
            const response = await apiClient.get(`/articles/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching article:', error)
            return null
        }
    }

    const saveArticle = async (form: Article): Promise<boolean> => {
        try {
            await initCsrf()
            if (form.id) {
                await apiClient.put(`/articles/${form.id}`, form)
            } else {
                await apiClient.post('/articles', form)
            }
            return true
        } catch (error) {
            console.error('Error saving article:', error)
            return false
        }
    }

    const deleteArticle = async (id: number): Promise<boolean> => {
        try {
            await initCsrf()
            await apiClient.delete(`/articles/${id}`)
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
