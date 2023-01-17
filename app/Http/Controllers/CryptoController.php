<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CryptoCoin;
use App\Models\CryptoTransaction;
use App\Models\UserCrypto;
use App\Repositories\CoinMarketCapRepository;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CryptoController extends Controller
{
    protected CoinMarketCapRepository $coinMarketCapRepository;

    public function __construct(CoinMarketCapRepository $coinMarketCapRepository)
    {
        $this->coinMarketCapRepository = $coinMarketCapRepository;
    }

    public function index(): View
    {
        $coins = Cache::get('coins');
        if ($coins === null) {
            $coins = $this->coinMarketCapRepository->getData();
            Cache::put('coins', $coins, 1200);
        }
        $account = auth()->user()->accounts()->where('name', 'CRYPTO')->first();
        return view('crypto.main', ['account' => $account, 'coins' => $coins]);
    }

    public function show(string $symbol)
    {
        $coin = Cache::get('coins')->where('symbol', $symbol)->first();
        if ($coin === null) {
            $coin = $this->coinMarketCapRepository->getSingle($symbol);
        }
        $account = auth()->user()->accounts()->where('name', 'CRYPTO')->first();
        return view('crypto.single', ['account' => $account, 'coin' => $coin]);
    }

    public function buy(string $symbol, Request $request): RedirectResponse
    {
        $coin = Cache::get('coins')->where('symbol', $symbol)->first();
        if ($coin === null) {
            $coin = $this->coinMarketCapRepository->getSingle($symbol);
        }
        $amount = $request->amount;
        $account = auth()->user()->accounts()->where('name', 'CRYPTO')->first();
        if ($account->balance < $amount*$coin->price) {
            return Redirect::back()->with('error', 'Not enough money for the purchase');
        }
        $rules = [
            'amount' => 'required|numeric|min:1|max:' . $account->balance/$coin->price,
        ];
        $this->validate($request, $rules);

        $userCoin = UserCrypto::where('account_id', $account->id)->where('coin', $coin->symbol)->first();
        if($userCoin === null) {
            $userCoin = new UserCrypto();
            $userCoin->account_id = $account->id;
            $userCoin->coin = $coin->symbol;
            $userCoin->amount = $amount;
            $userCoin->avg_price = $coin->price;
        } else {
            $userCoin->amount += $amount;
            $userCoin->avg_price = ($userCoin->avg_price*$userCoin->amount + $amount*$coin->price)/($userCoin->amount + $amount);
        }
        $userCoin->save();

        $account->balance -= $amount*$coin->price;
        $account->save();
        $this->recordBuyCryptoTransaction($account, $coin, $amount);

        return Redirect::back()->with('status', 'Purchase successful');
    }

    private function recordBuyCryptoTransaction(Account $account, CryptoCoin $coin, int $amount)
    {
        $transaction = new CryptoTransaction();
        $transaction->account_id = $account->id;
        $transaction->coin = $coin->symbol;
        $transaction->amount = $amount;
        $transaction->price = $coin->price;
        $transaction->total = $amount*$coin->price;
        $transaction->type = 'BUY';
        $transaction->save();
    }
}
