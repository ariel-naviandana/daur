import axios from 'axios'
import { WalletTransaction } from '../interfaces/WalletTransaction'

export function useWalletTransactionApi() {
    const getWalletTransactions = async (): Promise<WalletTransaction[]> => {
        try {
            const response = await axios.get('/wallet_transactions')
            return response.data
        } catch (error) {
            console.error('Error fetching wallet transactions:', error)
            return []
        }
    }

    const getWalletTransaction = async (id: number): Promise<WalletTransaction | null> => {
        try {
            const response = await axios.get(`/wallet_transactions/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching wallet transaction:', error)
            return null
        }
    }

    const saveWalletTransaction = async (form: WalletTransaction): Promise<boolean> => {
        try {
            const config = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            }
            if (form.id) {
                await axios.put(`/wallet_transactions/${form.id}`, form, config)
            } else {
                await axios.post('/wallet_transactions', form, config)
            }
            return true
        } catch (error) {
            console.error('Error saving wallet transaction:', error)
            return false
        }
    }

    const deleteWalletTransaction = async (id: number): Promise<boolean> => {
        try {
            await axios.delete(`/wallet_transactions/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            })
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
