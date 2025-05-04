export interface RecycleTransaction {
    id: number;
    user_id: number;
    bank_id: number | null;
    pickup_address: string | null;
    appointment_time: string | null;
    note: string | null;
    method: 'pickup' | 'dropoff';
    status: 'waiting' | 'process' | 'cancel' | 'success';
    total_weight: number | null;
    total_amount: number;
    created_at: string;
    updated_at: string;
}
