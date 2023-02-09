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
            padding-top: 16px;
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

        .crypto-link:hover,
        .crypto-link:active {
            color: #6b7280;
            text-decoration: none;
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
                    <h1 class="font-semibold text-xl text-gray-800 leading-tight">Your Owned Cryptos</h1>
                    <div class="header">
                        <div class="header-coin">COIN</div>
                        <div class="header-name">NAME</div>
                        <div class="header-price">PRICE IN USD</div>
                        <div class="header-amount">AMOUNT</div>
                        <div class="header-value">VALUE</div>
                        <div class="header-invested">INVESTED</div>
                        <div class="header-profit">PROFIT/LOSS</div>
                    </div>
                    <ul>
                        @foreach($userCoins as $coin)
                            @if($coin->amount > 0)
                                <li class="crypto-coin">
                                    <a href="{{ route('crypto.show', $coin->coin) }}">
                                        <img src="{{ $coin->getCrypto()->logoURL }}" alt="{{$coin->coin}}"
                                             class="mx-auto">
                                    </a>
                                    <a href="{{ route('crypto.show', $coin->coin) }}">
                                        <h2 class="text-gray-700 crypto-link">{{ $coin->getCrypto()->name }} ({{ $coin->coin }}
                                            )</h2>
                                    </a>
                                    <p class="text-gray-700">$ {{ number_format($coin->getCrypto()->price, 2) }}</p>
                                    <p class="text-gray-700">{{ $coin->amount }}</p>
                                    <p class="text-gray-700">${{ number_format(($coin->getCrypto()->price * $coin->amount), 2) }}</p>
                                    <p class="text-gray-700">${{ number_format(($coin->amount * $coin->avg_price), 2) }}</p>
                                    @if( ($coin->getCrypto()->price * $coin->amount - $coin->amount * $coin->avg_price) > 0)
                                        <p class="text-green-600">
                                            ${{number_format(($coin->getCrypto()->price * $coin->amount - $coin->amount * $coin->avg_price), 2)}}</p>
                                    @else
                                        <p class="text-red-600">
                                            ${{number_format(($coin->getCrypto()->price * $coin->amount - $coin->amount * $coin->avg_price), 2)}}</p>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                        <li class="crypto-coin" id="total">
                            <br>
                            <p></p>
                            <p></p>
                            <h1>TOTAL:</h1>
                            <h1>${{ number_format($ownedValue, 2) }}</h1>
                            <h1>${{ number_format($ownedInvested, 2) }}</h1>
                            @if($ownedValue > $ownedInvested)
                                <h1 class="text-green-600">${{ number_format($ownedValue-$ownedInvested, 2) }}</h1>
                            @else
                                <h1 class="text-red-600">${{ number_format($ownedValue-$ownedInvested, 2) }}</h1>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    <h1 class="font-semibold text-xl text-gray-800 leading-tight">Your Shortlisted Cryptos</h1>
                    <div class="header">
                        <div class="header-coin">COIN</div>
                        <div class="header-name">NAME</div>
                        <div class="header-price">PRICE IN USD</div>
                        <div class="header-amount">AMOUNT</div>
                        <div class="header-value">PRICE NOW</div>
                        <div class="header-invested">SHORTLISTED FOR</div>
                        <div class="header-profit">PROFIT/LOSS</div>
                    </div>
                    <ul>
                        @foreach($userCoins as $coin)
                            @if($coin->amount < 0)
                                <li class="crypto-coin">
                                    <a href="{{ route('crypto.show', $coin->coin) }}">
                                        <img src="{{ $coin->getCrypto()->logoURL }}" alt="{{$coin->coin}}"
                                             class="mx-auto">
                                    </a>
                                    <a href="{{ route('crypto.show', $coin->coin) }}">
                                        <h2 class="text-gray-700 crypto-link">{{ $coin->getCrypto()->name }} ({{ $coin->coin }}
                                            )</h2>
                                    </a>
                                    <p class="text-gray-700">$ {{ number_format($coin->getCrypto()->price, 2) }}</p>
                                    <p class="text-gray-700">{{ -$coin->amount }}</p>
                                    <p class="text-gray-700">${{ number_format(($coin->getCrypto()->price * -$coin->amount), 2) }}</p>
                                    <p class="text-gray-700">${{ number_format((-$coin->amount * $coin->avg_price), 2) }}</p>
                                    @if(($coin->getCrypto()->price * -$coin->amount) < (-$coin->amount * $coin->avg_price))
                                        <p class="text-green-600">
                                            ${{number_format((-$coin->amount * $coin->avg_price)-($coin->getCrypto()->price * -$coin->amount), 2)}}</p>
                                    @else
                                        <p class="text-red-600">
                                            ${{number_format((-$coin->amount * $coin->avg_price)-($coin->getCrypto()->price * -$coin->amount), 2)}}</p>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                        <li class="crypto-coin" id="total">
                            <br>
                            <p></p>
                            <p></p>
                            <h1>TOTAL:</h1>
                            <h1>${{ number_format($shortedPrice, 2) }}</h1>
                            <h1>${{ number_format($shortedFor, 2) }}</h1>
                            @if($shortedPrice < $shortedFor)
                                <h1 class="text-green-600">${{ number_format($shortedFor-$shortedPrice, 2) }}</h1>
                            @else
                                <h1 class="text-red-600">${{ number_format($shortedFor-$shortedPrice, 2) }}</h1>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
