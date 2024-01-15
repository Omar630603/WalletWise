<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Intl\Currencies;

class DashboardController extends Controller
{
    public function index()
    {

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

        if ($defaultWallet) {
            $user->setDefaultWallet($defaultWallet->id);
            $defaultWallet_type_label = Wallet::TYPES[array_search($defaultWallet->type, array_column(Wallet::TYPES, 'value'))]['label'];
            $defaultWallet_formatted_balance = Currencies::getSymbol($defaultWallet->currency) . ' ' . number_format($defaultWallet->balance, 2, '.', ',');
            $defaultWallet_transactions = $defaultWallet->transactionsFrom()->get()->merge($defaultWallet->transactionsTo()->get())->sortByDesc('created_at');
        }

        return view('dashboard', compact('walletTypes', 'currencies', 'symbols', 'numberNewWallet', 'defaultWallet', 'defaultWallet_type_label', 'defaultWallet_formatted_balance', 'defaultWallet_transactions'));
    }
}
