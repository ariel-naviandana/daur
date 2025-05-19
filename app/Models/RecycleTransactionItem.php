<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecycleTransactionItem extends Model
{
    protected $fillable = ['transaction_id', 'waste_type_id', 'quantity', 'sub_total', 'image'];

    public function transaction()
    {
        return $this->belongsTo(RecycleTransaction::class, 'transaction_id');
    }

    public function wasteType()
    {
        return $this->belongsTo(WasteType::class);
    }
}
