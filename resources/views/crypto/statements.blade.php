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

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    <ul>
                        @foreach ($transactions as $transaction)
                            <li class="flex items center space-x-4 py-1 border-b border-gray-300">
                                <div class="flex-1">
                                    <div class="text-gray-700 font-bold">
                                        {{$transaction->type}} {{$transaction->coin}}
                                    </div>
                                    <div class="text-gray-500 text-sm">
                                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                <div class="text-gray-600 font-bold">
                                    {{ $transaction->amount }} x $ {{ $transaction->price }}
                                    <span class="font-extrabold">Total: $ {{ $transaction->total}}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>