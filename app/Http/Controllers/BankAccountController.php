<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BankAccountOpenRequest;
use Illuminate\Support\Facades\Redirect;

class BankAccountController extends Controller
{
    /**
     * Display all user's bank accounts
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        return view('accounts.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Add a new bank account to the bank accounts table and link it to the user
     *
     * @param  \App\Http\Requests\BankAccountOpenRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(BankAccountOpenRequest $request)
    {
        $request->user()->bankAccounts()->create($request->validated());

        return Redirect::route('accounts.show')->with('status', 'new bank account opened');
    }

    /**
     * Delete a user's bank account from the bank accounts table only if the balance is 0.00
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $request->validateWithBag('bankAccountDeletion', [
            'password' => ['required', 'current-password'],
        ]);
        //delete the chosen (where account_number is the chosen accounts number) user's bank account from the bank accounts table only if the balance is 0.00
        $bankAccount = $request->user()->bankAccounts()->where('account_number', $request->account_number)->first();
        if ($bankAccount->balance == 0.00) {
            $bankAccount->delete();
        } else {
            return Redirect::route('accounts.show')->with('status', 'cannot delete account with a positive balance');
        }

        return Redirect::route('accounts.show')->with('status', 'bank account deleted');
    }
}
