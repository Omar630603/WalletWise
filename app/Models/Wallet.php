<?php

namespace App\Models;

use Symfony\Component\Intl\Currencies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet extends Model
{
    use HasFactory;

    const TYPES = [
        ['value' => 'cash', 'label' => 'Cash'],
        ['value' => 'debit_card', 'label' => 'Debit Card'],
        ['value' => 'credit_card', 'label' => 'Credit Card'],
        ['value' => 'application', 'label' => 'Application'],
        ['value' => 'other', 'label' => 'Other'],
    ];

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'currency',
        'balance',
        'minimum_balance',
        'icon',
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
