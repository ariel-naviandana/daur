import axios from 'axios'
import { Wallet } from '../interfaces/Wallet'

export function useWalletApi() {
    const getWallets = async (): Promise<Wallet[]> => {
        try {
            const response = await axios.get('/wallets')
            return response.data
        } catch (error) {
            console.error('Error fetching wallets:', error)
            return []
        }
    }

    const getWallet = async (id: number): Promise<Wallet | null> => {
        try {
            const response = await axios.get(`/wallets/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching wallet:', error)
            return null
        }
    }

    const saveWallet = async (form: Wallet): Promise<boolean> => {
        try {
            const config = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            }
            if (form.id) {
                await axios.put(`/wallets/${form.id}`, form, config)
            } else {
                await axios.post('/wallets', form, config)
            }
            return true
        } catch (error) {
            console.error('Error saving wallet:', error)
            return false
        }
    }

    const deleteWallet = async (id: number): Promise<boolean> => {
        try {
            await axios.delete(`/wallets/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            })
            return true
        } catch (error) {
            console.error('Error deleting wallet:', error)
            return false
        }
    }

    return {
        getWallets,
        getWallet,
        saveWallet,
        deleteWallet,
    }
}
