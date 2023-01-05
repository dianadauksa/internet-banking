<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, Cache, Redirect};
use Illuminate\View\View;

class TransferController extends Controller
{
    public function index(): View
    {
        $accounts = auth()->user()->accounts()->get();
        return view('transfers.index', ['accounts' => $accounts]);
    }

    public function makeTransfer(Request $request): RedirectResponse
    {
        $senderAccount = Account::where('id', $request->account_from)->firstOrFail();
        $receiverAccount = Account::where('number', $request->account_to)->firstOrFail();

        $rules = [
            'account_from' => 'required|exists:accounts,id',
            'account_to' => 'required|exists:accounts,number',
            'amount' => 'required|numeric|min:0.01|max:' . $senderAccount->balance,
        ];
        $this->validate($request, $rules);

        if ($senderAccount->user_id !== Auth::user()->id || $senderAccount->id === $receiverAccount->id) {
            return abort('403');
        }

        $exchangedAmount = $request->amount * $this->getExchangeRate($senderAccount, $receiverAccount);
        $senderAccount->balance -= $request->amount;
        $senderAccount->save();
        $receiverAccount->balance += $exchangedAmount;
        $receiverAccount->save();

        $this->recordTransactions($senderAccount, $receiverAccount, $request->amount, $exchangedAmount);

        return Redirect::back()->with('status', 'transfer-successful');
    }

    private function getExchangeRate(Account $accountFrom, Account $accountTo): float
    {
        $exchangeRate = 1;
        if ($accountFrom->currency !== $accountTo->currency) {
            $exchangeRate = $this->getExchangeRateFromApi($accountFrom->currency, $accountTo->currency);
        }
        return $exchangeRate;
    }

    private function getExchangeRateFromApi(string $currencyFrom, string $currencyTo): float
    {
        $url = "https://www.bank.lv/vk/ecb.xml";
        $xml = simplexml_load_file($url);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        if (Cache::has('exchange_rates')) {
            $exchangeRates = Cache::get('exchange_rates');
        } else {
            $currencyArray = [];
            foreach ($array['Currencies']['Currency'] as $currency) {
                $currencyArray[$currency['ID']] = $currency['Rate'];
            }
            $array['Currencies']['Currency'] = $currencyArray;
            Cache::put('exchange_rates', $currencyArray, 5); // stored for 5 minutes

            $exchangeRates = Cache::get('exchange_rates');
        }

        if ($currencyFrom === 'EUR') {
           return $exchangeRates[$currencyTo];
        } elseif ($currencyTo === 'EUR') {
           return 1 / $exchangeRates[$currencyFrom];
        }
        return $exchangeRates[$currencyTo] / $exchangeRates[$currencyFrom];
    }

    private function recordTransactions(Account $accountFrom, Account $accountTo, float $amount, float $exchangedAmount): void
    {
        $transactionOut = (new Transaction)->fill([
            'account_from_id' => $accountFrom->id,
            'account_to_id' => $accountTo->id,
            'amount' => $amount,
            'currency' => $accountFrom->currency,
            'type' => 'OUTGOING',
        ]);
        $transactionOut->save();

        $transactionIn = (new Transaction)->fill([
            'account_from_id' => $accountFrom->id,
            'account_to_id' => $accountTo->id,
            'amount' => $exchangedAmount,
            'currency' => $accountTo->currency,
            'type' => 'INCOMING',
        ]);
        $transactionIn->save();
    }
}