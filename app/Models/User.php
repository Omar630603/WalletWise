<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'default_wallet_id',
        'google_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function getUserNameLettersAttribute()
    {
        $names = explode(' ', $this->name);
        $letters = array_map(function ($name) {
            return $name[0];
        }, $names);

        if (count($letters) > 2) {
            $letters = [$letters[0], end($letters)];
        }

        return strtoupper(implode('', $letters));
    }

    public function setDefaultWallet($id)
    {
        $this->default_wallet_id = $id;
        $this->save();
    }
}
