import axios from 'axios'
import { WasteType } from '../interfaces/WasteType'

export function useWasteType() {
    const getWasteTypes = async (): Promise<WasteType[]> => {
        try {
            const response = await axios.get('/waste-types')
            return response.data
        } catch (error) {
            console.error('Error fetching waste types:', error)
            return []
        }
    }

    const getWasteType = async (id: number): Promise<WasteType | null> => {
        try {
            const response = await axios.get(`/waste-types/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching waste type:', error)
            return null
        }
    }

    const getWasteTypesByCategory = async (categoryId: number): Promise<WasteType[]> => {
        try {
            const response = await axios.get(`/waste-types/category/${categoryId}`)
            return response.data
        } catch (error) {
            console.error('Error fetching waste types by category:', error)
            return []
        }
    }

    const saveWasteType = async (form: WasteType): Promise<boolean> => {
        try {
            const config = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            }
            if (form.id) {
                await axios.put(`/waste-types/${form.id}`, form, config)
            } else {
                await axios.post('/waste-types', form, config)
            }
            return true
        } catch (error) {
            console.error('Error saving waste type:', error)
            return false
        }
    }

    const deleteWasteType = async (id: number): Promise<boolean> => {
        try {
            await axios.delete(`/waste-types/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            })
            return true
        } catch (error) {
            console.error('Error deleting waste type:', error)
            return false
        }
    }

    return {
        getWasteTypes,
        getWasteType,
        getWasteTypesByCategory,
        saveWasteType,
        deleteWasteType,
    }
}
