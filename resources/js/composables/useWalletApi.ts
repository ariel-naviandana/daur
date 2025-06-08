import apiClient from '../helpers/axios'
import { useCsrf } from './useCsrf'
import { Wallet } from '../interfaces/Wallet'

export function useWalletApi() {
    const { initCsrf } = useCsrf()

    const getWallets = async (): Promise<Wallet[]> => {
        try {
            const response = await apiClient.get('/wallets')
            return response.data
        } catch (error) {
            console.error('Error fetching wallets:', error)
            return []
        }
    }

    const getWallet = async (id: number): Promise<Wallet | null> => {
        try {
            const response = await apiClient.get(`/wallets/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching wallet:', error)
            return null
        }
    }

    const getWalletByUser = async (id: number): Promise<Wallet | null> => {
        try {
            const response = await apiClient.get(`/wallets-user/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching wallet:', error)
            return null
        }
    }

    const saveWallet = async (form: Wallet): Promise<boolean> => {
        try {
            await initCsrf()
            if (form.id) {
                await apiClient.put(`/wallets/${form.id}`, form)
            } else {
                await apiClient.post('/wallets', form)
            }
            return true
        } catch (error) {
            console.error('Error saving wallet:', error)
            return false
        }
    }

    const deleteWallet = async (id: number): Promise<boolean> => {
        try {
            await initCsrf()
            await apiClient.delete(`/wallets/${id}`)
            return true
        } catch (error) {
            console.error('Error deleting wallet:', error)
            return false
        }
    }

    return {
        getWallets,
        getWallet,
        getWalletByUser,
        saveWallet,
        deleteWallet,
    }
}
