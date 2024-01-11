<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Requests\NewWalletRequest;
use Illuminate\Support\Facades\Redirect;

class WalletController extends Controller
{
    public function store(NewWalletRequest $request)
    {
        $request->validated();

        $wallet = new Wallet();
        $wallet->user_id = $request->user()->id;
        $wallet->name = $request->name;
        $wallet->type = $request->type;
        $wallet->currency = $request->currency;
        $wallet->balance = $request->initial_balance;
        $wallet->icon = $request->icon . " " . $request->color;
        $wallet->save();

        return  Redirect::route('dashboard')->with('status', 'wallet-created');
    }
}
