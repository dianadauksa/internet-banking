<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Display all user's bank accounts
     */
    public function index(): View
    {
        $accounts = auth()->user()->accounts()->get();
        return view('accounts.showAll', ['accounts' => $accounts]);
    }

    public function show(Account $account): View
    {
        if ($account->user_id !== Auth::user()->id) {
            return abort('403');
        }
        return view('accounts.showSingle', ['account' => $account]);
    }

    public function update(Request $request, Account $account): RedirectResponse
    {
        if ($account->user_id !== Auth::user()->id) {
            return abort('403');
        }
        if ($account->name === 'MAIN') {
            return Redirect::back()->withErrors(['main-account' => 'You cannot change the name of MAIN account']);
        }
        $account->update($request->only('name'));
        return Redirect::back()->with('status', 'name-updated');
    }

    /**
     * Add a new bank account to the bank accounts table and link it to the user
     */
    public function store(Request $request): RedirectResponse
    {
        do {
            $prefix = 'LV77ORCL';
            $suffix = str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
            $number = $prefix . $suffix;
        } while (Account::where('number', $number)->exists());

        // TODO: modify database schema to include a unique constraint on the account number column

        $newAccount = (new Account)->fill([
            'name' => $request->name ?? 'New account',
            'number' => $number,
            'currency' => $request->currency,
            'balance' => 0.00,
        ]);
        $newAccount->user()->associate(auth()->user());
        $newAccount->save();

        return Redirect::back()->with('status', 'account-created');
    }

    /**
     * Delete a user's bank account from the bank accounts table only if the balance is 0.00
     */
    public function delete(Account $account, Request $request): RedirectResponse
    {
        if ($account->user_id !== Auth::user()->id) {
            return abort('403');
        }

        $request->validateWithBag('bankAccountDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        if ($account->balance !== "0.00") {
            return Redirect::route('accounts.show', $account)->with('status', 'cannot-delete-account-balance');
        } elseif ( $account->name === 'MAIN') {
            return Redirect::route('accounts.show', $account)->with('status', 'cannot-delete-account-main');
        } else {
            $account->delete();
        }

        return Redirect::route('accounts.showAll')->with('status', 'account-deleted');
    }
}
