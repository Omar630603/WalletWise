<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'balance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionsFrom()
    {
        return $this->hasMany(Transaction::class, 'from_wallet_id');
    }

    public function transactionsTo()
    {
        return $this->hasMany(Transaction::class, 'to_wallet_id');
    }
}
