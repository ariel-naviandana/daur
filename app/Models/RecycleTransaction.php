<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecycleTransaction extends Model
{
    protected $fillable = [
        'user_id', 'bank_id', 'pickup_address', 'method',
        'status', 'total_amount', 'total_quantity', 'appointment_time', 'note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function items()
    {
        return $this->hasMany(RecycleTransactionItem::class, 'transaction_id');
    }
}
