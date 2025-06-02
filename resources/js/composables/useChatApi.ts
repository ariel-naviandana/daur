import apiClient from '../helpers/axios'
import { useCsrf } from './useCsrf'
import { Chat } from '../interfaces/Chat'

export function useChatApi() {
    const { initCsrf } = useCsrf()

    const getChats = async (): Promise<Chat[]> => {
        try {
            const response = await apiClient.get('/chats')
            return response.data
        } catch (error) {
            console.error('Error fetching chats:', error)
            return []
        }
    }

    const getChat = async (id: number): Promise<Chat | null> => {
        try {
            const response = await apiClient.get(`/chats/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching chat:', error)
            return null
        }
    }

    const saveChat = async (form: Chat): Promise<boolean> => {
        try {
            await initCsrf()
            if (form.id) {
                await apiClient.put(`/chats/${form.id}`, form)
            } else {
                await apiClient.post('/chats', form)
            }
            return true
        } catch (error) {
            console.error('Error saving chat:', error)
            return false
        }
    }

    const deleteChat = async (id: number): Promise<boolean> => {
        try {
            await initCsrf()
            await apiClient.delete(`/chats/${id}`)
            return true
        } catch (error) {
            console.error('Error deleting chat:', error)
            return false
        }
    }

    return {
        getChats,
        getChat,
        saveChat,
        deleteChat,
    }
}
