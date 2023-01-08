<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatementController extends Controller
{
    public function index(): View
    {
        $accounts = auth()->user()->accounts()->get();
        return view('statements.allAccounts', ['accounts' => $accounts]);
    }

    public function show(Account $account): View
    {
        if ($account->user_id !== auth()->user()->id) {
            return abort('403');
        }
        $transactions = $account->transactions()->get();
        $transactions = $transactions->sortByDesc('created_at');
        return view('statements.singleAccount', ['transactions' => $transactions, 'account' => $account]);
    }

    public function showSingle(Transaction $transaction): View
    {
        if ($transaction->user->id !== auth()->user()->id) {
            return abort('403');
        }
        return view('statements.singleTransaction', ['transaction' => $transaction]);
    }

    public function filter(Request $request, Account $account): View
    {
        $transactions = $account->transactions()->get();

        if ($request->account_number) {
            $transactions = $transactions->filter(function ($transaction) use ($request) {
                return ($transaction->getAccountFrom->number === $request->account_number
                    || $transaction->getAccountTo->number === $request->account_number);
            });
        }

        if ($request->sender) {
            $transactions = $transactions->filter(function ($transaction) use ($request) {
                if (str_contains($transaction->getAccountFrom->user->getFulLName(), $request->sender)) {
                    return $transaction;
                }
                return null;
            });
        }

        if ($request->recipient) {
            $transactions = $transactions->filter(function ($transaction) use ($request) {
                if (str_contains($transaction->getAccountTo->user->getFulLName(), $request->recipient)) {
                    return $transaction;
                }
                return null;
            });
        }

        if ($request->form || $request->to) {
            $transactions = $transactions->filter(function ($transaction) use ($request) {
                if ($request->from && $request->to) {
                    return ($transaction->created_at->format('Y-m-d') >= $request->from
                        && $transaction->created_at->format('Y-m-d') <= $request->to);
                } elseif ($request->from) {
                    return ($transaction->created_at->format('Y-m-d') >= $request->from);
                }
                return ($transaction->created_at->format('Y-m-d') <= $request->to);
            });
        }

        $transactions = $transactions->sortByDesc('created_at');
        return view('statements.singleAccount', ['transactions' => $transactions, 'account' => $account]);
    }
}
