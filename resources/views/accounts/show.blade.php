<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Your Accounts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h2>{{ Auth::user()->firstName  }}, you can open new or delete your bank accounts here.</h2>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @foreach ($bankAccounts as $bankAccount)
                        Account name: {{ $bankAccount->name }}
                        Account number: {{ $bankAccount->account_number }}
                        Currency: {{ $bankAccount->currency }}
                        Money available: {{ $bankAccount->balance }} {{ $bankAccount->currency }}
                    @endforeach
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('accounts.partials.create-account-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('accounts.partials.delete-account-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
