<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Intl\Currencies;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {

            $currencies = array_filter(Currencies::getNames('en'), function ($name) {
                return strpos($name, '(') === false;
            });

            $symbols = [];
            foreach ($currencies as $code => $name) {
                $symbols[$code] = Currencies::getSymbol($code);
            }

            $walletTypes = Wallet::TYPES;
            $user = User::find(Auth::user()->id);

            $numberNewWallet = $user->wallets->count() + 1;

            $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
            if ((($numberNewWallet % 100) >= 11) && (($numberNewWallet % 100) <= 13))
                $numberNewWallet = $numberNewWallet . 'th';
            else
                $numberNewWallet = $numberNewWallet . $ends[$numberNewWallet % 10];


            $defaultWallet = Wallet::where('id', $user->default_wallet_id)->first();
            $defaultWallet_type_label = '';
            $defaultWallet_formatted_balance = '';

            if (!$defaultWallet) {
                $defaultWallet = Wallet::where('user_id', $user->id)->first();
            }

            $defaultWallet_transactions = [];

            $current_year = request('year') ?? date('Y');
            $current_month = request('month') ?? date('M');

            if (!in_array($current_year, range(2000, 2100))) {
                $current_year = date('Y');
            }

            if (!in_array($current_month, ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Nov', 'Dec'])) {
                $current_month = date('M');
            }

            $defaultWallet_number = 0;
            $defaultCurrency = '';
            $summary_formatted = [];
            $userCurrencies = [];

            if ($defaultWallet) {
                $userWallets = $user->wallets->sortBy('created_at');
                $defaultCurrency = request('currency') ?? $defaultWallet->currency;

                $userCurrencies = $userWallets->pluck('currency')->unique()->toArray();
                if (request('currency') != $defaultWallet->currency && request('currency') != null) {
                    $defaultWallet = $userWallets->where('currency', $defaultCurrency)->first();
                }

                if (request('default_wallet') != null) {
                    switch (request('default_wallet')) {
                        case 'next':
                            $defaultWallet = $userWallets->where('currency', $defaultCurrency)->where('id', '>', $defaultWallet->id)->first();
                            if ($defaultWallet === null) {
                                $defaultWallet = $userWallets->where('currency', $defaultCurrency)->first();
                            }
                            break;
                        case 'prev':
                            $defaultWallet = $userWallets->where('currency', $defaultCurrency)->where('id', '<', $defaultWallet->id)->last();
                            if ($defaultWallet === null) {
                                $defaultWallet = $userWallets->where('currency', $defaultCurrency)->last();
                            }
                            break;
                    }
                    $user->setDefaultWallet($defaultWallet->id);
                    return redirect()->route('dashboard');
                }

                foreach ($userWallets->where('currency', $defaultCurrency)->values() as $key => $wallet) {
                    if ($wallet->id == $defaultWallet->id) {
                        $defaultWallet_number = $key + 1;
                        break;
                    }
                }

                if (request('wallet') != null && request('wallet') != 'all') {
                    $wallet = Wallet::where([['id', request('wallet')], ['user_id', $user->id]])->firstOrFail();
                    $defaultWallet_transactions = $wallet->transactionsFrom()->with('category', 'toWallet', 'fromWallet')
                        ->whereYear('date', $current_year)->whereMonth('date', date('m', strtotime($current_month)))
                        ->get()
                        ->merge($wallet->transactionsTo()->with('category', 'toWallet', 'fromWallet')
                            ->whereYear('date', $current_year)
                            ->whereMonth('date', date('m', strtotime($current_month)))
                            ->get())
                        ->sortByDesc('date');
                    $defaultWallet = $wallet;
                } else {
                    $defaultWallet_transactions = $user->transactions()->with('category', 'toWallet', 'fromWallet')
                        ->where(function ($query) use ($defaultCurrency) {
                            $query->whereHas('toWallet', function ($query) use ($defaultCurrency) {
                                $query->where('currency', $defaultCurrency);
                            })->orWhereHas('fromWallet', function ($query) use ($defaultCurrency) {
                                $query->where('currency', $defaultCurrency);
                            });
                        })
                        ->whereYear('date', $current_year)
                        ->whereMonth('date', date('m', strtotime($current_month)))
                        ->get()
                        ->sortByDesc('date');
                }

                $user->setDefaultWallet($defaultWallet->id);
                $defaultWallet_type_label = Wallet::TYPES[array_search($defaultWallet->type, array_column(Wallet::TYPES, 'value'))]['label'];
                $defaultWallet_formatted_balance = Currencies::getSymbol($defaultWallet->currency) . ' ' . number_format($defaultWallet->balance, 2, '.', ',');
                $summary_formatted = $this->getSummaryData($current_month, $current_year, $defaultWallet, $user, $defaultCurrency);

                $page = request('page', 1);
                $perPage = 5;
                $offset = ($page * $perPage) - $perPage;

                $paginator = new LengthAwarePaginator(
                    array_slice($defaultWallet_transactions->toArray(), $offset, $perPage, true),
                    count($defaultWallet_transactions),
                    $perPage,
                    $page,
                    ['path' => request()->url(), 'query' => request()->query()]
                );

                $defaultWallet_transactions = $paginator;
            }

            return view('dashboard', compact(
                'walletTypes',
                'currencies',
                'symbols',
                'numberNewWallet',
                'defaultWallet',
                'defaultWallet_type_label',
                'defaultWallet_formatted_balance',
                'defaultWallet_transactions',
                'current_year',
                'current_month',
                'summary_formatted',
                'defaultWallet_number',
                'userCurrencies',
                'defaultCurrency'
            ));
        } catch (\Exception $e) {
            throw $e;
            // return redirect()->route('dashboard');
        }
    }

    private function calculatePercentageDifference($current, $last)
    {
        if ($last > 0) {
            return round((($current - $last) / $last) * 100, 2);
        } else {
            return $current > 0 ? 100 : 0;
        }
    }

    private function calculateTotal($transactions, $month, $year, $amountType)
    {
        $internal_transfer_category = Category::where('name', Category::DEFAULT_CATEGORIES['internal_transfer']['name'])->first();
        return $transactions->whereYear('date', $year)
            ->whereMonth('date', date('m', strtotime($month)))
            ->where($amountType, '>', 0)
            ->where('category_id', '!=', $internal_transfer_category->id)
            ->sum($amountType);
    }

    private function getSummaryData($month, $year, $wallet, $user, $defaultCurrency): array
    {
        $lastMonth = date('M', strtotime($year . '-' . $month . '-01 -1 month'));
        $lastMonthYear = date('Y', strtotime($year . '-' . $month . '-01 -1 month'));

        $userTransactions = $user->transactions()->with('category', 'toWallet', 'fromWallet')
            ->where(function ($query) use ($defaultCurrency) {
                $query->whereHas('toWallet', function ($query) use ($defaultCurrency) {
                    $query->where('currency', $defaultCurrency);
                })->orWhereHas('fromWallet', function ($query) use ($defaultCurrency) {
                    $query->where('currency', $defaultCurrency);
                });
            });

        $lastMonthExpense = $this->calculateTotal(clone $userTransactions, $lastMonth, $lastMonthYear, 'amountOut');
        $lastMonthIncome = $this->calculateTotal(clone $userTransactions, $lastMonth, $lastMonthYear, 'amountIn');

        $currentMonthExpense = $this->calculateTotal(clone $userTransactions, $month, $year, 'amountOut');
        $currentMonthIncome = $this->calculateTotal(clone $userTransactions, $month, $year, 'amountIn');

        $lastMonthWalletExpense = $this->calculateTotal($wallet->transactionsFrom(), $lastMonth, $lastMonthYear, 'amountOut');
        $lastMonthWalletIncome = $this->calculateTotal($wallet->transactionsTo(), $lastMonth, $lastMonthYear, 'amountIn');

        $currentMonthWalletExpense = $this->calculateTotal($wallet->transactionsFrom(), $month, $year, 'amountOut');
        $currentMonthWalletIncome = $this->calculateTotal($wallet->transactionsTo(), $month, $year, 'amountIn');

        // $lastMonthExpense = 5350000;
        // $lastMonthIncome = 2500000;
        // $currentMonthExpense = 3500000;
        // $currentMonthIncome = 6500000;
        // $lastMonthWalletExpense = 2000000;
        // $lastMonthWalletIncome = 2200000;
        // $currentMonthWalletExpense = 3000000;
        // $currentMonthWalletIncome = 6000000;

        $expenseDifferencePercentage = $this->calculatePercentageDifference($currentMonthExpense, $lastMonthExpense);
        $incomeDifferencePercentage = $this->calculatePercentageDifference($currentMonthIncome, $lastMonthIncome);

        $walletExpenseDifferencePercentage = $this->calculatePercentageDifference($currentMonthWalletExpense, $lastMonthWalletExpense);
        $walletIncomeDifferencePercentage = $this->calculatePercentageDifference($currentMonthWalletIncome, $lastMonthWalletIncome);

        $walletExpensePercentage = $currentMonthExpense > 0 ? round(($currentMonthWalletExpense / $currentMonthExpense) * 100, 2) : 0;
        $walletIncomePercentage = $currentMonthIncome > 0 ? round(($currentMonthWalletIncome / $currentMonthIncome) * 100, 2) : 0;

        return [
            'lastMonthExpense' => Currencies::getSymbol($wallet->currency) . ' ' . number_format($lastMonthExpense, 2, '.', ','),
            'lastMonthIncome' => Currencies::getSymbol($wallet->currency) . ' ' . number_format($lastMonthIncome, 2, '.', ','),
            'currentMonthExpense' => Currencies::getSymbol($wallet->currency) . ' ' . number_format($currentMonthExpense, 2, '.', ','),
            'currentMonthIncome' => Currencies::getSymbol($wallet->currency) . ' ' . number_format($currentMonthIncome, 2, '.', ','),
            'expenseDifferencePercentage' => $expenseDifferencePercentage . '%',
            'incomeDifferencePercentage' => $incomeDifferencePercentage . '%',

            'lastMonthWalletExpense' => Currencies::getSymbol($wallet->currency) . ' ' . number_format($lastMonthWalletExpense, 2, '.', ','),
            'lastMonthWalletIncome' => Currencies::getSymbol($wallet->currency) . ' ' . number_format($lastMonthWalletIncome, 2, '.', ','),
            'currentMonthWalletExpense' => Currencies::getSymbol($wallet->currency) . ' ' . number_format($currentMonthWalletExpense, 2, '.', ','),
            'currentMonthWalletIncome' => Currencies::getSymbol($wallet->currency) . ' ' . number_format($currentMonthWalletIncome, 2, '.', ','),
            'walletExpenseDifferencePercentage' => $walletExpenseDifferencePercentage . '%',
            'walletIncomeDifferencePercentage' => $walletIncomeDifferencePercentage . '%',

            'walletExpensePercentage' => round($walletExpensePercentage) . '%',
            'walletIncomePercentage' => round($walletIncomePercentage) . '%',
        ];
    }
}
