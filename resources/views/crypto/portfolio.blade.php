<x-app-layout>
    <style>
        .header {
            display: grid;
            grid-template-columns: repeat(7, 1fr); /* 6 columns */
            grid-template-rows: 1fr;
            text-align: center;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
            grid-template-areas:
        "coin name price amount value invested profit";
        }

        .crypto-coin {
            display: grid;
            grid-template-columns: repeat(7, 1fr); /* 6 columns */
            grid-template-rows: 1fr;
            align-items: center;
            text-align: center;
            grid-template-areas:
        "coin name price amount value invested profit";
        }

        li {
            border-bottom: 1px solid #ccc;
            padding-top: 4px;
            padding-bottom: 4px;
        }

        #total {
            border-bottom: none;
            padding-top: 4px;
            padding-bottom: 4px;
        }

        img {
            width: 48px;
            height: 48px;
        }

        h1 {
            padding-top: 2px;
            font-weight: bold;
        }

        h2 {
            margin-bottom: 8px;
        }

        p {
            margin-bottom: 8px;
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Crypto Portfolio') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    <div class="header">
                        <div class="header-coin">COIN</div>
                        <div class="header-name">NAME</div>
                        <div class="header-price">PRICE IN USD</div>
                        <div class="header-amount">AMOUNT OWNED</div>
                        <div class="header-value">VALUE</div>
                        <div class="header-invested">INVESTED</div>
                        <div class="header-profit">PROFIT/LOSS</div>
                    </div>
                    <ul>
                        @foreach($userCoins as $coin)
                            <li class="crypto-coin">
                                <a href="{{ route('crypto.show', $coin->coin) }}">
                                <img src="{{ $coin->getCrypto()->logoURL }}" alt="{{$coin->coin}}" class="mx-auto">
                                </a>
                                <a href="{{ route('crypto.show', $coin->coin) }}">
                                <h2 class="text-gray-600">{{ $coin->getCrypto()->name }} ({{ $coin->coin }})</h2>
                                </a>
                                <p class="text-gray-600">$ {{ number_format($coin->getCrypto()->price, 2) }}</p>
                                <p>{{ $coin->amount }}</p>
                                <p>${{ number_format(($coin->getCrypto()->price * $coin->amount), 2) }}</p>
                                <p>${{ number_format(($coin->amount * $coin->avg_price), 2) }}</p>
                                @if( ($coin->getCrypto()->price * $coin->amount - $coin->amount * $coin->avg_price) > 0)
                                <p class="text-green-600">${{number_format(($coin->getCrypto()->price * $coin->amount - $coin->amount * $coin->avg_price), 2)}}</p>
                                @else
                                <p class="text-red-600">${{number_format(($coin->getCrypto()->price * $coin->amount - $coin->amount * $coin->avg_price), 2)}}</p>
                                @endif
                            </li>
                        @endforeach
                            <li class="crypto-coin" id="total">
                                <br>
                                <p></p>
                                <p></p>
                                <h1>TOTAL:</h1>
                                <h1>${{ number_format($value, 2) }}</h1>
                                <h1>${{ number_format($invested, 2) }}</h1>
                                @if($value-$invested > 0)
                                <h1 class="text-green-600">${{ number_format($value-$invested, 2) }}</h1>
                                @else
                                <h1 class="text-red-600">${{ number_format($value-$invested, 2) }}</h1>
                                @endif
                            </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
