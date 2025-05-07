import {Category} from "./Category"

export interface WasteType {
    id: number
    category_id: number
    name: string
    unit: string
    price_per_unit: number
    image?: string | null
    created_at?: string
    updated_at?: string
    waste_type?: Category
}
