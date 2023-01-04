<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TransferController extends Controller
{
    public function index(): View
    {
        $accounts = auth()->user()->accounts()->get();
        return view('transfers.index', ['accounts' => $accounts]);
    }

    //TODO: transfer money from one account to another user's account, check if the sender user has enough money in the account
    public function transfer(Request $request, Account $account): RedirectResponse
    {
        $request->validateWithBag('transfer', [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'account' => ['required', 'exists:accounts,id'],
        ]);

        if ($account->user_id !== Auth::user()->id) {
            return abort('403');
        }

        // TODO: check if the recipient is the same user
        $toAccount = Account::findOrFail($request->account);

        if ($account->balance < $request->amount) {
            return Redirect::back()->withErrors(['insufficient-balance' => 'Not enough funds in the account']);
        }

        $account->balance -= $request->amount;
        $account->save();

        $toAccount->balance += $request->amount;
        $toAccount->save();

        //TODO: add a transaction to the transactions table

        return Redirect::back()->with('status', 'transfer-success');
    }
}
