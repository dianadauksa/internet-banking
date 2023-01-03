<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class BankAccountController extends Controller
{
    /**
     * Display all user's bank accounts
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('accounts.show');
    }

    /**
     * Add a new bank account to the bank accounts table and link it to the user
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        do {
            $prefix = 'LV77ORCL';
            $suffix = str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
            $accountNumber = $prefix . $suffix;
        } while (BankAccount::where('account_number', $accountNumber)->exists());

        $bankAccount = new BankAccount([
            'name' => $request->name ?? 'New account',
            'account_number' => $accountNumber,
            'currency' => $request->currency ?? 'EUR',
            'balance' => 0.00,
            'user_id' => $request->user()->id,
        ]);
        $bankAccount->save();

        return Redirect::route('accounts.show')->with('status', 'account-created');
    }

    /**
     * Delete a user's bank account from the bank accounts table only if the balance is 0.00
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $request->validateWithBag('bankAccountDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $bankAccount = BankAccount::where('account_number', $request->account_number)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($bankAccount->balance !== 0.00) {
            return Redirect::route('accounts.show')->with('status', 'cannot-delete-account-balance');
        } elseif ( $bankAccount->name === 'MAIN') {
            return Redirect::route('accounts.show')->with('status', 'cannot-delete-account-main');
        } else {
            $bankAccount->delete();
        }

        return Redirect::route('accounts.show')->with('status', 'account-deleted');
    }
}
