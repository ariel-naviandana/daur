import apiClient from '../helpers/axios'
import { useCsrf } from './useCsrf'
import { User } from '../interfaces/User'

export function useUserApi() {
    const { initCsrf } = useCsrf()

    const getUsers = async (): Promise<User[]> => {
        try {
            const response = await apiClient.get('/users')
            return response.data
        } catch (error) {
            console.error('Error fetching users:', error)
            return []
        }
    }

    const getUser = async (id: number): Promise<User | null> => {
        try {
            const response = await apiClient.get(`/users/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching user:', error)
            return null
        }
    }

    const saveUser = async (form: User): Promise<boolean> => {
        try {
            await initCsrf()
            if (form.id) {
                await apiClient.put(`/users/${form.id}`, form)
            } else {
                await apiClient.post('/users', form)
            }
            return true
        } catch (error) {
            console.error('Error saving user:', error)
            return false
        }
    }

    const deleteUser = async (id: number): Promise<boolean> => {
        try {
            await initCsrf()
            await apiClient.delete(`/users/${id}`)
            return true
        } catch (error) {
            console.error('Error deleting user:', error)
            return false
        }
    }

    const getAdmins = async (): Promise<User[]> => {
        try {
            const response = await apiClient.get('/admins')
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
