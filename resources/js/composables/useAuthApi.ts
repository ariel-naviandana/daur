import apiClient from '../helpers/axios'
import { useCsrf } from './useCsrf'
import { User } from '../interfaces/User'

export function useAuthApi() {
    const { initCsrf } = useCsrf()

    const register = async (form: User): Promise<User | null> => {
        try {
            await initCsrf()
            const response = await apiClient.post('/register', form)
            window.location.href = '/login'
            return response.data.user
        } catch (error) {
            console.error('Error registering user:', error)
            return null
        }
    }

    const login = async (credentials: { email: string; password: string }): Promise<User | null> => {
        try {
            await initCsrf()
            const response = await apiClient.post('/login', credentials)
            const user = response.data.user
            if (user) {
                window.location.href = user.role === 'master_admin' || user.role === 'bank_admin' ? '/admin' : '/'
                window.history.pushState({}, '', window.location.href)
            }
            return user
        } catch (error) {
            console.error('Error logging in:', error)
            return null
        }
    }

    const logout = async (): Promise<boolean> => {
        try {
            await initCsrf()
            await apiClient.post('/logout')
            window.location.href = '/login'
            window.history.pushState({}, '', '/login')
            return true
        } catch (error) {
            console.error('Error logging out:', error)
            return false
        }
    }

    const getCurrentUser = async (): Promise<User | null> => {
        try {
            const response = await apiClient.get('/me')
            return response.data.user
        } catch (error) {
            console.error('Error fetching current user:', error)
            return null
        }
    }

    const updateProfile = async (data: { name?: string; address?: string; phone?: string; profile_picture?: string }): Promise<User | null> => {
        try {
            await initCsrf()
            const response = await apiClient.put('/users', data)
            return response.data.user
        } catch (error) {
            console.error('Error updating profile:', error)
            return null
        }
    }

    return {
        register,
        login,
        logout,
        getCurrentUser,
        updateProfile,
    }
}
