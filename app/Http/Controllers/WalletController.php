<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\NewWalletRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Intl\Currencies;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function store(NewWalletRequest $request)
    {
        DB::beginTransaction();

        try {
            $request->validated();
            $wallet = $request->user()->wallets()->create([
                'name' => $request->name,
                'type' => $request->type,
                'currency' => $request->currency,
                'balance' => $request->initial_balance,
                'icon' => $request->icon . " " . $request->color,
            ]);

            $request->user()->setDefaultWallet($wallet->id);

            $category = Category::where('name', Category::DEFAULT_CATEGORIES['initiate_wallet']['name'])->first();

            if (!$category) {
                throw new \Exception('Category not found');
            }

            if ($wallet->balance > 0) {
                $str_balance = Currencies::getSymbol($wallet->currency) . ' ' . number_format($wallet->balance, 2, '.', ',');
                $request->user()->transactions()->create([
                    'amountIn' => $wallet->balance,
                    'to_wallet_id' => $wallet->id,
                    'category_id' => $category->id,
                    'description' => 'Initial Balance for ' . $wallet->name . ' wallet with initial balance of ' . $str_balance,
                    'date' => now(),
                ]);
            }

            DB::commit();
            return  Redirect::route('dashboard')->with(['status' => 'wallet-created', 'message' => "Wallet {$wallet->name} created successfully"]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            return  Redirect::route('dashboard')->with(['status' => 'wallet-creation-failed', 'message' => "Wallet {$wallet->name} creation failed"]);
        }
    }
}
