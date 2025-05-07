import {WalletTransaction} from "./WalletTransaction"
import {User} from "./User"

export interface Wallet {
    id: number
    user_id: number
    balance: number
    created_at?: string
    updated_at?: string
    user?: User
    transactions?: WalletTransaction[]
}
