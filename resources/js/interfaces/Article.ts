export interface Article {
    id: number
    title: string
    content: string
    image_url?: string | null
    pewarta?: string | null
    editor?: string | null
    copyright?: string | null
    sumber?: string | null
    created_at?: string
    updated_at?: string
}
