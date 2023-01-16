<?php

namespace App\Http\Controllers;

use App\Models\Account;
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
        $cryptoCoins = Cache::get('cryptoCoins');
        if ($cryptoCoins === null) {
            $cryptoCoins = $this->coinMarketCapRepository->getData();
            Cache::put('cryptoCoins', $cryptoCoins, 120);
        }
        $account = auth()->user()->accounts()->where('name', 'CRYPTO')->first();
        return view('crypto.main', ['account' => $account, 'cryptoCoins' => $cryptoCoins]);
    }

}
