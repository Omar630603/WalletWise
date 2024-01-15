<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    const DEFAULT_CATEGORIES = [
        'initiate_wallet' => [
            ['name' => 'Initiate Wallet', 'icon' => 'fa-plus'],
        ]
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
