<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CryptoCoin;
use App\Repositories\CoinMarketCapRepository;
use Illuminate\Http\RedirectResponse;
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
}
