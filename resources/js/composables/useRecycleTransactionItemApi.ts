import axios from 'axios'
import { RecycleTransactionItem } from '../interfaces/RecycleTransactionItem'

export function useRecycleTransactionItemApi() {
    const getRecycleTransactionItems = async (): Promise<RecycleTransactionItem[]> => {
        try {
            const response = await axios.get('/recycle-transaction-items')
            return response.data
        } catch (error) {
            console.error('Error fetching recycle transaction items:', error)
            return []
        }
    }

    const getRecycleTransactionItem = async (id: number): Promise<RecycleTransactionItem | null> => {
        try {
            const response = await axios.get(`/recycle-transaction-items/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching recycle transaction item:', error)
            return null
        }
    }

    const getRecycleTransactionItemsByTransaction = async (transactionId: number): Promise<RecycleTransactionItem[]> => {
        try {
            const response = await axios.get(`/recycle-transaction-items/transaction/${transactionId}`)
            return response.data
        } catch (error) {
            console.error('Error fetching recycle transaction items by transaction:', error)
            return []
        }
    }

    const saveRecycleTransactionItem = async (form: RecycleTransactionItem): Promise<boolean> => {
        try {
            const config = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            }
            if (form.id) {
                await axios.put(`/recycle-transaction-items/${form.id}`, form, config)
            } else {
                await axios.post('/recycle-transaction-items', form, config)
            }
            return true
        } catch (error) {
            console.error('Error saving recycle transaction item:', error)
            return false
        }
    }

    const deleteRecycleTransactionItem = async (id: number): Promise<boolean> => {
        try {
            await axios.delete(`/recycle-transaction-items/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            })
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
