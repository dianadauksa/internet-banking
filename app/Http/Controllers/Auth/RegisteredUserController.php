<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\{User, Account};
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        do {
            $userNumber = str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
        } while (User::where('number', $userNumber)->exists());

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
            'number' => $userNumber,
            'security_codes' => $securityCodes,
        ]);

        do {
            $prefix = 'LV77ORCL';
            $suffix = str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
            $accountNumber = $prefix . $suffix;
        } while (Account::where('number', $accountNumber)->exists());

        $account = (new Account)->fill([
            'name' => 'MAIN',
            'number' => $accountNumber,
            'balance' => 0.00,
        ]);
        $account->user()->associate($user);
        $account->save();

        event(new Registered($user));
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
