<?php

namespace App\Models;

class CryptoCoin
{
    public string $name;
    public string $symbol;
    public float $price;
    public float $priceChange1h;
    public float $priceChange24h;
    public float $market_cap;

    public function __construct(
        string $name,
        string $symbol,
        float $price,
        float $priceChange1h,
        float $priceChange24h,
        float $market_cap)
    {
        $this->name = $name;
        $this->symbol = $symbol;
        $this->price = $price;
        $this->priceChange1h = $priceChange1h;
        $this->priceChange24h = $priceChange24h;
        $this->market_cap = $market_cap;
    }
}
