import {User} from "./User"
import {Bank} from "./Bank"
import {RecycleTransactionItem} from "./RecycleTransactionItem"

export interface RecycleTransaction {
    id: number
    user_id: number
    bank_id?: number | null
    pickup_address?: string | null
    latitude?: number | null
    longitude?: number | null
    appointment_time: string
    note?: string | null
    method: 'pickup' | 'dropoff'
    status: 'waiting' | 'process' | 'cancel' | 'success'
    total_quantity?: number | null
    total_amount: number
    created_at?: string
    updated_at?: string
    user?: User
    bank?: Bank | null
    items?: RecycleTransactionItem[]
}
