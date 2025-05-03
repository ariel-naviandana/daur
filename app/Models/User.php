<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'address', 'profile_picture', 'role'
    ];

    protected $hidden = ['password'];

    public function recycleTransactions(): HasMany
    {
        return $this->hasMany(RecycleTransaction::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function sentChats()
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    public function receivedChats()
    {
        return $this->hasMany(Chat::class, 'receiver_id');
    }
}

