<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    const DEFAULT_CATEGORIES = [
        'initiate_wallet' => [
            'name' => 'Initiate Wallet',
            'icon' => 'fa-plus',
        ],
        'food' => [
            'name' => 'Food',
            'icon' => 'fa-utensils',
        ],
        'transportation' => [
            'name' => 'Transportation',
            'icon' => 'fa-bus',
        ],
        'shopping' => [
            'name' => 'Shopping',
            'icon' => 'fa-cart-shopping',
        ],
        'groceries' => [
            'name' => 'Groceries',
            'icon' => 'fa-basket-shopping',
        ],
        'housing' => [
            'name' => 'Housing',
            'icon' => 'fa-house',
        ],
        'entertainment' => [
            'name' => 'Entertainment',
            'icon' => 'fa-gamepad',
        ],
        'love' => [
            'name' => 'Love',
            'icon' => 'fa-heart',
        ],
        'health' => [
            'name' => 'Health',
            'icon' => 'fa-heart-pulse',
        ],
        'education' => [
            'name' => 'Education',
            'icon' => 'fa-graduation-cap',
        ],
        'other' => [
            'name' => 'Other',
            'icon' => 'fa-question',
        ],
        'salary' => [
            'name' => 'Salary',
            'icon' => 'fa-money-bill-wave',
        ],
        'borrow' => [
            'name' => 'Borrow',
            'icon' => 'fa-hand-holding-dollar',
        ],
        'lend' => [
            'name' => 'Lend',
            'icon' => 'fa-hand-holding-heart',
        ],
        'internal_transfer' => [
            'name' => 'Internal Transfer',
            'icon' => 'fa-arrow-right-arrow-left',
        ],
        'fees' => [
            'name' => 'Fees',
            'icon' => 'fa-file-invoice',
        ],
    ];

    protected $fillable = [
        'user_id',
        'name',
        'icon',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
