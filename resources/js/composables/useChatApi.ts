import axios from 'axios'
import { Chat } from '../interfaces/Chat'

export function useChat() {
    const getChats = async (): Promise<Chat[]> => {
        try {
            const response = await axios.get('/chats')
            return response.data
        } catch (error) {
            console.error('Error fetching chats:', error)
            return []
        }
    }

    const getChat = async (id: number): Promise<Chat | null> => {
        try {
            const response = await axios.get(`/chats/${id}`)
            return response.data
        } catch (error) {
            console.error('Error fetching chat:', error)
            return null
        }
    }

    const saveChat = async (form: Chat): Promise<boolean> => {
        try {
            const config = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            }
            if (form.id) {
                await axios.put(`/chats/${form.id}`, form, config)
            } else {
                await axios.post('/chats', form, config)
            }
            return true
        } catch (error) {
            console.error('Error saving chat:', error)
            return false
        }
    }

    const deleteChat = async (id: number): Promise<boolean> => {
        try {
            await axios.delete(`/chats/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            })
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
