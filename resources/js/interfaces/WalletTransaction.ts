import {Wallet} from "./Wallet"

export interface WalletTransaction {
    id: number
    wallet_id: number
    amount: number
    method?: 'bank' | 'ewallet'
    account_info?: string
    type: 'deposit' | 'withdrawal'
    status: 'waiting' | 'approved' | 'rejected'
    created_at?: string
    updated_at?: string
    wallet?: Wallet
}
