import apiClient from '../helpers/axios'
import { useCsrf } from './useCsrf'
import { WasteType } from '../interfaces/WasteType'

export function useWasteTypeApi() {
    const { initCsrf } = useCsrf()

    const getWasteTypes = async (): Promise<WasteType[]> => {
        try {
            const response = await apiClient.get('/waste-types')
            return response.data
        } catch (error) {
            console.error('Error fetching waste types:', error)
            return []
        }
    }

    const getWasteType = async (id: number): Promise<WasteType | null> => {
        try {
            const response = await apiClient.get(`/waste-types/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching waste type:', error)
            return null
        }
    }

    const getWasteTypesByCategory = async (categoryId: number): Promise<WasteType[]> => {
        try {
            const response = await apiClient.get(`/waste-types/category/${categoryId}`)
            return response.data
        } catch (error) {
            console.error('Error fetching waste types by category:', error)
            return []
        }
    }

    const saveWasteType = async (form: WasteType): Promise<boolean> => {
        try {
            await initCsrf()
            if (form.id) {
                await apiClient.put(`/waste-types/${form.id}`, form)
            } else {
                await apiClient.post('/waste-types', form)
            }
            return true
        } catch (error) {
            console.error('Error saving waste type:', error)
            return false
        }
    }

    const deleteWasteType = async (id: number): Promise<boolean> => {
        try {
            await initCsrf()
            await apiClient.delete(`/waste-types/${id}`)
            return true
        } catch (error) {
            console.error('Error deleting waste type:', error)
            return false
        }
    }

    return {
        getWasteTypes,
        getWasteType,
        getWasteTypesByCategory,
        saveWasteType,
        deleteWasteType,
    }
}
