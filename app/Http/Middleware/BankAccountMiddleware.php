<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\BankAccount;

class BankAccountMiddleware
{
    public function handle($request, Closure $next)
    {
        $bankAccounts = BankAccount::where('user_id', auth()->id())->get();
        view()->share('bankAccounts', $bankAccounts);

        return $next($request);
    }
}
