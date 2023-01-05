<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatementController extends Controller
{
    public function index(): View
    {
        $accounts = auth()->user()->accounts()->get();
        return view('statements.all', ['accounts' => $accounts]);
    }

    public function show(Account $account): View
    {
        $transactions = auth()->user()->transactions()->where('account_from_id', $account->id)->orWhere('account_to_id', $account->id)->get();
        $transactions = $transactions->sortByDesc('created_at');
        return view('statements.single', ['transactions' => $transactions, 'account' => $account]);
    }
}
