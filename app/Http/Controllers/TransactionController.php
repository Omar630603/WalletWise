<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'transaction_type' => 'required|in:expense,income,internal_transfer,borrow,lend',
                'category' => 'required_if:transaction_type,expense,income',
                'to_wallet' => 'required_if:transaction_type,internal_transfer',
                'fee' => 'nullable|numeric',
                'amount' => 'required|numeric',
                'date' => 'required|date',
                'description' => 'nullable|string',
                'wallet_id' => 'required|exists:wallets,id,user_id,' . $request->user()->id,
            ]);

            // $wallet = $request->user()->wallets()->find($request->wallet_id);
            // if ($request->transaction_type == 'expense' && $wallet->balance < $request->amount) {
            //     return redirect()->route('dashboard')->with(['status' => 'transaction-not-created', 'message' => 'Transaction creation failed, insufficient balance']);
            // }
            // if ($request->transaction_type == 'internal_transfer' && $wallet->balance < $request->amount) {
            //     return redirect()->route('dashboard')->with(['status' => 'transaction-not-created', 'message' => 'Transaction creation failed, insufficient balance']);
            // }
            // if ($request->transaction_type == 'borrow' && $request->borrow_lend_return == true) {
            //     if ($wallet->balance < $request->amount) {
            //         return redirect()->route('dashboard')->with(['status' => 'transaction-not-created', 'message' => 'Transaction creation failed, insufficient balance']);
            //     }
            // }
            // if ($request->transaction_type == 'lend' && $request->borrow_lend_return == false) {
            //     if ($wallet->balance < $request->amount) {
            //         return redirect()->route('dashboard')->with(['status' => 'transaction-not-created', 'message' => 'Transaction creation failed, insufficient balance']);
            //     }
            // }

            $wallet = $request->user()->wallets()->find($request->wallet_id);

            $transactionTypesRequiringBalance = ['expense', 'internal_transfer'];
            $shouldCheckBalance = in_array($request->transaction_type, $transactionTypesRequiringBalance)
                || ($request->transaction_type == 'borrow' && $request->borrow_lend_return == true)
                || ($request->transaction_type == 'lend' && $request->borrow_lend_return == false);

            if ($shouldCheckBalance && $wallet->balance < $request->amount) {
                return redirect()->route('dashboard')->with(['status' => 'transaction-not-created', 'message' => 'Transaction creation failed, insufficient balance']);
            }
        } catch (\Exception $e) {
            dd($e->getMessage(), $request->all());
            return redirect()->route('dashboard')->with(['status' => 'transaction-not-created', 'message' => 'Transaction creation failed ' . $e->getMessage()]);
        }

        $date = date('Y-m-d', strtotime($request->date));

        if ($date == date('Y-m-d')) {
            $date = date('Y-m-d H:i:s');
        }

        if ($request->transaction_type == 'expense') {
            $request->user()->transactions()->create([
                'amountOut' => $request->amount,
                'from_wallet_id' => $request->wallet_id,
                'category_id' => $request->category,
                'description' => $request->description,
                'date' => $date,
            ]);
            $wallet->balance -= $request->amount;
            $wallet->save();
        } elseif ($request->transaction_type == 'income') {
            $request->user()->transactions()->create([
                'amountIn' => $request->amount,
                'to_wallet_id' => $request->wallet_id,
                'category_id' => $request->category,
                'description' => $request->description,
                'date' => $date,
            ]);
            $wallet->balance += $request->amount;
            $wallet->save();
        } elseif ($request->transaction_type == 'internal_transfer') {
            $internalTransferCategory = Category::where('name', Category::DEFAULT_CATEGORIES['internal_transfer']['name'])->first();
            $transaction = $request->user()->transactions()->create([
                'amountOut' => $request->amount,
                'amountIn' => $request->amount,
                'category_id' => $internalTransferCategory->id,
                'from_wallet_id' => $request->wallet_id,
                'to_wallet_id' => $request->to_wallet,
                'description' => $request->description,
                'date' => $date,
            ]);
            if ($request->fee) {
                $feeCategory = Category::where('name', Category::DEFAULT_CATEGORIES['fees']['name'])->first();
                $request->user()->transactions()->create([
                    'amountOut' => $request->fee,
                    'from_wallet_id' => $request->wallet_id,
                    'category_id' =>    $feeCategory->id,
                    'parent_id' => $transaction->id,
                    'description' => 'Transfer fee',
                    'date' => $date,
                ]);
                $wallet->balance -= $request->amount + $request->fee;
            } else {
                $wallet->balance -= $request->amount;
            }
            $toWallet = $request->user()->wallets()->find($request->to_wallet);
            $toWallet->balance += $request->amount;
            $toWallet->save();
            $wallet->save();
        } elseif ($request->transaction_type == 'borrow') {
            $barrowCategory = Category::where('name', Category::DEFAULT_CATEGORIES['borrow']['name'])->first();
            if ($request->borrow_lend_return == true) {
                $request->user()->transactions()->create([
                    'amountOut' => $request->amount,
                    'from_wallet_id' => $request->wallet_id,
                    'category_id' => $barrowCategory->id,
                    'description' => $request->description,
                    'date' => $date,
                ]);
                $wallet->balance -= $request->amount;
            } else {
                $request->user()->transactions()->create([
                    'amountIn' => $request->amount,
                    'to_wallet_id' => $request->wallet_id,
                    'category_id' => $barrowCategory->id,
                    'description' => $request->description,
                    'date' => $date,
                ]);
                $wallet->balance += $request->amount;
            }
            $wallet->save();
        } elseif ($request->transaction_type == 'lend') {
            $lendCategory = Category::where('name', Category::DEFAULT_CATEGORIES['lend']['name'])->first();
            if ($request->borrow_lend_return == true) {
                $request->user()->transactions()->create([
                    'amountIn' => $request->amount,
                    'to_wallet_id' => $request->wallet_id,
                    'category_id' => $lendCategory->id,
                    'description' => $request->description,
                    'date' => $date,
                ]);
                $wallet->balance += $request->amount;
            } else {
                $request->user()->transactions()->create([
                    'amountOut' => $request->amount,
                    'from_wallet_id' => $request->wallet_id,
                    'category_id' => $lendCategory->id,
                    'description' => $request->description,
                    'date' => $date,
                ]);
                $wallet->balance -= $request->amount;
            }
            $wallet->save();
        }

        return redirect()->route('dashboard')->with(['status' => 'transaction-created', 'message' => 'Transaction created successfully']);
    }
}
