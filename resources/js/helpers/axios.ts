import axios, { AxiosInstance } from 'axios'

const apiClient: AxiosInstance = axios.create({
    baseURL: '/',
    withCredentials: true,
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
    },
})

apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401 || error.response?.status === 403) {
            window.location.href = '/login'
        }
        return Promise.reject(error)
    }
)

export const initializeCsrf = async (): Promise<void> => {
    try {
        await apiClient.get('/sanctum/csrf-cookie')
    } catch (error) {
        console.error('Error initializing CSRF token:', error)
    }
}

export default apiClient
