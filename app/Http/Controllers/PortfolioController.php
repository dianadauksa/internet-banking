<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\UserCrypto;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $account = $user->accounts()->where('name', 'CRYPTO')->first();
        $userCoins = UserCrypto::where('account_id', $account->id)->get();
        $ownedValue = 0;
        $ownedInvested = 0;
        $shortedPrice = 0;
        $shortedFor = 0;
        foreach ($userCoins as $userCoin) {
            if ($userCoin->amount > 0) {
                $ownedValue += $userCoin->amount * $userCoin->getCrypto()->price;
                $ownedInvested += $userCoin->amount * $userCoin->avg_price;
            } else {
                $shortedPrice += -$userCoin->amount * $userCoin->getCrypto()->price;
                $shortedFor += -$userCoin->amount * $userCoin->avg_price;
            }
        }

        return view('crypto.portfolio', [
            'userCoins' => $userCoins,
            'ownedValue' => $ownedValue,
            'ownedInvested' => $ownedInvested,
            'shortedPrice' => $shortedPrice,
            'shortedFor' => $shortedFor]);
    }
}
