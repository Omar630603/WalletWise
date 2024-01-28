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
            $numberNewWallet = $this->ordinal($numberNewWallet);


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
            $chartData = [];

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
                $chartData = $this->getChartData($current_month, $current_year, $defaultCurrency);

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

            $transactionTypes = [
                ['label' => 'Expense', 'value' => 'expense'],
                ['label' => 'Income', 'value' => 'income'],
            ];

            $excludedCategories = [
                Category::DEFAULT_CATEGORIES['internal_transfer']['name'],
                Category::DEFAULT_CATEGORIES['borrow']['name'],
                Category::DEFAULT_CATEGORIES['lend']['name'],
                Category::DEFAULT_CATEGORIES['initiate_wallet']['name'],
            ];

            $defaultCategories = Category::where('user_id', null)->whereNotIn('name', $excludedCategories)->get();
            $userCategories = $user->categories;

            $categories = [
                'Default Categories' => $defaultCategories,
                'Your Categories' => $userCategories,
            ];

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
                'defaultCurrency',
                'transactionTypes',
                'categories',
                'chartData'
            ));
        } catch (\Exception $e) {
            throw $e;
            // return redirect()->route('dashboard');
        }
    }

    private function ordinal($number)
    {
        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
        if ((($number % 100) >= 11) && (($number % 100) <= 13))
            return $number . 'th';
        else
            return $number . $ends[$number % 10];
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

    private function getChartData($month, $year, $currency): array
    {
        $user = User::find(Auth::user()->id);

        $total_expenses = 0;
        $total_incomes = 0;
        $chart_option = "";
        $total_expenses_array = [];
        $total_incomes_array = [];
        $periods = [];

        try {
            $userTransactions = $user->transactions()->with('category', 'toWallet', 'fromWallet')
                ->where(function ($query) use ($currency) {
                    $query->whereHas('toWallet', function ($query) use ($currency) {
                        $query->where('currency', $currency);
                    })->orWhereHas('fromWallet', function ($query) use ($currency) {
                        $query->where('currency', $currency);
                    });
                });

            $chart_option = request('chart-option') ?? "all-month";
            if (!in_array($chart_option, ['all-month', 'all-year', '01', '02', '03', '04'])) {
                $chart_option = "all-month";
            }
            $periods = $this->getPeriod($chart_option, $month, $year);

            if ($chart_option == "all-year") {
                foreach ($periods as $period) {
                    $expenses = $this->calculateTotal(clone $userTransactions, $period, $year, 'amountOut');
                    $incomes = $this->calculateTotal(clone $userTransactions, $period, $year, 'amountIn');
                    // if ($expenses == 0 && $incomes == 0) {
                    //     $periods = array_diff($periods, [$period]);
                    //     continue;
                    // }
                    $total_expenses_array[] = $expenses;
                    $total_incomes_array[] = $incomes;
                }

                $periods = array_map(function ($periods) use ($year) {
                    return date('j M Y', strtotime($periods . ' ' . $year));
                }, $periods);

                $chart_option = "This Year";
            } else {
                foreach ($periods as $period) {
                    $transactions_expenses = clone $userTransactions;
                    $transactions_incomes = clone $userTransactions;
                    $expenses = $transactions_expenses->where('date', '>=', $period)->where('date', '<=', date('Y-m-d', strtotime($period . ' +1 day')))->where('amountOut', '>', 0)->sum('amountOut');
                    $incomes = $transactions_incomes->where('date', '>=', $period)->where('date', '<=', date('Y-m-d', strtotime($period . ' +1 day')))->where('amountIn', '>', 0)->sum('amountIn');
                    // if ($expenses == 0 && $incomes == 0) {
                    //     $periods = array_diff($periods, [$period]);
                    //     continue;
                    // }
                    $total_expenses_array[] = $expenses;
                    $total_incomes_array[] = $incomes;
                }

                $periods = array_map(function ($periods) {
                    return date('j M', strtotime($periods));
                }, $periods);

                $chart_option = $chart_option == "all-month" ? "This Month" : $this->ordinal((int) $chart_option) . " Week";
            }

            $total_expenses = array_sum($total_expenses_array);
            $total_incomes = array_sum($total_incomes_array);
        } catch (\Exception $e) {
            throw $e;
        }

        $results = [
            'total_expenses' => Currencies::getSymbol($currency) . ' ' . number_format($total_expenses, 2, '.', ','),
            'total_incomes' => Currencies::getSymbol($currency) . ' ' . number_format($total_incomes, 2, '.', ','),
            'chart_option' => $chart_option,
            'total_expenses_array' => $total_expenses_array,
            'total_incomes_array' => $total_incomes_array,
            'periods' => $periods,
        ];

        return $results;
    }

    private function getPeriod($option, $month, $year): array
    {
        $periods = [];
        switch ($option) {
            case 'all-year':
                for ($i = 1; $i <= 12; $i++) {
                    $periods[] = date('M', strtotime($year . '-' . $i . '-01'));
                }
                break;
            case 'all-month':
                $days = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($month)), date('Y', strtotime($year)));
                for ($i = 1; $i <= $days; $i++) {
                    $periods[] = date('Y-m-d', strtotime($year . '-' . date('m', strtotime($month)) . '-' . $i));
                }
                break;
            case '01':
                for ($i = 1; $i <= 7; $i++) {
                    $periods[] = date('Y-m-d', strtotime($year . '-' . date('m', strtotime($month)) . '-' . $i));
                }
                break;
            case '02':
                for ($i = 8; $i <= 14; $i++) {
                    $periods[] = date('Y-m-d', strtotime($year . '-' . date('m', strtotime($month)) . '-' . $i));
                }
                break;
            case '03':
                for ($i = 15; $i <= 21; $i++) {
                    $periods[] = date('Y-m-d', strtotime($year . '-' . date('m', strtotime($month)) . '-' . $i));
                }
                break;
            case '04':
                for ($i = 22; $i <= cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($month)), date('Y', strtotime($year))); $i++) {
                    $periods[] = date('Y-m-d', strtotime($year . '-' . date('m', strtotime($month)) . '-' . $i));
                }
                break;
        }
        return $periods;
    }
}
