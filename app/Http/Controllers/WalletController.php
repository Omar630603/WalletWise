<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\NewWalletRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Intl\Currencies;
use Illuminate\Support\Facades\DB;
use App\Models\Wallet;
use Illuminate\Validation\Rule;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        return view('wallets.index');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'type' => ['required', Rule::in(array_column(Wallet::TYPES, 'value'))],
                'currency' => ['required'],
                'initial_balance' => ['required', 'numeric', 'min:0'],
                'icon' => ['nullable', 'string', 'max:255'],
                'color' => ['nullable', 'string', 'max:255'],
            ]);
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
            return  Redirect::route('dashboard')->with(['status' => 'wallet-not-created', 'message' => "Wallet {$request->name} creation failed " . $e->getMessage()]);
        }
    }
}
