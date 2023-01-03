<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Assets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @foreach ($bankAccounts as $bankAccount)
                        Account name: {{ $bankAccount->name }}
                        Account number: {{ $bankAccount->account_number }}
                        Currency: {{ $bankAccount->currency }}
                        Money available: {{ $bankAccount->amount }} {{ $bankAccount->currency }}
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
