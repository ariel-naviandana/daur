import apiClient from '../helpers/axios'
import { useCsrf } from './useCsrf'
import { WalletTransaction } from '../interfaces/WalletTransaction'

export function useWalletTransactionApi() {
    const { initCsrf } = useCsrf()

    const getWalletTransactions = async (): Promise<WalletTransaction[]> => {
        try {
            const response = await apiClient.get('/wallet-transactions')
            return response.data
        } catch (error) {
            console.error('Error fetching wallet transactions:', error)
            return []
        }
    }

    const getWalletTransaction = async (id: number): Promise<WalletTransaction | null> => {
        try {
            const response = await apiClient.get(`/wallet-transactions/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching wallet transaction:', error)
            return null
        }
    }

    const saveWalletTransaction = async (form: WalletTransaction): Promise<boolean> => {
        try {
            await initCsrf()
            if (form.id) {
                await apiClient.put(`/wallet-transactions/${form.id}`, form)
            } else {
                await apiClient.post('/wallet-transactions', form)
            }
            return true
        } catch (error) {
            console.error('Error saving wallet transaction:', error)
            return false
        }
    }

    const deleteWalletTransaction = async (id: number): Promise<boolean> => {
        try {
            await initCsrf()
            await apiClient.delete(`/wallet-transactions/${id}`)
            return true
        } catch (error) {
            console.error('Error deleting wallet transaction:', error)
            return false
        }
    }

    return {
        getWalletTransactions,
        getWalletTransaction,
        saveWalletTransaction,
        deleteWalletTransaction,
    }
}
