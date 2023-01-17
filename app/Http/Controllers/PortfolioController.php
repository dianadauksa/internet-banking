<?php

namespace App\Http\Controllers;

use App\Models\UserCrypto;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $account = $user->accounts()->where('name', 'CRYPTO')->first();
        $userCoins = UserCrypto::where('account_id', $account->id)->get();
        $value = 0;
        $profit = 0;
        foreach($userCoins as $userCoin) {
            $crypto = $userCoin->getCrypto();
            $value += $userCoin->amount * $crypto->price;
            $profit += $userCoin->amount * ($crypto->price - $userCoin->avg_price);
        }
        return view('crypto.portfolio', ['userCoins' => $userCoins, 'value' => $value, 'profit' => $profit]);
    }
}
