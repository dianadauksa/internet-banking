<?php

namespace App\Models;

use App\Repositories\CoinMarketCapRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class UserCrypto extends Model
{
    use HasFactory;

    protected $table = 'user_cryptos';

    protected $fillable = [
        'account_id',
        'coin',
        'amount',
        'avg_price'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function getCrypto(): CryptoCoin
    {
        $crypto = Cache::get('coins')->where('symbol', $this->coin)->first();
        if ($crypto === null) {
            $coinMarketCapRepository = new CoinMarketCapRepository();
            $crypto = $coinMarketCapRepository->getSingle($this->coin);
            Cache::put('coins', Cache::get('coins')->push($crypto), now()->addMinutes(120));
        }
        return $crypto;
    }
}
