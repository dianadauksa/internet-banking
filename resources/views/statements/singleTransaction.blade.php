<x-app-layout>
    <style>
        i {
            font-size: 30px !important;
            padding: 5px;
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <section>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-2xl mx-auto">
                        <div class="card">
                            <div class="card-header p-4 text-center flex items-center justify-between">
                                <div class="text-gray-800 text-xl font-bold px-4">
                                    @if ($transaction->type == 'INCOMING')
                                        + {{ $transaction->amount }} {{ $transaction->currency }}
                                    @else
                                        - {{ $transaction->amount }} {{ $transaction->currency }}
                                    @endif
                                </div>
                                <div class="text-gray-600 text-2xl font-bold ml-4">
                                    Transaction No. {{ $transaction->id }}
                                </div>
                                <div class="text-gray-600 text-2xl mr-1">
                                    {{ $transaction->created_at->format('l, d/m/Y H:i') }}
                                </div>
                            </div>
                            <div class="card-body p-4 ml-2 mr-2 text-center flex items-center justify-between">

                                <div class="font-bold text-gray-800">
                                    <i class="fa fa-user"></i>
                                    Sender:
                                    <span class="text-gray-600">
                                                {{ $transaction->getAccountFrom->user->getFullName() }}
                                                <small>({{ $transaction->getAccountFrom->number }})</small>
                                            </span>
                                </div>
                                <div class="font-bold text-gray-800 mr-2">
                                    <i class="fa fa-arrow-right"></i>
                                </div>
                                <div class="font-bold text-gray-800 mr-1">
                                    <i class="fa fa-user"></i>
                                    Recipient:
                                    <span class="text-gray-600">
                                            {{ $transaction->getAccountTo->user->getFullName() }}
                                                <small>({{ $transaction->getAccountTo->number }})</small>
                                        </span>
                                </div>

                            </div>
                            @if ($transaction->description)

                                <div class="font-bold text-gray-800 text-center">
                                    Description:
                                    <span class="text-gray-600">
                                            {{ $transaction->description }}
                                        </span>

                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </section>
        </div>
    </div>

</x-app-layout>
