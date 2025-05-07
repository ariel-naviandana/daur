import {RecycleTransaction} from "./RecycleTransaction"
import {WasteType} from "./WasteType"

export interface RecycleTransactionItem {
    id: number
    transaction_id: number
    waste_type_id: number
    quantity: number
    sub_total: number
    created_at?: string
    updated_at?: string
    transaction?: RecycleTransaction
    wasteType?: WasteType
}
