<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crypto Transactions') }}
        </h2>
    </x-slot>

    @if (session('status') === 'account-created')
        <p
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 4000)"
            class="mt-4 text-center text-lg text-green-600"
        >{{ __('Crypto Account Opened') }}</p>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($account)
                <section class="space-y-6">
                    <div class="bg-white overflow-hidden shadow sm:rounded-lg">

                        <div class="p-6 border-b border-gray-200">
                            <a href="{{ route('accounts.show', $account) }}" class="block font-bold text-xl mb-2 account-name">
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
            @else
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('crypto.partials.create-form')
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>


