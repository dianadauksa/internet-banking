<?php

namespace App\Repositories;

use App\Models\CryptoCoin;
use App\Models\CryptoCoinCollection;
use GuzzleHttp\Client;

class CoinMarketCapRepository
{
    public function getData()
    {
        $client = new Client();
        $response = $client->request(
            'GET',
            'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest', [
            'headers' => [
                'X-CMC_PRO_API_KEY' => $_ENV["COINMARKETCAP_API_KEY"]
            ]
        ]);

        $collection = new CryptoCoinCollection();
        $data = json_decode($response->getBody()->getContents(), true);
        foreach ($data['data'] as $coin) {
            $cryptoCoin = new CryptoCoin(
                $coin['name'],
                $coin['symbol'],
                $coin['quote']['USD']['price'],
                $coin['quote']['USD']['percent_change_1h'],
                $coin['quote']['USD']['percent_change_24h'],
                $coin['quote']['USD']['market_cap']
            );
            $collection->add($cryptoCoin);
        }
        return $collection;
    }
}
