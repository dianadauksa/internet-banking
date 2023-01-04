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
            {{ __('Manage Your Accounts') }}
        </h2>
    </x-slot>
    @if (session('status') === 'account-deleted')
        <p
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 4000)"
            class="mt-4 text-center text-lg text-red-600"
        >{{ __('Bank Account Deleted') }}</p>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('accounts.partials.all')

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('accounts.partials.create-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
