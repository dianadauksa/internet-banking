<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function crypto()
    {
        return $this->belongsTo(CryptoCoin::class);
    }
}
