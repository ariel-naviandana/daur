import apiClient from '../helpers/axios'
import { useCsrf } from './useCsrf'
import { RecycleTransactionItem } from '../interfaces/RecycleTransactionItem'

export function useRecycleTransactionItemApi() {
    const { initCsrf } = useCsrf()

    const getRecycleTransactionItems = async (): Promise<RecycleTransactionItem[]> => {
        try {
            const response = await apiClient.get('/recycle-transaction-items')
            return response.data
        } catch (error) {
            console.error('Error fetching recycle transaction items:', error)
            return []
        }
    }

    const getRecycleTransactionItem = async (id: number): Promise<RecycleTransactionItem | null> => {
        try {
            const response = await apiClient.get(`/recycle-transaction-items/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching recycle transaction item:', error)
            return null
        }
    }

    const getRecycleTransactionItemsByTransaction = async (transactionId: number): Promise<RecycleTransactionItem[]> => {
        try {
            const response = await apiClient.get(`/recycle-transaction-items/transaction/${transactionId}`)
            return response.data
        } catch (error) {
            console.error('Error fetching recycle transaction items by transaction:', error)
            return []
        }
    }

    const saveRecycleTransactionItem = async (form: RecycleTransactionItem): Promise<boolean> => {
        try {
            await initCsrf()
            if (form.id) {
                await apiClient.put(`/recycle-transaction-items/${form.id}`, form)
            } else {
                await apiClient.post('/recycle-transaction-items', form)
            }
            return true
        } catch (error) {
            console.error('Error saving recycle transaction item:', error)
            return false
        }
    }

    const deleteRecycleTransactionItem = async (id: number): Promise<boolean> => {
        try {
            await initCsrf()
            await apiClient.delete(`/recycle-transaction-items/${id}`)
            return true
        } catch (error) {
            console.error('Error deleting recycle transaction item:', error)
            return false
        }
    }

    return {
        getRecycleTransactionItems,
        getRecycleTransactionItem,
        getRecycleTransactionItemsByTransaction,
        saveRecycleTransactionItem,
        deleteRecycleTransactionItem,
    }
}
