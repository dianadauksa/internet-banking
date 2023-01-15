<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CryptoController extends Controller
{
    public function index(): View
    {
        $account = auth()->user()->accounts()->where('name', 'CRYPTO')->first();
        return view('crypto.main', ['account' => $account]);
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
