import {User} from "./User"

export interface Chat {
    id: number
    sender_id: number
    receiver_id: number
    message?: string | null
    image_url?: string | null
    sent_at?: string
    sender?: User
    receiver?: User
}
