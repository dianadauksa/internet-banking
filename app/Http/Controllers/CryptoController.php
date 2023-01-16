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
        return view('crypto.main', ['account' => $account, 'cryptoCoins' => $mostPopularCoins]);
    }

}
