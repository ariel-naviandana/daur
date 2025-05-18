import axios from 'axios'
import { User } from '../interfaces/User'

export function useAuthApi() {
    const register = async (form: User): Promise<User | null> => {
        try {
            const config = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            }
            const response = await axios.post('/register', form, config)
            const user = response.data.user
            if (user) {
                window.location.href = user.role === 'master_admin' ? '/admin' : '/'
            }
            return user
        } catch (error) {
            console.error('Error registering user:', error)
            return null
        }
    }

    const login = async (credentials: { email: string; password: string }): Promise<User | null> => {
        try {
            const config = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            }
            const response = await axios.post('/login', credentials, config)
            const user = response.data.user
            if (user) {
                window.location.href = user.role === 'master_admin' || 'bank_admin' ? '/admin' : '/'
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
            const config = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            }
            await axios.post('/logout', {}, config)
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
            const response = await axios.get('/me')
            return response.data.user
        } catch (error) {
            console.error('Error fetching current user:', error)
            return null
        }
    }

    const updateProfile = async (data: { name?: string; address?: string; phone?: string; profile_picture?: string }): Promise<User | null> => {
        try {
            const config = {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            },
            }
            const response = await axios.put('/users', data, config)
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
