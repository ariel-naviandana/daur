import axios from 'axios'
import { RecycleTransaction } from '../interfaces/RecycleTransaction'

export function useRecycleTransaction() {
    const getRecycleTransactions = async (): Promise<RecycleTransaction[]> => {
        try {
            const response = await axios.get('/recycle-transactions')
            return response.data
        } catch (error) {
            console.error('Error fetching recycle transactions:', error)
            return []
        }
    }

    const getRecycleTransaction = async (id: number): Promise<RecycleTransaction | null> => {
        try {
            const response = await axios.get(`/recycle-transactions/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching recycle transaction:', error)
            return null
        }
    }

    const getRecycleTransactionsByUser = async (userId: number): Promise<RecycleTransaction[]> => {
        try {
            const response = await axios.get(`/recycle-transactions/user/${userId}`)
            return response.data
        } catch (error) {
            console.error('Error fetching recycle transactions by user:', error)
            return []
        }
    }

    const saveRecycleTransaction = async (form: RecycleTransaction & { items: { waste_type_id: number; quantity: number; sub_total: number }[] }): Promise<boolean> => {
        try {
            const config = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            }
            if (form.id) {
                await axios.put(`/recycle-transactions/${form.id}`, form, config)
            } else {
                await axios.post('/recycle-transactions', form, config)
            }
            return true
        } catch (error) {
            console.error('Error saving recycle transaction:', error)
            return false
        }
    }

    const deleteRecycleTransaction = async (id: number): Promise<boolean> => {
        try {
            await axios.delete(`/recycle-transactions/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            })
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
