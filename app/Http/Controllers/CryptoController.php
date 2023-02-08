<?php

namespace App\Http\Controllers;

use App\Models\{Account, CryptoCoin, CryptoTransaction, UserCrypto};
use App\Repositories\CoinMarketCapRepository;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Cache, Redirect};
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
            Cache::put('coins', $coins, now()->addMinutes(120));
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

    public function statements(Account $account): View
    {
        if ($account->user_id !== auth()->user()->id) {
            return abort('403');
        }
        $transactions = CryptoTransaction::where('account_id', $account->id)->get();
        $transactions = $transactions->sortByDesc('created_at');
        return view('crypto.statements', ['account' => $account, 'transactions' => $transactions]);
    }

    public function filterStatements(Account $account, Request $request): View
    {
        if ($account->user_id !== auth()->user()->id) {
            return abort('403');
        }
        $transactions = CryptoTransaction::where('account_id', $account->id)->get();

        if ($request->coin) {
            $transactions = $transactions->where('coin', strtoupper($request->coin));
        }

        if ($request->from || $request->to) {
            $transactions = $transactions->filter(function ($transaction) use ($request) {
                if ($request->from && $request->to) {
                    return ($transaction->created_at->format('Y-m-d') >= $request->from
                        && $transaction->created_at->format('Y-m-d') <= $request->to);
                } elseif ($request->from) {
                    return ($transaction->created_at->format('Y-m-d') >= $request->from);
                }
                return ($transaction->created_at->format('Y-m-d') <= $request->to);
            });
        }

        if ($request->type && $request->type !== 'all') {
            $transactions = $transactions->filter(function ($transaction) use ($request) {
                return (str_contains($transaction->type, strtoupper($request->type)));
            });
        }

        $transactions = $transactions->sortByDesc('created_at');
        return view('crypto.statements', ['account' => $account, 'transactions' => $transactions]);
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
