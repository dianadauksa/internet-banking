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

    public function show(string $symbol): View
    {
        $coin = Cache::get('coins')->where('symbol', $symbol)->first();
        if ($coin === null) {
            $coin = $this->coinMarketCapRepository->getSingle($symbol);
        }
        $account = auth()->user()->accounts()->where('name', 'CRYPTO')->first();
        $userCoin = UserCrypto::where('account_id', $account->id)->where('coin', $symbol)->first();
        $transactions = CryptoTransaction::where('account_id', $account->id)->where('coin', $symbol)->get();
        $transactions = $transactions->sortByDesc('created_at');
        return view('crypto.single', [
            'account' => $account,
            'coin' => $coin,
            'userCoin' => $userCoin,
            'transactions' => $transactions]);
    }

    public function buy(string $symbol, Request $request): RedirectResponse
    {
        $coin = Cache::get('coins')->where('symbol', $symbol)->first();
        if ($coin === null) {
            $coin = $this->coinMarketCapRepository->getSingle($symbol);
        }
        $amount = $request->amount;
        $account = auth()->user()->accounts()->where('name', 'CRYPTO')->first();
        if ($account->balance < $amount * $coin->price) {
            return Redirect::back()->with('error', 'Not enough money for the purchase');
        }
        $rules = [
            'amount' => 'required|numeric|min:1|max:' . $account->balance / $coin->price,
        ];
        $this->validate($request, $rules);

        $userCoin = UserCrypto::where('account_id', $account->id)->where('coin', $coin->symbol)->first();
        if ($userCoin === null) {
            $userCoin = new UserCrypto();
            $userCoin->account_id = $account->id;
            $userCoin->coin = $coin->symbol;
            $userCoin->amount = $amount;
            $userCoin->avg_price = $coin->price;
        } else {
            $userCoin->amount += $amount;
            $userCoin->avg_price = ($userCoin->avg_price * $userCoin->amount + $amount * $coin->price) / ($userCoin->amount + $amount);
        }
        $userCoin->save();

        $account->balance -= $amount * $coin->price;
        $account->save();
        $this->recordBuyCryptoTransaction($account, $coin, $amount);

        return Redirect::back()->with(
            'status',
            'Purchase successful. You bought ' . $amount . ' ' . $coin->symbol . ' for $ ' . number_format($amount * $coin->price,2)
        );
    }

    public function sell(string $symbol, Request $request): RedirectResponse
    {
        $coin = Cache::get('coins')->where('symbol', $symbol)->first();
        if ($coin === null) {
            $coin = $this->coinMarketCapRepository->getSingle($symbol);
        }
        $amount = $request->amount;
        $account = auth()->user()->accounts()->where('name', 'CRYPTO')->first();
        $userCoin = UserCrypto::where('account_id', $account->id)->where('coin', $coin->symbol)->first();
        if ($userCoin === null || $userCoin->amount < $amount) {
            return Redirect::back()->with('error', 'Not enough coins for the sale');
        }
        $rules = [
            'amount' => 'required|numeric|min:1|max:' . $userCoin->amount,
        ];
        $this->validate($request, $rules);

        $userCoin->amount -= $amount;
        $userCoin->save();
        if ($userCoin->amount === 0) {
            $userCoin->delete();
        }

        $account->balance += $amount * $coin->price;
        $account->save();
        $this->recordSellCryptoTransaction($account, $coin, $amount);

        return Redirect::back()->with(
            'status',
            'Sale successful. You sold ' . $amount . ' ' . $coin->symbol . ' for $ ' . number_format($amount * $coin->price,2)
        );
    }

    private function recordBuyCryptoTransaction(Account $account, CryptoCoin $coin, int $amount): void
    {
        $transaction = new CryptoTransaction();
        $transaction->account_id = $account->id;
        $transaction->coin = $coin->symbol;
        $transaction->amount = $amount;
        $transaction->price = $coin->price;
        $transaction->total = $amount * $coin->price;
        $transaction->type = 'BUY';
        $transaction->save();
    }

    private function recordSellCryptoTransaction(Account $account, CryptoCoin $coin, int $amount): void
    {
        $transaction = new CryptoTransaction();
        $transaction->account_id = $account->id;
        $transaction->coin = $coin->symbol;
        $transaction->amount = $amount;
        $transaction->price = $coin->price;
        $transaction->total = $amount * $coin->price;
        $transaction->type = 'SELL';
        $transaction->save();
    }
}
