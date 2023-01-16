<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Repositories\CoinMarketCapRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CryptoController extends Controller
{
    protected $coinMarketCapRepository;

    public function __construct(CoinMarketCapRepository $coinMarketCapRepository)
    {
        $this->coinMarketCapRepository = $coinMarketCapRepository;
    }
    public function index(): View
    {
        $cryptoCoins = $this->coinMarketCapRepository->getData();
        $mostPopularCoins = $cryptoCoins->sortByDesc('market_cap')->take(10);
        $account = auth()->user()->accounts()->where('name', 'CRYPTO')->first();
        var_dump($mostPopularCoins);
        return view('crypto.main', ['account' => $account, 'cryptoCoins' => $mostPopularCoins]);
    }

    /**
     * Add a new crypto bank account to the bank accounts table and link it to the user
     */
    public function store(): RedirectResponse
    {
        do {
            $prefix = 'LV77ORCL';
            $suffix = str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
            $number = $prefix . $suffix;
        } while (Account::where('number', $number)->exists());

        $newAccount = (new Account)->fill([
            'name' => 'CRYPTO',
            'number' => $number,
            'currency' => 'USD',
            'balance' => 0.00,
        ]);
        $newAccount->user()->associate(auth()->user());
        $newAccount->save();

        return Redirect::back()->with('status', 'account-created');
    }
}
