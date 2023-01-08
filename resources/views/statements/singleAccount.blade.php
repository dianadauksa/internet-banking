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
            {{ __('Transactions') }} for {{ $account->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <section class="space-y-6">
                <div class="bg-white overflow-hidden shadow sm:rounded-lg">

                    <div class="p-6 border-b border-gray-200">
                        <a href="{{ route('statements.show', $account) }}"
                           class="block font-bold text-xl mb-2 account-name">
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
                <form method="post" action="{{ route('statements.filter', $account) }}">
                    @csrf

                    <div class="flex items-center space-x-4 py-2 border-b border-gray-300">
                        <input type="text" name="account_number" placeholder="Search by account number" class="form-input w-52 rounded-md shadow-sm mr-2" value="{{ request('account_number') }}">
                        <input type="text" name="sender" placeholder="Search by sender name" class="form-input w-52 rounded-md shadow-sm mr-2" value="{{ request('sender') }}">
                        <input type="text" name="recipient" placeholder="Search by recipient name" class="form-input w-52 rounded-md shadow-sm mr-2" value="{{ request('recipient') }}">
                        From <input type="date" name="from" class="form-input w-44 rounded-md shadow-sm mr-2 ml-1" value="{{ request('from') }}">
                        To <input type="date" name="to" class="form-input w-44 rounded-md shadow-sm mr-1 ml-1" value="{{ request('to') }}">
                        <x-primary-button class="ml-2">Search transactions</x-primary-button>
                    </div>
                </form>

            </section>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    <ul>
                        @foreach ($transactions as $transaction)

                            @if ($transaction->type == 'OUTGOING' and $transaction->account_to_id !== $account->id)
                                <a href="{{ route('statements.transaction', $transaction) }}">
                                <li class="flex items center space-x-4 py-1 border-b border-gray-300">
                                    <div class="flex-1">
                                        <div class="text-gray-700 font-bold">
                                            @if ($transaction->getAccountTo->user == $transaction->getAccountFrom->user)
                                                To your {{ $transaction->getAccountTo->name }} account
                                                <small class="ml-2">({{ $transaction->getAccountTo->number }})</small>
                                            @else
                                                To {{ $transaction->getAccountTo->user->getFullName() }}
                                                <small class="ml-2">({{ $transaction->getAccountTo->number }})</small>
                                            @endif
                                        </div>
                                        @if ($transaction->description)
                                        <div class="text-gray-500 text-sm">
                                            {{ $transaction->description }}
                                        </div>
                                        @endif
                                        <div class="text-gray-500 text-sm">
                                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="text-red-600 font-bold">
                                        -{{ $transaction->amount }} {{ $account->currency }}
                                    </div>
                                </li>
                                </a>
                            @elseif ($transaction->type == 'INCOMING' and $transaction->account_from_id !== $account->id)
                                <a href="{{ route('statements.transaction', $transaction) }}">
                                <li class="flex items center space-x-4 py-1 border-b border-gray-300">
                                    <div class="flex-1">
                                        <div class="text-gray-700 font-bold">
                                            @if ($transaction->getAccountFrom->user == $transaction->getAccountTo->user)
                                                From your {{ $transaction->getAccountFrom->name }} account
                                                <small class="ml-2">({{ $transaction->getAccountFrom->number }})</small>
                                            @else
                                            From {{ $transaction->getAccountFrom->user->getFullName() }}
                                                <small class="ml-2">({{ $transaction->getAccountFrom->number }})</small>
                                            @endif
                                        </div>
                                        @if ($transaction->description)
                                            <div class="text-gray-500 text-sm">
                                                {{ $transaction->description }}
                                            </div>
                                        @endif
                                        <div class="text-gray-500 text-sm">
                                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="text-gray-700 font-bold">
                                        +{{ $transaction->amount }} {{ $account->currency }}
                                    </div>
                                </li>
                                </a>
                            @endif

                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
