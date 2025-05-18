<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    protected $fillable = ['name', 'address', 'phone'];

    public function recycleTransactions(): HasMany
    {
        return $this->hasMany(RecycleTransaction::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
