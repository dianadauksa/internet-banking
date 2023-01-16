<?php

namespace App\Repositories;

use App\Models\CryptoCoin;
use App\Models\CryptoCoinCollection;
use GuzzleHttp\Client;

class CoinMarketCapRepository
{
    public function getData(): CryptoCoinCollection
    {
        $client = new Client();
        $response = $client->request(
            'GET',
            'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest', [
            'headers' => [
                'X-CMC_PRO_API_KEY' => $_ENV["COINMARKETCAP_API_KEY"]
            ],
            'query' => ['limit' => 9]
        ]);

        $collection = new CryptoCoinCollection();
        $data = json_decode($response->getBody()->getContents(), true);
        foreach ($data['data'] as $coin) {
            $logo_url = $this->getLogoUrl($coin['symbol']);
            $cryptoCoin = new CryptoCoin(
                $coin['name'],
                $coin['symbol'],
                $logo_url,
                $coin['quote']['USD']['price'],
                $coin['quote']['USD']['percent_change_1h'],
                $coin['quote']['USD']['percent_change_24h'],
                $coin['quote']['USD']['market_cap']
            );
            $collection->add($cryptoCoin);
        }
        return $collection;
    }

    public function getLogoUrl($symbol): string
    {
        $client = new Client();
        $response = $client->request(
            'GET',
            'https://pro-api.coinmarketcap.com/v1/cryptocurrency/info', [
            'headers' => [
                'X-CMC_PRO_API_KEY' => $_ENV["COINMARKETCAP_API_KEY"],
            ],
            'query' => ['symbol' => $symbol]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        return $data['data'][$symbol]['logo'];
    }

    public function getSingle(string $symbol): CryptoCoin
    {
        $client = new Client();
        $response = $client->request(
            'GET',
            'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest', [
            'query' => [
                'symbol' => $symbol
            ],
            'headers' => [
                'X-CMC_PRO_API_KEY' => $_ENV["COINMARKETCAP_API_KEY"]
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        $coinData = $data['data'][$symbol];
        $logo_url = $this->getLogoUrl($symbol);
        return new CryptoCoin(
            $coinData['name'],
            $coinData['symbol'],
            $logo_url,
            $coinData['quote']['USD']['price'],
            $coinData['quote']['USD']['percent_change_1h'],
            $coinData['quote']['USD']['percent_change_24h'],
            $coinData['quote']['USD']['market_cap']
        );
    }
}
