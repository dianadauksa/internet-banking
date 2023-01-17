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
        $invested = 0;
        foreach($userCoins as $userCoin) {
            $crypto = $userCoin->getCrypto();
            $value += $userCoin->amount * $crypto->price;
            $invested += $userCoin->amount * $userCoin->avg_price;
        }
        return view('crypto.portfolio', ['userCoins' => $userCoins, 'value' => $value, 'invested' => $invested]);
    }
}
