export interface User {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    address: string | null;
    profile_picture: string | null;
    role: 'user' | 'admin';
    created_at: string;
    updated_at: string;
}
