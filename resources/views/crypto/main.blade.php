<x-app-layout>
    <style>
        .header {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: 1fr;
            text-align: center;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
            grid-template-areas:
        "coin name price change1h change24h";
        }

        .crypto-coin {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: 1fr;
            align-items: center;
            text-align: center;
            grid-template-areas:
        "coin name price change1h change24h";
        }

        li {
            border-bottom: 1px solid #ccc;
            padding-top: 4px;
            padding-bottom: 4px;
        }
        img {
            width: 48px;
            height: 48px;
        }
        h2 {
            margin-bottom: 8px;
        }
        p {
            margin-bottom: 8px;
        }
        .header-coin{
            flex-basis: 10%;
        }
        .header-name, .header-price, .header-change-1h, .header-change-24h {
            flex-basis: 20%;
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crypto Corner') }}
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
                            <a href="{{ route('crypto') }}" class="block font-bold text-xl mb-2 account-name">
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
                <section>
                    <form method="get" action="#" onsubmit="return submitForm(event)">
                        @csrf
                        <div class="flex items-center space-x-4 py-1">
                            <input type="text" name="symbol" placeholder="Search a coin, e.g. ADA" class="form-input w-52 rounded-md shadow-sm mr-2" value="{{ request('cryptoCoin') }}">
                            <x-primary-button class="ml-2">Search</x-primary-button>
                        </div>
                    </form>

                    <script>
                        function submitForm(event) {
                            event.preventDefault();
                            let symbol = document.querySelector("input[name='symbol']").value;
                            symbol = symbol.toUpperCase();
                            window.location.href = "{{ route('crypto.show', '') }}" + '/' + symbol;
                        }
                    </script>
                </section>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    <div class="header">
                        <div class="header-coin">COIN</div>
                        <div class="header-name">NAME</div>
                        <div class="header-price">PRICE IN USD</div>
                        <div class="header-change-1h">CHANGE IN 1H</div>
                        <div class="header-change-24h">CHANGE IN 24H</div>
                    </div>
                    <ul>
                            @foreach($coins as $coin)
                                <li class="crypto-coin">
                                    <a href="{{ route('crypto.show', $coin->symbol) }}">
                                        <img src="{{ $coin->logoURL }}" alt="{{ $coin->name }}" class="mx-auto">
                                    </a>
                                    <a href="">
                                        <h2>{{ $coin->name }} ({{ $coin->symbol }})</h2>
                                    </a>
                                    <p>$ {{ number_format($coin->price, 2) }}</p>
                                    @if ($coin->priceChange1h > 0)
                                        <p class="text-green-600">{{ number_format($coin->priceChange1h, 2) }}%</p>
                                    @else
                                    <p class="text-red-600">{{ number_format($coin->priceChange1h, 2) }}%</p>
                                    @endif
                                    @if($coin->priceChange24h > 0)
                                        <p class="text-green-600">{{ number_format($coin->priceChange24h,2) }}%</p>
                                    @else
                                        <p class="text-red-600">{{ number_format($coin->priceChange24h,2) }}%</p>
                                    @endif
                                </li>
                            @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>


