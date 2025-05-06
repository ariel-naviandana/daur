<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = ['wallet_id', 'amount', 'method', 'account_info', 'status', 'type'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
