import axios from 'axios'
import { User } from '../interfaces/User'

export function useUserApi() {
    const getUsers = async (): Promise<User[]> => {
        try {
            const response = await axios.get('/users')
            return response.data
        } catch (error) {
            console.error('Error fetching users:', error)
            return []
        }
    }

    const getUser = async (id: number): Promise<User | null> => {
        try {
            const response = await axios.get(`/users/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching user:', error)
            return null
        }
    }

    const saveUser = async (form: User): Promise<boolean> => {
        try {
            const config = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            }
            if (form.id) {
                await axios.put(`/users/${form.id}`, form, config)
            } else {
                await axios.post('/users', form, config)
            }
            return true
        } catch (error) {
            console.error('Error saving user:', error)
            return false
        }
    }

    const deleteUser = async (id: number): Promise<boolean> => {
        try {
            await axios.delete(`/users/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            })
            return true
        } catch (error) {
            console.error('Error deleting user:', error)
            return false
        }
    }

    const getAdmins = async (): Promise<User[]> => {
        try {
            const response = await axios.get('/admins')
            return response.data
        } catch (error) {
            console.error('Error fetching admins:', error)
            return []
        }
    }

    return {
        getUsers,
        getUser,
        saveUser,
        deleteUser,
        getAdmins,
    }
}
