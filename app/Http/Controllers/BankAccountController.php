<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class BankAccountController extends Controller
{
    /**
     * Display all user's bank accounts
     */
    public function show(): View
    {
        return view('accounts.show');
    }

    /**
     * Add a new bank account to the bank accounts table and link it to the user
     */
    public function add(Request $request): RedirectResponse
    {
        do {
            $prefix = 'LV77ORCL';
            $suffix = str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
            $accountNumber = $prefix . $suffix;
        } while (BankAccount::where('account_number', $accountNumber)->exists());

        // TODO: modify database schema to include a unique constraint on the account_number column and rename account_number to number

        $newAccount = (new BankAccount)->fill([
            'name' => $request->name ?? 'New account',
            'account_number' => $accountNumber,
            'currency' => $request->currency,
            'balance' => 0.00,
        ]);
        $newAccount->user()->associate(auth()->user());
        $newAccount->save();

        return Redirect::route('accounts.show')->with('status', 'account-created');
    }

    /**
     * Delete a user's bank account from the bank accounts table only if the balance is 0.00
     */
    public function delete(Request $request): RedirectResponse
    {
        $request->validateWithBag('bankAccountDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $bankAccount = auth()->user()->bankAccounts()->where('account_number', $request->account_number)->first();

        if ($bankAccount->balance !== "0.00") {
            return Redirect::route('accounts.show')->with('status', 'cannot-delete-account-balance');
        } elseif ( $bankAccount->name === 'MAIN') {
            return Redirect::route('accounts.show')->with('status', 'cannot-delete-account-main');
        } else {
            $bankAccount->delete();
        }

        return Redirect::route('accounts.show')->with('status', 'account-deleted');
    }
}
