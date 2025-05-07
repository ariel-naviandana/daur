import {WasteType} from "./WasteType"

export interface Category {
    id: number
    name: string
    image?: string | null
    created_at?: string
    updated_at?: string
    wasteTypes?: WasteType[]
}
