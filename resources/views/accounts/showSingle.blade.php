<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Your Accounts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="block font-bold text-xl mb-2 account-name">
                        {{ $account->name }}
                    </div>
                    <div class="text-gray-700 font-medium text-sm">
                        Account Number: {{ $account->number }}
                    </div>
                    <div class="text-gray-700 font-medium text-sm">
                        Money available: {{ $account->balance }} {{ $account->currency }}
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('accounts.partials.delete-account-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
