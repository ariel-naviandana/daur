import axios from 'axios'
import { Bank } from '../interfaces/Bank'

export function useBank() {
    const getBanks = async (): Promise<Bank[]> => {
        try {
            const response = await axios.get('/banks')
            return response.data
        } catch (error) {
            console.error('Error fetching banks:', error)
            return []
        }
    }

    const getBank = async (id: number): Promise<Bank | null> => {
        try {
            const response = await axios.get(`/banks/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching bank:', error)
            return null
        }
    }

    const saveBank = async (form: Bank): Promise<boolean> => {
        try {
            const config = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            }
            if (form.id) {
                await axios.put(`/banks/${form.id}`, form, config)
            } else {
                await axios.post('/banks', form, config)
            }
            return true
        } catch (error) {
            console.error('Error saving bank:', error)
            return false
        }
    }

    const deleteBank = async (id: number): Promise<boolean> => {
        try {
            await axios.delete(`/banks/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            })
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
