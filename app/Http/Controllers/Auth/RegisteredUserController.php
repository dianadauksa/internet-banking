<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BankAccount;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        do {
            $userNumber = str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
        } while (User::where('user_number', $userNumber)->exists());

        $codes = [];
        for ($i = 0; $i < 10; $i++) {
            $codes[] = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        }
        $securityCodes = json_encode($codes);

        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_number' => $userNumber,
            'security_codes' => $securityCodes,
        ]);

        do {
            $prefix = 'LV77ORCL';
            $suffix = str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
            $accountNumber = $prefix . $suffix;
        } while (BankAccount::where('account_number', $accountNumber)->exists());

        $bankAccount = new BankAccount([
            'name' => 'MAIN',
            'account_number' => $accountNumber,
            'currency' => 'EUR',
            'balance' => 0.00,
            'user_id' => $user->id,
        ]);
        $bankAccount->save();

        event(new Registered($user));
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
