<?php

namespace App\Http\Middleware;

use Closure;

class BankAccountMiddleware
{
    public function handle($request, Closure $next)
    {
        $bankAccounts = auth()->user()->bankAccounts()->get();
        view()->share('bankAccounts', $bankAccounts);

        return $next($request);
    }
}
