<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'transaction_type' => 'required|in:expense,income',
                'category' => 'required|exists:categories,id',
                'amount' => 'required|numeric',
                'date' => 'required|date',
                'description' => 'nullable|string',
                'wallet_id' => 'required|exists:wallets,id,user_id,' . $request->user()->id,
            ]);

            $wallet = $request->user()->wallets()->find($request->wallet_id);
            if ($request->transaction_type == 'expense' && $wallet->balance < $request->amount) {
                return redirect()->route('dashboard')->with(['status' => 'transaction-not-created', 'message' => 'Transaction creation failed, insufficient balance']);
            }
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with(['status' => 'transaction-not-created', 'message' => 'Transaction creation failed ' . $e->getMessage()]);
        }

        $date = date('Y-m-d', strtotime($request->date));
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
        } else {
            $request->user()->transactions()->create([
                'amountIn' => $request->amount,
                'to_wallet_id' => $request->wallet_id,
                'category_id' => $request->category,
                'description' => $request->description,
                'date' => $date,
            ]);
            $wallet->balance += $request->amount;
            $wallet->save();
        }

        return redirect()->route('dashboard')->with(['status' => 'transaction-created', 'message' => 'Transaction created successfully']);
    }
}
