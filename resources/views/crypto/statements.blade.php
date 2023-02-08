<style>
    #coin, #amount, #total {
        font-weight: bold;
    }
</style>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crypto Transactions') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <section class="space-y-6">
                <div class="bg-white overflow-hidden shadow sm:rounded-lg">

                    <div class="p-6 border-b border-gray-200">
                        <a href="{{ route('crypto.statements', $account) }}" class="block font-bold text-xl mb-2 account-name">
                            {{ $account->name }}
                        </a>
                        <div class="text-gray-700 font-medium text-sm">
                            Account Number: {{ $account->number }}
                        </div>
                        <div class="text-gray-700 font-medium text-sm">
                            Balance: {{ $account->balance }} {{ $account->currency }}
                        </div>
                    </div>

                </div>
            </section>
            <section>
                <form method="post" action="{{route('crypto.statements.filter', $account)}}">
                    @csrf

                    <div class="flex items-center space-x-4 py-2 border-b border-gray-300">
                        <input type="text" name="coin" placeholder="Search by coin, e.g. BNB" class="form-input w-52 rounded-md shadow-sm mr-2" value="{{ request('coin') }}">
                        <span class="px-2">From</span><input type="date" name="from" class="form-input w-44 rounded-md shadow-sm mr-2" value="{{ request('from') }}">
                        <span class="px-2">To</span><input type="date" name="to" class="form-input w-44 rounded-md shadow-sm mr-2" value="{{ request('to') }}">
                        <label for="buy" class="ml-2 px-1">Buy</label><input type="radio" id="buy" name="type" value="buy">
                        <label for="sell" class="ml-1 px-1">Sell</label><input type="radio" id="sell" name="type" value="sell">
                        <label for="shortlist" class="ml-1 px-1">Shortlist</label><input type="radio" id="shortlist" name="type" value="shortlist">
                        <label for="all" class="ml-1 px-1">All</label><input type="radio" id="all" name="type" value="all" class="mr-2">
                        <x-primary-button class="ml-2">Search transactions</x-primary-button>
                    </div>
                </form>

            </section>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    <ul>
                        @foreach ($transactions as $transaction)
                            <li class="flex items center space-x-4 py-1 border-b border-gray-300">
                                <div class="flex-1">
                                    <div class="text-gray-700">
                                        {{$transaction->type}} <span id="coin">{{$transaction->coin}}</span>
                                    </div>
                                    <div class="text-gray-500 text-sm">
                                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                <div class="text-gray-600 font-bold">
                                    <span id="amount">{{ $transaction->amount }}</span> x $ {{ $transaction->price }}
                                    <span id="total">Total:</span> $ {{ $transaction->total}}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
