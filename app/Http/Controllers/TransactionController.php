<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        return view('transactions.index');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
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

            $wallet = $request->user()->wallets()->find($request->wallet_id);

            $transactionTypesRequiringBalance = ['expense', 'internal_transfer'];
            $shouldCheckBalance = in_array($request->transaction_type, $transactionTypesRequiringBalance)
                || ($request->transaction_type == 'borrow' && $request->borrow_lend_return == true)
                || ($request->transaction_type == 'lend' && $request->borrow_lend_return == false);

            if ($shouldCheckBalance && $wallet->balance < $request->amount) {
                return redirect()->route('dashboard')->with(['status' => 'transaction-not-created', 'message' => 'Transaction creation failed, insufficient balance']);
            }


            $date = date('Y-m-d', strtotime($request->date));

            if ($date == date('Y-m-d')) {
                $date = date('Y-m-d H:i:s');
            }

            if ($request->transaction_type == 'expense') {
                $this->createTransaction($request, null, $request->amount, $request->wallet_id, null, $request->category, null, $request->description, $date);
                $wallet->balance -= $request->amount;
                $wallet->save();
            } elseif ($request->transaction_type == 'income') {
                $this->createTransaction($request, $request->amount, null, null, $request->wallet_id, $request->category, null, $request->description, $date);
                $wallet->balance += $request->amount;
                $wallet->save();
            } elseif ($request->transaction_type == 'internal_transfer') {
                $internalTransferCategory = Category::where('name', Category::DEFAULT_CATEGORIES['internal_transfer']['name'])->first();
                $toWallet = $request->user()->wallets()->find($request->to_wallet);
                $transaction = $this->createTransaction($request, $request->amount, $request->amount, $request->wallet_id, $request->to_wallet, $internalTransferCategory->id, null, $request->description ?? "Internal transfer from " . $wallet->name . " to " . $toWallet->name . " wallet", $date);
                if ($request->fee) {
                    $feeCategory = Category::where('name', Category::DEFAULT_CATEGORIES['fees']['name'])->first();
                    $fee = $this->createTransaction($request, null, $request->fee, $request->wallet_id, null, $feeCategory->id, $transaction->id, 'Transfer fee', $date);
                    $wallet->balance -= $request->amount + $request->fee;
                    $transaction->child_id = $fee->id;
                    $transaction->save();
                } else {
                    $wallet->balance -= $request->amount;
                }
                $toWallet->balance += $request->amount;
                $toWallet->save();
                $wallet->save();
            } elseif ($request->transaction_type == 'borrow') {
                $barrowCategory = Category::where('name', Category::DEFAULT_CATEGORIES['borrow']['name'])->first();
                if ($request->borrow_lend_return == true) {
                    $this->createTransaction($request, null, $request->amount, $request->wallet_id, null, $barrowCategory->id, null, $request->description, $date);
                    $wallet->balance -= $request->amount;
                } else {
                    $this->createTransaction($request, $request->amount, null, null, $request->wallet_id, $barrowCategory->id, null, $request->description, $date);
                    $wallet->balance += $request->amount;
                }
                $wallet->save();
            } elseif ($request->transaction_type == 'lend') {
                $lendCategory = Category::where('name', Category::DEFAULT_CATEGORIES['lend']['name'])->first();
                if ($request->borrow_lend_return == true) {
                    $this->createTransaction($request, $request->amount, null, null, $request->wallet_id, $lendCategory->id, null, $request->description, $date);
                    $wallet->balance += $request->amount;
                } else {
                    $this->createTransaction($request, null, $request->amount, $request->wallet_id, null, $lendCategory->id, null, $request->description, $date);
                    $wallet->balance -= $request->amount;
                }
                $wallet->save();
            }
            DB::commit();
            return redirect()->route('dashboard')->with(['status' => 'transaction-created', 'message' => 'Transaction created successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('dashboard')->with(['status' => 'transaction-not-created', 'message' => 'Transaction creation failed ' . $e->getMessage()]);
        }
    }

    private function createTransaction($request, $amountIn = null, $amountOut = null, $fromWalletId = null, $toWalletId = null, $categoryId, $parentId = null, $description = null, $date)
    {
        $transaction = $request->user()->transactions()->create([
            'amountIn' => $amountIn,
            'amountOut' => $amountOut,
            'from_wallet_id' => $fromWalletId,
            'to_wallet_id' => $toWalletId,
            'category_id' => $categoryId,
            'parent_id' => $parentId,
            'description' => $description,
            'date' => $date,
        ]);
        return $transaction;
    }
}
