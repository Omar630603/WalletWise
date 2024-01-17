<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
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

            if ($defaultWallet) {
                $userWallets = $user->wallets->sortBy('created_at');

                if (request('default_wallet') != null) {
                    switch (request('default_wallet')) {
                        case 'next':
                            $defaultWallet = $userWallets->where('id', '>', $defaultWallet->id)->first();
                            if ($defaultWallet === null) {
                                $defaultWallet = $userWallets->first();
                            }
                            break;
                        case 'prev':
                            $defaultWallet = $userWallets->where('id', '<', $defaultWallet->id)->last();
                            if ($defaultWallet === null) {
                                $defaultWallet = $userWallets->last();
                            }
                            break;
                    }
                }

                $user->setDefaultWallet($defaultWallet->id);
                $defaultWallet_type_label = Wallet::TYPES[array_search($defaultWallet->type, array_column(Wallet::TYPES, 'value'))]['label'];
                $defaultWallet_formatted_balance = Currencies::getSymbol($defaultWallet->currency) . ' ' . number_format($defaultWallet->balance, 2, '.', ',');

                $defaultWallet_number =  $userWallets->search(function ($wallet) use ($defaultWallet) {
                    return $wallet->id == $defaultWallet->id;
                }) + 1;

                if (request('wallet') == 'all') {
                    $defaultWallet_transactions = $user->transactions()
                        ->whereYear('date', $current_year)
                        ->whereMonth('date', date('m', strtotime($current_month)))
                        ->get()
                        ->sortByDesc('date');
                } else if (request('wallet') != null) {
                    $wallet = Wallet::where([['id', request('wallet')], ['user_id', $user->id]])->firstOrFail();
                    $defaultWallet_transactions = $wallet->transactionsFrom()
                        ->whereYear('date', $current_year)->whereMonth('date', date('m', strtotime($current_month)))
                        ->get()
                        ->merge($wallet->transactionsTo()
                            ->whereYear('date', $current_year)
                            ->whereMonth('date', date('m', strtotime($current_month)))
                            ->get())
                        ->sortByDesc('date');
                } else {
                    $defaultWallet_transactions = $defaultWallet->transactionsFrom()
                        ->whereYear('date', $current_year)
                        ->whereMonth('date', date('m', strtotime($current_month)))
                        ->get()
                        ->merge($defaultWallet->transactionsTo()
                            ->whereYear('date', $current_year)
                            ->whereMonth('date', date('m', strtotime($current_month)))
                            ->get())
                        ->sortByDesc('date');
                }
            }

            $summary_formatted = [];
            if ($defaultWallet) {
                $summary_formatted = $this->getSummaryData($current_month, $current_year, $defaultWallet, $user);
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
                'defaultWallet_number'
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

    private function calculateTotal($transactions, $startDate, $endDate, $amountType)
    {
        return $transactions->where('date', '>=', $startDate)
            ->where('date', '<', $endDate)
            ->where($amountType, '>', 0)
            ->sum($amountType);
    }

    private function getSummaryData($month, $year, $wallet, $user): array
    {
        $lastMonthStartDate = date('Y-m-d', strtotime($year . '-' . $month . '-01 -1 month'));
        $lastMonthEndDate = date('Y-m-t', strtotime($year . '-' . $month . '-01 -1 month'));

        $currentMonthStartDate = date('Y-m-d', strtotime($year . '-' . $month . '-01'));
        $currentMonthEndDate = date('Y-m-t', strtotime($year . '-' . $month . '-01'));

        $lastMonthExpense = $this->calculateTotal($user->transactions(), $lastMonthStartDate, $lastMonthEndDate, 'amountOut');
        $lastMonthIncome = $this->calculateTotal($user->transactions(), $lastMonthStartDate, $lastMonthEndDate, 'amountIn');

        $currentMonthExpense = $this->calculateTotal($user->transactions(), $currentMonthStartDate, $currentMonthEndDate, 'amountOut');
        $currentMonthIncome = $this->calculateTotal($user->transactions(), $currentMonthStartDate, $currentMonthEndDate, 'amountIn');

        $lastMonthWalletExpense = $this->calculateTotal($wallet->transactionsFrom(), $lastMonthStartDate, $lastMonthEndDate, 'amountOut');
        $lastMonthWalletIncome = $this->calculateTotal($wallet->transactionsTo(), $lastMonthStartDate, $lastMonthEndDate, 'amountIn');

        $currentMonthWalletExpense = $this->calculateTotal($wallet->transactionsFrom(), $currentMonthStartDate, $currentMonthEndDate, 'amountOut');
        $currentMonthWalletIncome = $this->calculateTotal($wallet->transactionsTo(), $currentMonthStartDate, $currentMonthEndDate, 'amountIn');

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

        $walletExpensePercentage = $currentMonthWalletExpense > 0 ? round(($currentMonthWalletExpense / $currentMonthExpense) * 100, 2) : 0;
        $walletIncomePercentage = $currentMonthWalletIncome > 0 ? round(($currentMonthWalletIncome / $currentMonthIncome) * 100, 2) : 0;

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
