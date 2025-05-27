import apiClient from '../helpers/axios'
import { useCsrf } from './useCsrf'
import { Bank } from '../interfaces/Bank'

export function useBankApi() {
    const { initCsrf } = useCsrf()

    const getBanks = async (): Promise<Bank[]> => {
        try {
            const response = await apiClient.get('/banks')
            return response.data
        } catch (error) {
            console.error('Error fetching banks:', error)
            return []
        }
    }

    const getBank = async (id: number): Promise<Bank | null> => {
        try {
            const response = await apiClient.get(`/banks/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching bank:', error)
            return null
        }
    }

    const saveBank = async (form: Bank): Promise<boolean> => {
        try {
            await initCsrf()
            if (form.id) {
                await apiClient.put(`/banks/${form.id}`, form)
            } else {
                await apiClient.post('/banks', form)
            }
            return true
        } catch (error) {
            console.error('Error saving bank:', error)
            return false
        }
    }

    const deleteBank = async (id: number): Promise<boolean> => {
        try {
            await initCsrf()
            await apiClient.delete(`/banks/${id}`)
            return true
        } catch (error) {
            console.error('Error deleting bank:', error)
            return false
        }
    }

    return {
        getBanks,
        getBank,
        saveBank,
        deleteBank,
    }
}
