<x-app-layout>
    <style>
        .account-name:hover,
        .account-name:active {
            color: #6b7280;
            text-decoration: none;
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Get Account Statements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <section class="space-y-6">
                <div class="bg-white overflow-hidden shadow sm:rounded-lg">

                    @foreach ($accounts as $account)
                        @if($account->name !== 'CRYPTO')
                        <div class="p-6 border-b border-gray-200">
                            <a href="{{ route('statements.show', $account) }}" class="block font-bold text-xl mb-2 account-name">
                                {{ $account->name }}
                            </a>
                            <div class="text-gray-700 font-medium text-sm">
                                Account Number: {{ $account->number }}
                            </div>
                            <div class="text-gray-700 font-medium text-sm">
                                Balance: {{ $account->balance }} {{ $account->currency }}
                            </div>
                        </div>
                        @endif
                    @endforeach

                </div>
            </section>
        </div>
    </div>
</x-app-layout>
