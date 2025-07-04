import {RecycleTransaction} from "./RecycleTransaction"

export interface Bank {
    id: number
    name: string
    address?: string | null
    latitude?: number | null
    longitude?: number | null
    phone?: string | null
    created_at?: string
    updated_at?: string
    recycleTransactions?: RecycleTransaction[]
}
