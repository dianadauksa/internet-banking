<?php

namespace App\Models;

class CryptoCoin
{
    public string $name;
    public string $symbol;
    public float $price;
    public float $priceChange1h;
    public float $priceChange24h;
    public float $marketCap;

    public function __construct(
        string $name,
        string $symbol,
        float $price,
        float $priceChange1h,
        float $priceChange24h,
        float $marketCap)
    {
        $this->name = $name;
        $this->symbol = $symbol;
        $this->price = $price;
        $this->priceChange1h = $priceChange1h;
        $this->priceChange24h = $priceChange24h;
        $this->marketCap = $marketCap;
    }
}
