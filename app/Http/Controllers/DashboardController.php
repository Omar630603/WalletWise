<?php

namespace App\Http\Controllers;

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

        $numberNewWallet = Auth::user()->wallets->count() + 1;
        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
        if ((($numberNewWallet % 100) >= 11) && (($numberNewWallet % 100) <= 13))
            $numberNewWallet = $numberNewWallet . 'th';
        else
            $numberNewWallet = $numberNewWallet . $ends[$numberNewWallet % 10];


        return view('dashboard', compact('walletTypes', 'currencies', 'symbols', 'numberNewWallet'));
    }
}
