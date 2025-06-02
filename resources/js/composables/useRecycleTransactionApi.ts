import apiClient from '../helpers/axios'
import { useCsrf } from './useCsrf'
import { RecycleTransaction } from '../interfaces/RecycleTransaction'

export function useRecycleTransactionApi() {
    const { initCsrf } = useCsrf()

    const getRecycleTransactions = async (): Promise<RecycleTransaction[]> => {
        try {
            const response = await apiClient.get('/recycle-transactions')
            return response.data
        } catch (error) {
            console.error('Error fetching recycle transactions:', error)
            return []
        }
    }

    const getRecycleTransaction = async (id: number): Promise<RecycleTransaction | null> => {
        try {
            const response = await apiClient.get(`/recycle-transactions/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching recycle transaction:', error)
            return null
        }
    }

    const getRecycleTransactionsByUser = async (userId: number): Promise<RecycleTransaction[]> => {
        try {
            const response = await apiClient.get(`/recycle-transactions/user/${userId}`)
            return response.data
        } catch (error) {
            console.error('Error fetching recycle transactions by user:', error)
            return []
        }
    }

    const saveRecycleTransaction = async (form: RecycleTransaction & { items: { waste_type_id: number; quantity: number; sub_total: number }[] }): Promise<boolean> => {
        try {
            await initCsrf()
            if (form.id) {
                await apiClient.put(`/recycle-transactions/${form.id}`, form)
            } else {
                await apiClient.post('/recycle-transactions', form)
            }
            return true
        } catch (error) {
            console.error('Error saving recycle transaction:', error)
            return false
        }
    }

    const deleteRecycleTransaction = async (id: number): Promise<boolean> => {
        try {
            await initCsrf()
            await apiClient.delete(`/recycle-transactions/${id}`)
            return true
        } catch (error) {
            console.error('Error deleting recycle transaction:', error)
            return false
        }
    }

    return {
        getRecycleTransactions,
        getRecycleTransaction,
        getRecycleTransactionsByUser,
        saveRecycleTransaction,
        deleteRecycleTransaction,
    }
}
